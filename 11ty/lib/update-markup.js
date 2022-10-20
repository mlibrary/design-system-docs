const { JSDOM } = require('jsdom');

module.exports = function (eleventyConfig, config = {}) {

  const _replaceMarkup = function (node) {
    node.childNodes.forEach((el) => {
      if (el.nodeType == 3) {
        let value = el.nodeValue;
        if (value.trim() && value.indexOf('`') > -1) {
          let oldValue = value;
          value = value.replace(/`([^`]+)`/g, "<code>$1</code>");
          el.parentElement.innerHTML = value;
          if (process.env.DEBUG) {
            console.log("-- backticks", oldValue, "->", value);
          }
        }
      } else {
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

    return dom.serialize();
  })
}
