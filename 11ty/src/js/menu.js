let toggleNav = document.querySelectorAll(".navBtn"); 

  // Set up for multiple nav buttons for Site Search and Hamburger
toggleNav.forEach(function(navBtn) {
   // Buttons are generated on init, to support no JS and display the menu
  navBtn.innerHTML=`<button class="nav" aria-expanded="false" aria-label="Site menu"></button>`; 
  navBtn.nextElementSibling.hidden=true; //hide submenu
  let btn=navBtn.firstElementChild;
  btn.addEventListener("click", function(e) {
    let expanded = this.getAttribute("aria-expanded") === "true" || false;
    this.setAttribute("aria-expanded", !expanded);
    let submenu = this.parentNode.nextElementSibling;
    submenu.hidden = !submenu.hidden;
    e.preventDefault();
    return false;
  });
});

