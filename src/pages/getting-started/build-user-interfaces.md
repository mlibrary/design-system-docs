---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Build User Interfaces
eleventyNavigation:
  key: Build User Interfaces
  summary: Use specifications, copy and paste HTML and CSS, or web components and design tokens.
  parent: Getting Started
  order: 4
---

# {{ title }}

The Design System is flexible so you can use as much or as little as you need to for the interface you’re creating. It is compatible with any framework or no framework at all.

The primary uses when building an interface are:

* Applying colors, fonts, spacing, and other [visual elements](/visual-elements/).  
* Adding ready-to-use [reusable designs](/reusable-designs/) to your interfaces

## Options for use

Choose the most appropriate option for what you’re working on to use our visual elements and reusable designs.

Options include:

* Using available specifications (such as hex colors or spacing) in your code without design tokens  
* Copying and pasting the HTML and CSS available for all of our [reusable designs](/reusable-designs/)
* Using [web components and design tokens](/about/web-components-and-design-tokens/) when they’re available

In addition, we encourage everyone to follow the guidelines provided for visual elements and reusable designs to inform your own implementation.

### Use web components and design tokens

In order to use web components and design tokens, you’ll need to link to our stylesheet and two script tags to the \<head\>.

```html
<link href="https://cdn.jsdelivr.net/npm/@umich-lib/web@latest/umich-lib.css" rel="stylesheet"/>
<script type="module" src="https://cdn.jsdelivr.net/npm/@umich-lib/web@latest/dist/umich-lib/umich-lib.esm.js"></script>

```

Our overview of [web components and design tokens](/about/web-components-and-design-tokens/) will help you get up to speed if these concepts are new to you.
