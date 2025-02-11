---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Color
eleventyNavigation:
  key: Color
  summary: An overview of our color palettes, accessibility guidelines, and examples of our colors in action.
  parent: Visual Elements
  order: 1
---

# {{ title }}

Blue and maize are our primary colors, and teal is frequently used for link text and other interactions.

We also provide a neutral gray and a set of secondary colors: orange, pink, indigo, and green. Keep in mind that for websites, secondary colors should only be used in special cases, such as in a warning state or as an accent color.

Across all colors, 400-level is the primary shade.

See our [full color table](#full-color-table) and [contact the Design System team](/about/our-team/) with questions about appropriate color choices.

## Text color

**Headers** and **body text** are neutral-400.

**Links** are teal-400 and underlined.

Use white text when the text is on a dark background. For example, teal-400 and pink-400 buttons must use white text. View our [Figma Guidelines](https://www.figma.com/@mlibrary) for more examples.

Learn more about how to use these colors as design tokens when [building a user interface](/getting-started/build-user-interfaces/)

## Accessibility

Be sure to use the colors in accessible combinations that meet WCAG 2.1 AA accessibility standards:

* Small and regular text must have a contrast ratio of 4.5:1.
* Large text, as well as graphics and interface controls, must have a contrast ratio of 3:1.

You can use the [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/) and reference [WCAG 2.2 Understanding Success Criterion 1.4.3: Contrast (Minimum)](https://www.w3.org/WAI/WCAG22/Understanding/contrast-minimum.html) to make decisions and ensure the color combinations meet accessibility standards. If you are designing in Figma, you can use the [Color contrast plugin](https://www.figma.com/community/plugin/937465522075454889/Color-contrast) to check your work.

In some situations — like with slide deck templates — it can be helpful to provide a variety of contrasting color options for background and text. This supports accessibility (especially considering eye strain and vision impairments), as well as use in a variety of contexts.

## Full color table

Use [design tokens](/about/web-components-and-design-tokens/#design-tokens) or hex color codes.

```html
<docs-color-block></docs-color-block>
```
