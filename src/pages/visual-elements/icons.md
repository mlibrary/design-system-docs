---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Icons
eleventyNavigation:
  key: Icons
  summary: Visual symbols used to represent objects and draw attention to content.
  parent: Visual Elements
  order: 2
---

# {{ title }}

Use icons for decorative purposes or to present important visual information for the viewer. Choose icons that are familiar and easy to recognize.

The U-M Library Design System uses outlined and rounded icons from Material Icons in Google Fonts.

* [Material icons outlined](https://fonts.google.com/icons?icon.style=Outlined&icon.set=Material+Icons)  
* [Material icons rounded](https://fonts.google.com/icons?icon.style=Rounded&icon.set=Material+Icons)

If you aren’t sure which icon to use, can’t find an appropriate one, or have any questions about using them, please [contact our team](/about/get-in-touch/).

## Using icons

We typically set the icon size equal to our base font size (16px). However, icons may be 16 to 24px in size depending on the product.

Icons that present meaningful information must have a descriptive name available to users of assistive technology. Reach out if you need help.

### For designers

Material icons are available in the [U-M Library Design System Figma file](https://www.figma.com/community/file/1198259470207039738) as a plugin.

### For developers

Use the icons as an inline SVG or icon fonts with markup and CSS. Which one you choose needs to be informed by whether the icon is decorative or meaningful.

#### Inline SVG

Download the desired SVG from [Google Fonts](https://fonts.google.com/icons?icon.style=Outlined&icon.set=Material+Icons) and add it to your markup. Can be used for both decorative and meaningful icons.

For accessibility:

* If the icon is decorative, add the following inside the SVG tag: `role=img focusable=false aria-hidden=true`
* If the icon is meaningful, add the `role=img` to the SVG and then nest a title tag.

For more information on using a descriptive title tag, see Deque’s [guidance for creating accessible SVGs](https://www.deque.com/blog/creating-accessible-svgs/).

#### Using Icon Fonts via Google Fonts

Can **only be used for decorative icons**.

1. Add Icon Fonts to the head of the HTML file.
2. Add the icon in your markup. Be sure to use `aria-hidden="true"` as it tells screen readers to ignore the icon. Otherwise, it will announce the content and often it’s not meaningful.`<span aria-hidden=”true” class="material-icons">face</span>`

Adding  `aria-hidden="true"` to the `<span>` tells screen readers to ignore the icon. Otherwise it will announce the content and often it’s not meaningful.

See the [code example below](#icon-code-example) for the exact markup to use to add the icons using Google fonts.

#### Styling the icons

The `.material-design` class assigns the styling to the icons. The base size is 24px and you can override it with `font-size: 1rem`.

## Icon code example

https://codepen.io/team/umlibrary-designsystem/pen/KKZoddB
