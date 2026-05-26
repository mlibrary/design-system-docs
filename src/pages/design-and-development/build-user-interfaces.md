---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Build User Interfaces
eleventyNavigation:
  key: Build User Interfaces
  summary: Use specifications, copy and paste HTML and CSS, or web components and design tokens.
  parent: Design and Development
  order: 2
---

# {{ title }}

The Design System is flexible so you can use as much or as little as you need to for the interface you’re creating. It is compatible with any framework or no framework at all.

There are four levels of adoption:

- **Design tokens only**: copy/paste CSS custom properties for the DS tokens for color, typography, and spacing
- **Compiled CSS**: design tokens plus opinionated base styles
- **Reusable designs**: copy/paste HTML and CSS components
- **CSS + Web Components**: full component library via CDN or npm

{% callout "alert" %}
We are in the process of releasing new package(s) for the Design System CSS and web components. Please reach out to the [Design System team](/our-team-and-approach/#get-in-touch) if you are building a new library user interface or making updates to an existing interface.
{% endcallout %}

## Design Tokens

View the [design tokens on CodePen](https://codepen.io/team/umlibrary-designsystem/pen/BaEpMGO) and copy/paste into your project's CSS file.

## Compiled CSS

Includes design tokens and opinionated base styles. Use this if you want consistent foundational styles without the web components.

[View umich-lib.css](https://cdn.jsdelivr.net/npm/@umich-lib/web@latest/umich-lib.css)

## Reusable Designs (components)

The [Reusable Designs](/reusable-designs/) are pre-built HTML and CSS components: badges, buttons, input fields, tables, and more. Load the compiled CSS in your project to use them. Then you can browse the list, find the component you need, and copy the code directly into your project.

## Web Components

Includes the compiled CSS plus the web components. There are a few ways to get started.

### Install with CDN via jsDelivr

```html
<link href="https://cdn.jsdelivr.net/npm/@umich-lib/web@latest/umich-lib.css" rel="stylesheet"/>
<script type="module" src="https://cdn.jsdelivr.net/npm/@umich-lib/web@latest/dist/umich-lib/umich-lib.esm.js"></script>
```

### npm

```
npm i @umich-lib/web
```

### Download

[View complied umich-lib.css](https://cdn.jsdelivr.net/npm/@umich-lib/web@latest/umich-lib.css)

[View complied web components umich-lib.esm.js](https://cdn.jsdelivr.net/npm/@umich-lib/web@latest/dist/umich-lib/umich-lib.esm.js)
