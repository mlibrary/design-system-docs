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

## Choosing icons

While U-M Library web products use different icon packages, the U-M Library Design System recommends using [Material Symbols](https://fonts.google.com/icons?icon.set=Material+Symbols&icon.style=Rounded).

{% callout "info" %}
While Material Symbols and Material Icons look quite similar, they are different. When selecting icons from the Google Fonts website, make sure the dropdown labeled “Style” is set to “Material Symbols (new)” and "Rounded".  
{% endcallout %}

For design work, you can use the [Material Symbols plugin](https://www.figma.com/community/plugin/1088610476491668236/material-symbols) in Figma or download the SVG files from the [Google Fonts site](https://fonts.google.com/icons?icon.set=Material+Symbols&icon.style=Rounded).

If you aren’t sure which icon to use, can’t find an appropriate one, or have any questions about using them, please [contact the Design System team](/about/our-team/).

### Style settings

We use the following default style settings for Material Symbols:

* Fill: No fill  
* Weight: 400  
* Grade: 0  
* Optical size: 24  
* Style: Material Symbols (new), Rounded

If your product is using Font Awesome instead of Material Symbols, we recommend using the following settings to most closely match:

* Icon Pack: Classic  
* Style: Regular  
* Icon Type: Round

We typically set the icon size to 16px in designs and code, but you may use anywhere from 16 to 24px depending on the product.

## For developers

There are a few different ways we use icons in development: as an inline SVG or via Google Fonts using either static or variable icon fonts.

Which one you choose needs to be informed by whether the icon is decorative or meaningful. Icons that present meaningful information **must** have a descriptive name available to users of assistive technology.

We provide an overview of each option below. See the codepen examples at the bottom of the page for details about markup and ensuring accessibility. Please reach out if you need help.

### Inline SVG

SVGs can be used for **both decorative and meaningful icons**. For extra information on accessibility, see Deque’s [guidance for creating accessible SVGs](https://www.deque.com/blog/creating-accessible-svgs/).

To use: download the desired SVG from [Material Symbols](https://fonts.google.com/icons?icon.set=Material+Symbols&icon.style=Rounded) and add it to your markup.

### Google Fonts icon fonts

Icon fonts can **only be used for decorative icons**. Whether using static or variant fonts, be sure to include `aria-hidden="true"` in your markup. This ensures screen readers don’t announce content that is often not meaningful.

{% callout "info" %}
**Important**: For performance, we recommend only adding the icons/symbols you are using in your application!  
{% endcallout %}

The easiest way to add Material Symbols is with **static fonts**. If you're animating icons via CSS, or want finer control over icon features, use the Google Symbols **variable font**.

For both, you’ll need to link the relevant stylesheet in the `<head>` of your document and then add the icon in your markup.

#### **Styling icon fonts**

Use the `material-symbols-rounded` class for styling icons. The base size is 24px and you can override it with `font-size: 1rem`.

Use CSS font-variation settings to control variable font characteristics.

The [code example below](#code-example) has more details.

### Code example

https://codepen.io/team/umlibrary-designsystem/pen/KKZoddB
