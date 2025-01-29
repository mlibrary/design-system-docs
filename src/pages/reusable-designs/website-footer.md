---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Website Footer
eleventyNavigation:
  key: Website Footer
  summary: Display contact information and helpful links at the bottom of a website.
  parent: Reusable Designs
  order: 0
---

# {{ title }}

The website footer displays a column of information about the U-M Library as well as one or two lists of helpful links. The very bottom of the footer features a single row — or subfooter — in blue-500 with copyright information.

Content featured in the footer changes across products, but stays consistent throughout a specific website.

## When to use website footer

Use this footer as a starting point for any website within the domain of lib.umich.edu and customize the links of links for your needs.

## Guidelines for website footer

**Contact information** should be in the first column of the footer and linked accordingly. The U-M Library address is provided by default and links to Google Maps. If including email or phone, the latter should be selectable to dial from a mobile phone.

The footer provides one or two columns with a header over each for **additional content**. Aim for a balanced number of items if using two columns. When selecting items:

* Point to content addressing popular use cases, or that might answer visitor’s remaining questions as they reach the bottom of the site.
* Steer away from mirroring groupings in top level header navigation.  
* If you need to include disclaimers and legal content, try to keep it as minimal as possible.

If using **icons** along with list items, they should be all-or-nothing in each column. See more guidance around [icons as a visual element](/visual-elements/icons/).

### Subfooter

Include copyright information: year and Regents of the University of Michigan. We recommend updating the provided code example to dynamically change the copyright year based on the tech stack of your site (PHP, JS, Rails) so it doesn’t go out of date.

You can also optionally use this space to call out open source solutions used to build the site (for example: Built with [Drupal](https://www.drupal.org/) and [the U-M Library Design System](https://design-system.lib.umich.edu/)). This is also a good spot for linking to release notes or similar.

### Relevant WCAG guidelines

* [2.1 Keyboard](https://www.w3.org/WAI/WCAG22/Understanding/keyboard-accessible.html)  
* [2.4.3 Focus Order](https://www.w3.org/WAI/WCAG22/Understanding/focus-order)  
* [2.4.4 Link Purpose (In Context)](https://www.w3.org/WAI/WCAG22/Understanding/link-purpose-in-context) (In Context)  
* [2.4.6 Headings and Labels](https://www.w3.org/WAI/WCAG22/Understanding/headings-and-labels)  
* [2.4.7 Focus Visible](https://www.w3.org/WAI/WCAG22/Understanding/focus-visible)

## Code Example

https://codepen.io/team/umlibrary-designsystem/pen/dyjVeMQ
