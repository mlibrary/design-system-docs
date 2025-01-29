---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Web Components and Design Tokens
eleventyNavigation:
  key: Web Components and Design Tokens
  summary: An introduction to the evolving web platform technologies we use.
  parent: About
  order: 2
---

# {{ title }}

In addition to copy-and-paste HTML and CSS, our team is building out support for web components and design tokens, which offer increased sustainability and scalability.

## Web components

Web components are evolving web platform technologies. They allow us to create custom, reusable HTML elements that use standard web technologies (HTML, CSS, and JavaScript). The end result is a custom element that you can drop into your websites and applications with no additional coding or styling required.

We use the term “web component” for both the platform and the custom element we’ve created. The platform and browser support for web components is still actively evolving so our team thoughtfully chooses what should and shouldn’t be a web component.

The [Universal Header](/resuable-designs/universal-header/) and [Chat](/resuable-designs/chat/) are two of our reusable designs available as web components, for example.

## Design tokens

Design tokens are stored values for the visual elements that make up our design system such as colors, fonts, and spacing. Instead of assigning a hard coded value to a reusable design, we use design tokens for our themed values. These help us ensure our designs are consistent and can change as needs arise.

**For example:** Design token `--color-teal-400` outputs to `#1d7491`

```css
a {  
color: var(--color-teal-400);  
}
```

## Ready to get started?

Add the link to our stylesheet and two script tags to the `<head>` of whatever you're working on. You’ll then be able to use the web components and design tokens provided throughout the Design System.

```html
<link href="https://cdn.jsdelivr.net/npm/@umich-lib/web@latest/umich-lib.css" rel="stylesheet"/>
<script type="module" src="https://cdn.jsdelivr.net/npm/@umich-lib/web@latest/dist/umich-lib/umich-lib.esm.js"></script>
```
