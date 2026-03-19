const toggle = document.querySelector('.side-nav-toggle');
const pageAside = document.querySelector('.page-aside');

if (toggle && pageAside) {
  toggle.addEventListener('click', () => {
    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
    
    toggle.setAttribute('aria-expanded', !isExpanded);
    pageAside.classList.toggle('show');
  });
}