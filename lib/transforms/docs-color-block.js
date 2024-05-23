const tokens = require('../../src/_data/tokens');
const nunjunks = require('nunjucks');

const template = `
  {% for key, palette in palettes %}
    <div class="color-palette">
      <h3 class="visually-hidden" id="{{key}}">{{key}}</h3>
      <ol>
        {% for value, token in palette %}
          <li id="{{ token.name }}" style="background: var(--{{ token.name }});">
            <code>var(--{{ token.name }})</code>
            <code>{{ token.original.value }}</code>
          </li>
        {% endfor %}
      </ol>
    </div>
  {% endfor %}
`;

module.exports = function (el) {
  
  if (el.nodeType == 1 && 
      el.tagName == 'CODE' && 
      el.getAttribute('class') && 
      el.getAttribute('class').startsWith('language-')) {

    let value = decodeURI(el.textContent.trim());
    if (value == '<docs-color-block></docs-color-bock>') {
      // build our color tokens display
      let sectionEl = el.ownerDocument.createElement('section');
      let preEl = el.parentElement;
      sectionEl.setAttribute('class', 'color-palettes');
      sectionEl.innerHTML = nunjunks.renderString(template, { palettes: tokens.color });
      preEl.replaceWith(sectionEl);
    } else {
      // add a class to the `<pre>` tag for easier styling
      // could remove once we're convinced we can rely on pre:has(code)
      el.parentElement.classList.add('code');
      el.setAttribute("tabindex", "0");
    }
  }
}
