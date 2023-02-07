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

let retval = {};
module.exports = async function () {
  // 

  if ( retval.pageData ) {
    return retval;
  }

  if ( ! process.env.DRUPAL_AUTH ) {
    console.error("⚠ DRUPAL_AUTH needs to be set in ENV: DRUPAL_AUTH=admin:$password npm start");
    process.exit(1);
  }
  
  let drupalMenuData = await EleventyFetch(`${process.env.DRUPAL_URL}/jsonapi/menu_link_content/menu_link_content`, {
    duration: "1d", // 1 day
    type: "json",
    method: 'GET',
    fetchOptions: {
      headers: {
        "Authorization": "Basic " + Buffer.from(process.env.DRUPAL_AUTH).toString('base64')
      }
    }
  });

  let menuData = {}; let menuDataGuidMap = {}; let menuDataIndex = [];
  menuDataGuidMap['standard.front_page'] = 'standard.front_page';
  menuDataIndex.push('standard.front_page');
  menuData['standard.front_page'] = {
    slug: null,
    link: 'standard.front_page',
    title: 'home'
  };

  drupalMenuData.data.forEach((datum) => {
    if (datum.attributes.enabled == false) { return; }
    if (datum.attributes.menu_name != 'main') { return; }
    if (datum.attributes.link.uri.indexOf('entity:') < 0) { return; }
    let menuItem = {};
    menuItem.guid = datum.id;
    menuItem.title = datum.attributes.title;
    menuItem.slug = slugify(menuItem.title);
    menuItem.weight = datum.attributes.weight;
    menuItem.parentGuidId = null;
    if (datum.attributes.parent && datum.attributes.parent.indexOf('menu_link_content') > -1) {
      menuItem.parentGuidId = (datum.attributes.parent.split(":"))[1];
    } else {
      menuItem.parentGuidId = datum.attributes.parent;
    }

    // convert the link into /node/1 to seed link modifications
    menuItem.link = datum.attributes.link.uri.replace('entity:', '/');

    menuDataGuidMap[menuItem.guid] = menuItem.link;
    menuData[menuItem.link] = menuItem;
    menuDataIndex.push(menuItem.link);
  })

  // // now fix the menuItem.parent keys
  menuDataIndex.forEach((key) => {
    let menuItem = menuData[key];
    let parent = menuData[menuDataGuidMap[menuItem.parentGuidId]];
    let tmp = [ menuItem.slug ];
    if ( parent ) {
      menuItem.parent = parent.link;
      if ( parent.slug ) {
        tmp.unshift(parent.slug);
      }
    }
    menuItem.permalink = tmp.join('/');
  })

  if ( process.env.DEBUG ) {
    console.log("-- menuData", menuData);
    console.log("-- menuDataIndex", menuDataIndex);
    console.log("-- menuDataGuidMap", menuDataGuidMap);
  }

  let drupalPagesData = await EleventyFetch(`${process.env.DRUPAL_URL}/jsonapi/node/page/`, {
    duration: "1d", // 1 day
    type: "json",
    method: 'GET',
  });

  let drupalLandingPagesData = await EleventyFetch(`${process.env.DRUPAL_URL}/jsonapi/node/landing_page/`, {
    duration: "1d", // 1 day
    type: "json",
    method: 'GET',
  });

  let nodeLinkIndex = {};
  [drupalPagesData, drupalLandingPagesData].forEach((data) => {
    data.data.forEach((datum) => {
      let id = `/node/${datum.attributes.drupal_internal__nid}`;
      datum.link = id;
      nodeLinkIndex[id] = slugify(datum.attributes.title);
    });
  })

  if (process.env.DEBUG) {
    console.log("-- nodeLinkIndex", nodeLinkIndex);
  }

  // let retval = { page: [], landing_page: [] };
  retval.page = [];
  retval.landing_page = [];

  let pageData = {};
  [ drupalPagesData, drupalLandingPagesData ].forEach((data) => {
    data.data.forEach((datum) => {
      let pageItem = {};
      pageItem.guid = datum.id;
      pageItem.link = datum.link;
      let menuItem = menuData[pageItem.link];
      if (menuItem === undefined) { return; }

      pageItem.title = datum.attributes.title;
      pageItem.slug = nodeLinkIndex[pageItem.id];
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
      let parentLink = menuItem.parent;
      let parentTitle = 'home';
      if (parentLink ) {
        let parentItem = menuData[parentLink];
        parentTitle = parentItem.title;
      }

      pageItem.eleventyNavigation = {};
      pageItem.eleventyNavigation.key = pageItem.title;
      pageItem.eleventyNavigation.order = menuItem.weight;
      if (parentTitle) {
        pageItem.eleventyNavigation.parent = parentTitle;
      }

      if (process.env.DEBUG) {
        console.log("-- navigation", parentTitle, "->", pageItem.eleventyNavigation.key, pageItem.eleventyNavigation.order);
      }

      // retval[pageItem.tags].push(pageItem);
      pageData[pageItem.link] = pageItem;
    })
  })

  let menuStack = [ 'standard.front_page' ];
  while ( menuStack.length ) {
    let parentLink = menuStack.shift();
    let childMenuItems = menuDataIndex.filter((link) => {
      return (menuData[link].parent == parentLink );
    })
    childMenuItems.sort((a, b) => { 
      return menuData[a].title.localeCompare(menuData[b].title) || (menuData[a].weight - menuData[b].weight)  
    });
    childMenuItems.forEach((link) => {
      let pageItem = pageData[link];
      retval[pageItem.tags].push(pageItem);

      // ... as long as we do not have sub-pages
      if ( pageItem.tags == 'landing_page' ) {
        menuStack.push(link);
      }
    })
  }

  if (process.env.DEBUG) {
    console.log("-- # pagesData", Object.keys(retval));
  }

  retval.pageData = pageData;
  return retval;
};
