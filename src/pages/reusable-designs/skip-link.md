---
layout: page-reusable-design.njk
tags: reusableDesign
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Skip Link
eleventyNavigation:
  key: Skip Link
  summary: Visually hidden links that allow quick navigation to main content via keyboard focus.
  parent: Reusable Designs
---

# {{ title }}

Skip links allow for quick navigation to main content by letting the user navigate past repetitive headers or menus. They are visually hidden and only appear on keyboard focus.

## Always provide skip links

WCAG guidelines require a method to bypass blocks of repetitive content. Skip links must be available on every page in a site.

## Guidelines for skip links

Skip links should be hidden until the user presses TAB, which exposes them at the top of the page.

Once accessed, skip links should have a clear and consistent focus state.

### Relevant WCAG guidelines

* [2.4.1 Bypass Blocks](https://www.w3.org/WAI/WCAG21/Understanding/bypass-blocks)  
* [2.4.4 Link Purpose (In Context)](https://www.w3.org/WAI/WCAG22/Understanding/link-purpose-in-context)  
* [2.4.7 Focus Visible](https://www.w3.org/WAI/WCAG22/Understanding/focus-visible)

## Code example

https://codepen.io/team/umlibrary-designsystem/pen/gOogXeG/592d4b2b60146842dd013a85286f57df
