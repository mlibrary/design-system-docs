const { JSDOM } = require('jsdom');

module.exports = function (eleventyConfig, config = {}) {

  function idify(string) {
    var id = eleventyConfig.javascriptFunctions.slug(string).replace(/[&,+()$~%.'":*?!<>{}]/g, "");
    return id;
  }

  eleventyConfig.addTransform('permalinking', async (rawContent, outputPath) => {
    if (!outputPath || !outputPath.endsWith(".html")) {
      return rawContent;
    }

    const dom = new JSDOM(rawContent);
    const mainEl = dom.window.document.querySelector('.main-content > .prose');
    if (!mainEl) { return rawContent; }

    mainEl.querySelectorAll('a[href]').forEach((link) => {
      let href = link.getAttribute('href'); let anchor;
      let new_href; let new_anchor;
      if (href == '#') {
        // set up an anchor link to the link text
        new_href = '';
        new_anchor = `#${idify(link.textContent)}`;
      } else if (href.indexOf('#') == 0) {
        new_href = '';
        new_anchor = `#${idify(href.substring(1))}`;
      } else {
        return;
      }
      if (new_href || new_anchor) {
        link.setAttribute('href', new_href + new_anchor);
        if (process.env.DEBUG) {
          console.log("-- relinking", href, "->", link.getAttribute('href'));
        }
      }
    })

    mainEl.querySelectorAll('h2,h3,h4').forEach((heading) => {
      let id = idify(heading.textContent);
      heading.setAttribute("id", id);
      if (process.env.DEBUG) {
        console.log("-- heading", heading.textContent, "->", id);
      }
    })

    return dom.serialize();
  })
}
