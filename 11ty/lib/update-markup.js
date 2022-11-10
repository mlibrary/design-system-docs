const { JSDOM } = require('jsdom');

module.exports = function (eleventyConfig, config = {}) {

  const _replaceMarkup = function (node) {
    node.childNodes.forEach((el) => {
      if (el.nodeType == 3) {
        let value = el.nodeValue;
        value = value.trim();
        if (value.indexOf('`') > -1) {
          let oldValue = value;
          value = value.replace(/`([^`]+)`/g, "<code>$1</code>");
          el.parentElement.innerHTML = value;
          if (process.env.DEBUG) {
            console.log("-- backticks", oldValue, "->", value);
          }
        } else if ( value.indexOf('https://codepen.io') == 0 ) {
          if (process.env.DEBUG) {
            console.log("-- codepen", value);
          }
          let parentEl = el.parentElement;
          // we're only replacing 
          if ( parentEl.tagName != 'P' ) { return ; }
          let codepenId = (value.trim().split('/pen/')).pop();
          if ( ! codepenId ) { return; }

          new_value = `<span>See <a href="${value}">
  example pen</a> by U-M Library Design System (<a href="https://codepen.io/team/umlibrary-designsystem">@umlibrary-designsystem</a>)
  on <a href="https://codepen.io">CodePen</a>.</span>`;
          
          parentEl.setAttribute('class', 'codepen');
          parentEl.dataset.height = '300';
          parentEl.dataset.defaultTab = 'html,result';
          parentEl.dataset.slugHash = codepenId;
          parentEl.dataset.user = 'umlibrary-designsystem';
          parentEl.innerHTML = new_value;
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
