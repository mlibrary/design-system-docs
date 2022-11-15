module.exports = function(el) {
  if (el.nodeType == 3) {
   let value = el.nodeValue.trim();
    if (value.indexOf('`') > -1) {
      let oldValue = value;
      value = value.replace(/`([^`]+)`/g, "<code>$1</code>");
      el.parentElement.innerHTML = value;
      if (process.env.DEBUG) {
        console.log("-- backticks", oldValue, "->", value);
      }
    }
  }
}