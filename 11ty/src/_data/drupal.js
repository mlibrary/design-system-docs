const EleventyFetch = require("@11ty/eleventy-fetch");

function slugify(string) {
  const a = 'àáâäæãåāăąçćčđďèéêëēėęěğǵḧîïíīįìıİłḿñńǹňôöòóœøōõőṕŕřßśšşșťțûüùúūǘůűųẃẍÿýžźż·/_,:;'
  const b = 'aaaaaaaaaacccddeeeeeeeegghiiiiiiiilmnnnnoooooooooprrsssssttuuuuuuuuuwxyyzzz------'
  const p = new RegExp(a.split('').join('|'), 'g')

  return string.toString().toLowerCase()
    .replace(/\s+/g, '-') // Replace spaces with -
    .replace(p, c => b.charAt(a.indexOf(c))) // Replace special characters
    .replace(/&/g, '-and-') // Replace & with 'and'
    .replace(/[^\w\-]+/g, '') // Remove all non-word characters
    .replace(/\-\-+/g, '-') // Replace multiple - with single -
    .replace(/^-+/, '') // Trim - from start of text
    .replace(/-+$/, '') // Trim - from end of text
}

module.exports = async function () {
  // 

  if ( ! process.env.DRUPAL_AUTH ) {
    console.error("⚠ DRUPAL_AUTH needs to be set in ENV: DRUPAL_AUTH=admin:$password npm start");
    process.exit(1);
  }
  
  let drupalMenuData = await EleventyFetch(`https://${process.env.DRUPAL_AUTH}@design-system-cms.kubernetes.lib.umich.edu/jsonapi/menu_link_content/menu_link_content`, {
    duration: "1d", // 1 day
    type: "json",
    method: 'GET',
  });

  let menuData = {}; let menuDataIndex = {};
  drupalMenuData.data.forEach((datum) => {
    if (datum.attributes.enabled == false) { return; }
    if (datum.attributes.menu_name != 'main') { return; }
    if (datum.attributes.link.uri.indexOf('entity:') < 0) { return; }
    let menuItem = {};
    menuItem.guid = datum.id;
    menuItem.title = datum.attributes.title;
    menuItem.slug = slugify(menuItem.title);
    menuItem.weight = datum.attributes.weight;
    menuItem.parentId = null;
    if (datum.attributes.parent && datum.attributes.parent.indexOf('menu_link_content') > -1) {
      menuItem.parentId = (datum.attributes.parent.split(":"))[1];
    }

    // convert the link into /node/1 to seed link modifications
    menuItem.link = menuItem.id = datum.attributes.link.uri.replace('entity:', '/');

    menuDataIndex[menuItem.guid] = menuItem.id;
    menuData[menuItem.link] = menuItem;
  })

  // now fix the menuItem.parent keys
  Object.keys(menuData).forEach((key) => {
    let value = menuData[key];
    let parent = menuData[menuDataIndex[value.parentId]];
    let tmp = [ value.slug ];
    if (parent) {
      value.parent = parent.id;
      tmp.unshift(parent.slug);
    }
    value.permalink = tmp.join('/');
  })

  if ( process.env.DEBUG ) {
    console.log("-- menuData", menuData);
  }

  let drupalPagesData = await EleventyFetch("https://design-system-cms.kubernetes.lib.umich.edu/jsonapi/node/page/", {
    duration: "1d", // 1 day
    type: "json",
    method: 'GET',
  });

  let drupalLandingPagesData = await EleventyFetch("https://design-system-cms.kubernetes.lib.umich.edu/jsonapi/node/landing_page/", {
    duration: "1d", // 1 day
    type: "json",
    method: 'GET',
  });

  let slugData = {};
  [drupalPagesData, drupalLandingPagesData].forEach((data) => {
    data.data.forEach((datum) => {
      let id = `/node/${datum.attributes.drupal_internal__nid}`;
      datum.link = id;
      slugData[id] = slugify(datum.attributes.title);
    });
  })

  if (process.env.DEBUG) {
    console.log("-- slugData", slugData);
  }

  // let pagesData = [];
  let retval = { page: [], landing_page: [] };

  [ drupalPagesData, drupalLandingPagesData ].forEach((data) => {
    data.data.forEach((datum) => {
      let pageItem = {};
      pageItem.guid = datum.id;
      pageItem.id = pageItem.link = datum.link;
      let menuItem = menuData[pageItem.id];
      if (menuItem === undefined) { return; }

      pageItem.title = datum.attributes.title;
      pageItem.slug = slugData[pageItem.id];
      pageItem.created = datum.attributes.created;
      pageItem.changed = datum.attributes.changed;
      if ( datum.attributes.body == null ) {
        pageItem.content = '<p>🤔</p>';
      } else {
        pageItem.content = datum.attributes.body.processed;
        pageItem.summary = datum.attributes.body.summary;
        if ( pageItem.summary && ! pageItem.content ) {
          pageItem.content = pageItem.summary;
        }
      }
      pageItem.tags = datum.type.replace('node--', '');

      // navigation data
      pageItem.permalink = menuItem.permalink;
      let parentId = menuItem.parentId;
      let parentTitle = 'home';
      if ( parentId ) {
        let parentItem = menuData[menuDataIndex[parentId]];
        parentTitle = parentItem.title;
      }

      pageItem.eleventyNavigation = {};
      pageItem.eleventyNavigation.key = pageItem.title;
      if (parentTitle) {
        pageItem.eleventyNavigation.parent = parentTitle;
      }

      console.log("-- navigation", parentTitle, "->", pageItem.eleventyNavigation.key);

      // pagesData.push(pageItem);
      retval[pageItem.tags].push(pageItem);
    })
  })


  // pagesData.forEach((pageItem) => {

  //   retval[pageItem.tags].push(pageItem);

  //   let parentSlug = null;
  //   let menuItem = menuData[pageItem.id];
  //   let parentId = menuItem.parentId;
  //   if (parentId) {
  //     let parentItem = menuData[menuDataIndex[parentId]];
  //     if (parentItem === undefined) {
  //       console.log("-- WTF", parentId, menuItem.id, menuItem.permalink);
  //       return;
  //     }
  //     parentSlug = parentItem.title;
  //     //  else {
  //     //   pageItem.slug = parentSlug + '/' + pageItem.slug;
  //     // }
  //   } else {
  //     parentSlug = 'home';
  //   }

  //   pageItem.eleventyNavigation = {};
  //   pageItem.eleventyNavigation.key = pageItem.title;
  //   if (parentSlug) {
  //     pageItem.eleventyNavigation.parent = parentSlug;
  //   }
  //   console.log("-- ", pageItem.id, pageItem.slug, menuItem.parent, parentSlug);
  //   pageItem.eleventyNavigation.order = menuItem.weight;
  //   // pageItem.permalink = pageItem.slug;
  // })


  console.log("-- # pagesData", Object.keys(retval));

  return retval;
  // return pagesData;
};