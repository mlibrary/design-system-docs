module.exports = function (el) {
  if (el.nodeType == 3 && el.parentElement && el.parentElement.tagName == 'P') {
    let value = el.nodeValue.trim();
    if (value.match('^https://codepen.io/.*/pen/.*$')) {
      let oldValue = value;
      let parentEl = el.parentElement;
      // we're only replacing 
      let codepenId = (value.trim().split('/pen/')).pop();

      new_value = `<span>See <a href="${value}">
  example pen</a> by U-M Library Design System (<a href="https://codepen.io/team/umlibrary-designsystem">@umlibrary-designsystem</a>)
  on <a href="https://codepen.io">CodePen</a>.</span>`;

      parentEl.setAttribute('class', 'codepen');
      parentEl.dataset.height = '600';
      parentEl.dataset.defaultTab = 'html,result';
      parentEl.dataset.slugHash = codepenId;
      parentEl.dataset.user = 'umlibrary-designsystem';
      parentEl.dataset.themeId = '43179';
       parentEl.innerHTML = new_value;
   }
  }
}