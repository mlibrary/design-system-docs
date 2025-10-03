---
layout: page-reusable-design.njk
tags: reusableDesign
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Link
eleventyNavigation:
  key: Link
  summary: Indicate that there is more information elsewhere that users can navigate to. 
  parent: Reusable Designs
---

# {{ title }}

Links let users know there is more information, another page, or a different website they can navigate to. We make the ability to select a link clear through style choices and use link labels that indicate the destination it will take you to.

The styles and guidelines provided here apply to links in body text and generally within the content of a site (such as [breadcrumbs](/reusable-designs/breadcrumbs/)). While the overall principles still apply, styles in places like the main site menu and local navigation may vary.

## When to use a link

Use links for standard navigation from one page or website to another.

If you need to communicate actions a user can take (such as download, save, or submit), [use a button](/reusable-designs/buttons/) instead.  

## Link styles

Our link styles reflect long-standing conventions and an attention to accessibility.

Links within body text are `teal-400` and underlined by default. Underlining is the expected indicator of a link and color is used to help the links stand out (but should never be used alone).

On hover, a text-decoration of 2px thickness appears for the underline. The focus state adds a maize box around the link text. See the code example at the bottom of the page for details.

In addition, we offer a visited link color in Matthaei Violet (`#575294`). This is from U-M’s [secondary color palette](https://brand.umich.edu/design-resources/colors/), but is not included in our color tokens.

## Guidelines for links

Your link text should be descriptive, meaningful and clearly indicate the destination of the link. See [link text with our grammar and style](/content/grammar-and-style/#link-text-hyperlinks) guidance for details.  

By default, links should open in the same tab. This gives all users the ability to decide if they want to stick with the default or manually open the link in a new tab or window. Opening in the same tab also maintains the browser's back button functionality, provides a consistent overall experience, and supports mobile users who may find multiple tabs harder to navigate.

### Deviation from link opening default

There are some scenarios where it can make sense to default to opening a link in a new tab or window instead of the same one. One example is a link to help documentation where opening in the same tab would break a workflow or cause the user to lose work (such as a populated form fields).

If you determine opening in a new window or tab is appropriate, you must either identify that the link opens in a new window as part of link text **or** include "Opens in a new window" as visually hidden text and use an icon to indicate the new window. Our code example illustrates both options.

### Relevant WCAG guidelines

* [1.4.1 Use of Color](https://w3.org/TR/WCAG22#use-of-color)  
* [1.4.3 Contrast (Minimum)](https://www.w3.org/TR/WCAG22/#contrast-minimum)
* [2.4.4: Link Purpose (In Context)](https://www.w3.org/WAI/WCAG21/Understanding/link-purpose-in-context.html)  
* [2.4.7 Focus Visible](https://www.w3.org/WAI/WCAG21/Understanding/focus-visible.html)  
* [3.2.4 Consistent Identification](https://www.w3.org/TR/WCAG22/#consistent-identification)

## Code example

https://codepen.io/team/umlibrary-designsystem/pen/XJWyjKQ
