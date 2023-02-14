const { JSDOM } = require('jsdom');
const transforms = [];
transforms.push(require('./transforms/backticks'));
transforms.push(require('./transforms/codepen'));
transforms.push(require('./transforms/docs-color-block'));

console.log("-- transforms", transforms);

module.exports = function (eleventyConfig, config = {}) {

  const _replaceMarkup = function (node) {
    node.childNodes.forEach((el) => {
      transforms.forEach((fn) => {
        fn(el);
      })
      if ( el.nodeType != 3 ) {
        _replaceMarkup(el);
      }
    })
  };

  eleventyConfig.addTransform('markup', async (rawContent, outputPath) => {
    if (!outputPath || !outputPath.endsWith(".html")) {
      return rawContent;
    }

    const dom = new JSDOM(rawContent);
    const mainEl = dom.window.document.querySelector('.main-content > .prose');
    if (!mainEl) { return rawContent; }

    _replaceMarkup(mainEl);

    if ( mainEl.querySelector('p.codepen[data-slug-hash]') ) {
      // add the codepen embed script
      let scriptEl = dom.window.document.createElement('script');
      scriptEl.async = true;
      scriptEl.setAttribute('src', 'https://cpwebassets.codepen.io/assets/embed/ei.js');
      dom.window.document.body.appendChild(scriptEl);
    }

    return dom.serialize();
  })
}
