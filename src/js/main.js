document.addEventListener('DOMContentLoaded', () => {
  // Show JS-only elements
  document.querySelectorAll('.js-only').forEach(el => {
    el.style.display = '';
  });

  // Add aria-label to Search
  let searchBtn = document.querySelector(".pagefind-ui__search-input");
  searchBtn.setAttribute("aria-label", "Search documentation");
  searchBtn.setAttribute("placeholder", "Search documentation");

  // Adding link for headings
  document.querySelectorAll('h2[id], h3[id], h4[id], h5[id], h6[id]').forEach(heading => {
    const link = document.createElement('a');
    link.href = `#${heading.id}`;
    link.className = 'heading-anchor';
    link.innerHTML = '#';
    link.setAttribute('aria-label', `Link to ${heading.textContent}`);
    heading.appendChild(link);
  });

  // Theme toggle
  const themeToggle = document.getElementById('theme-toggle');

  const themeAnnouncement = document.getElementById('theme-toggle-announcement');

  if (themeToggle) {
    function applyTheme(isDark, announce = false) {
      document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
      themeToggle.setAttribute('aria-checked', String(isDark));
      if (announce && themeAnnouncement) {
        themeAnnouncement.textContent = isDark ? 'Dark mode on' : 'Dark mode off';
      }
    }

    // Set initial state: saved preference, or fall back to browser preference
    const saved = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    applyTheme(saved === 'dark' || (saved === null && prefersDark));

    // Keep toggle in sync if OS preference changes and no saved preference
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
      if (!localStorage.getItem('theme')) applyTheme(e.matches, true);
    });

    themeToggle.addEventListener('click', () => {
      const isDark = themeToggle.getAttribute('aria-checked') === 'true';
      applyTheme(!isDark, true);
    });
  }

  // Copy hex code buttons
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.copy-btn');
    if (!btn) return;

    const value = btn.getAttribute('data-copy-value');
    const status = document.getElementById('copy-status');

    navigator.clipboard.writeText(value).then(() => {
      btn.classList.add('copied');
      btn.textContent = 'check';
      if (status) status.textContent = `Copied ${value} to clipboard`;

      setTimeout(() => {
        btn.classList.remove('copied');
        btn.textContent = 'content_copy';
      }, 1500);
    }).catch(() => {
      if (status) status.textContent = `Unable to copy ${value}`;
    });
  });
});
