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

Use icons for decorative purposes or to present important visual information for the viewer. You want to choose icons that are familiar and easy to recognize.

The U-M Library Design System uses **outlined** and **rounded** icons from Material Icons in Google Fonts.

* [Material icons outlined](https://fonts.google.com/icons?icon.style=Outlined&icon.set=Material+Icons)  
* [Material icons rounded](https://fonts.google.com/icons?icon.style=Rounded&icon.set=Material+Icons)

If you aren’t sure which icon to use, can’t find an appropriate one, or have any questions about using them, please contact [our team](/about/get-in-touch/).

## Using icons

We typically set the icon size equal to our base font size (16px). However, icons may be 16 to 24px in size depending on the product.

Icons that present meaningful information must have a descriptive name available to users of assistive technology. Please reach out if you need help.

### For designers

Material icons are available in the [U-M Library Design System Figma file](https://www.figma.com/community/file/1198259470207039738) as a component. They default to 16px but can be resized up to 24px to meet the needs of your individual project.

### For developers

Use the icons as an inline SVG or icon fonts with markup and CSS. Which one you choose needs to be informed by whether the icon is decorative or meaningful.

#### Inline SVG

Download the desired SVG from [Google Fonts](https://fonts.google.com/icons?icon.style=Outlined&icon.set=Material+Icons) and add it to your markup. Can be used for **both decorative and meaningful icons**.

For accessibility:

* If the icon is **decorative**, add `role=img focusable=false aria-hidden=true\` to the `<svg>`  
* If the icon is **meaningful**, add \`role=img\` and a \`\<title\>Title\</title\>\` nested within the `<svg>`

For more information, see Deque’s [guidance for creating accessible SVGs](https://www.deque.com/blog/creating-accessible-svgs/).

#### Using Icon Fonts via Google Fonts

Can **only be used for decorative icons**.

1. Add Icon Fonts to the `<svg>` with `<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">`  
2. Add the icon in your markup: `<span aria-hidden=”true” class="material-icons">face</span>`

Adding  `aria-hidden="true"` to the `<span>` tells screen readers to ignore the icon. Otherwise it will announce the content and often it’s not meaningful.

See the [code example below](#icon-code-example) for the exact markup to use to add the icons using Google fonts.

#### Styling the icons

The `.material-design` class assigns the styling to the icons. The base size is 24px and you can override it with `font-size: 1rem`.

### Icon code example

https://codepen.io/team/umlibrary-designsystem/pen/KKZoddB
