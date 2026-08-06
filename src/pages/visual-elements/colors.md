---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"
templateEngineOverride: njk,md

title: Colors
eleventyNavigation:
  key: Colors
  summary: An overview of our color palettes, contrast guidelines, and color tables with copyable hex codes and use cases.
  parent: Visual Elements
  order: 1
---

# {{ title }}

We have 8 color palettes — grouped into primary and secondary — with a range of at least 5 shades in each. You can use our design tokens when [building a user interface](/design-and-development/build-user-interfaces/) or copy the hex color codes anytime\!

Across all colors, 400-level is the primary shade. We note any important considerations for using a color along with the respective table.

## Color contrast

Be sure to use the colors in accessible combinations that meet [WCAG 2.1 AA standards](https://www.w3.org/WAI/WCAG22/Understanding/contrast-minimum.html). For example, you’ll need to use white text on dark backgrounds (generally 400-level or higher).

The minimum contrast ratio for small and regular text is 4.5:1. Large text, graphics, and interface controls must be at least 3:1. We recommend using the [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/) to confirm your choices.

## Primary color tables

Blue, maize, and teal, along with a neutral palette, are our primary colors. Each palette includes our common use cases for certain shades. Uses specific to dark mode (currently only on this website) are noted in parentheses.

{% include "partials/color-table.njk" %}
