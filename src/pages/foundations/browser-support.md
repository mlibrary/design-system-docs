---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Browser Support
eleventyNavigation:
  key: Browser Support
  summary: The browsers we test in and what testing includes. 
  parent: Foundations
  order: 4
---

# {{ title }}

We thoroughly test our designs in:

* Chrome  
* Firefox  
* Safari  
* Edge

The types of testing we complete vary depending on what we’re working on, but generally include:

* Confirming compatibility with the latest two browser versions — the current stable version and the one prior.  
* Testing the most common screen sizes for desktop, tablet, and mobile devices.

While reusable designs may not look exactly the same in every browser, functionality should be consistent.

If you have an issue or find a bug, please contact us at <library-design-system-team@umich.edu>.
