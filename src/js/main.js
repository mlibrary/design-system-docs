// Add aria-label to Search
let searchBtn = document.querySelector(".pagefind-ui__search-input");
searchBtn.setAttribute("aria-label", "Search documentation");
searchBtn.setAttribute("placeholder", "Search documentation");

// Adding link for headings
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('h2[id], h3[id], h4[id], h5[id], h6[id]').forEach(heading => {
    const link = document.createElement('a');
    link.href = `#${heading.id}`;
    link.className = 'heading-anchor';
    link.innerHTML = '#';
    link.setAttribute('aria-label', `Link to ${heading.textContent}`);
    heading.appendChild(link);
  });

  // Theme select
  const themeSelect = document.getElementById('theme-select');

  if (themeSelect) {
    const selectWrap = themeSelect.closest('.select-wrap'); // ← no extra id needed

    function applyTheme(value) {
      if (value === 'system') {
        document.documentElement.removeAttribute('data-theme');
        localStorage.removeItem('theme');
      } else {
        document.documentElement.setAttribute('data-theme', value);
        localStorage.setItem('theme', value);
      }
    }

    function updateSelectIcon(value) {
      if (selectWrap) selectWrap.setAttribute('data-theme-value', value);
    }

    const saved = localStorage.getItem('theme');
    themeSelect.value = (saved === 'dark' || saved === 'light') ? saved : 'system';
    updateSelectIcon(themeSelect.value);

    themeSelect.addEventListener('change', () => {
      applyTheme(themeSelect.value);
      updateSelectIcon(themeSelect.value);
    });

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
      if (!localStorage.getItem('theme')) {
        themeSelect.value = 'system';
        updateSelectIcon('system');
      }
    });
  }
});