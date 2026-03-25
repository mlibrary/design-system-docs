---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Build User Interfaces
eleventyNavigation:
  key: Build User Interfaces
  summary: Use specifications, copy and paste HTML and CSS, or web components and design tokens.
  parent: Develop
  order: 1
---

# {{ title }}

The Design System is flexible so you can use as much or as little as you need to for the interface you’re creating. It is compatible with any framework or no framework at all.

There are three levels of adoption:

- **Design tokens only** — copy/paste CSS custom properties
- **Compiled CSS** — design tokens plus opinionated base styles
- **CSS + Web Components** — full component library via CDN or npm

{% callout "alert" %}
We are in the process of releasing a new npm package for the Design System CSS and web components.
{% endcallout %}

## Design Tokens

View the [design tokens on CodePen](https://codepen.io/team/umlibrary-designsystem/pen/BaEpMGO) and copy/paste into your project.

## Compiled CSS

Includes design tokens and opinionated base styles. Use this if you want consistent foundational styles without the web components.

<a href="https://cdn.jsdelivr.net/npm/@umich-lib/web@latest/umich-lib.css" download>
  Download CSS (v1.1.1)
</a>

## Web Components

Includes the compiled CSS plus the web components. There are a few ways to get started.

## Install with CDN via jsDelivr

```html
<link href="https://cdn.jsdelivr.net/npm/@umich-lib/web@latest/umich-lib.css" rel="stylesheet"/>
<script type="module" src="https://cdn.jsdelivr.net/npm/@umich-lib/web@latest/dist/umich-lib/umich-lib.esm.js"></script>
```

## npm

```
npm i @umich-lib/web
```

### Download

<a href="https://cdn.jsdelivr.net/npm/@umich-lib/web@latest/umich-lib.css" download>Download umich-lib.css </a>

<a href="https://cdn.jsdelivr.net/npm/@umich-lib/web@latest/dist/umich-lib/umich-lib.esm.js" download>Download web components umich-lib.esm.js</a>
