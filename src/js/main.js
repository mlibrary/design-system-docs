// Add aria-label to Search

let searchBtn = document.querySelector(".pagefind-ui__search-input");
searchBtn.setAttribute("aria-label", "Search documentation");
searchBtn.setAttribute("placeholder", "Search documentation");

// Adding link for headings
document.addEventListener('DOMContentLoaded', () => {
  // Select headings with IDs
  document.querySelectorAll('h2[id], h3[id], h4[id], h5[id], h6[id]').forEach(heading => {
    // Create the link
    const link = document.createElement('a');
    link.href = `#${heading.id}`;
    link.className = 'heading-anchor';
    link.innerHTML = '#'; 
    link.setAttribute('aria-label', `Link to ${heading.textContent}`);
    
    // Add the link after the heading text
    heading.appendChild(link);
  });
});