---
layout: page-reusable-design.njk
tags: reusableDesign
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Select
eleventyNavigation:
  key: Select
  summary: An input control for longer lists of options or form submissions.
  parent: Reusable Designs
---

# {{ title }}

Select uses the browser or operating system default to display a dropdown menu of options.

## When to use Select

Use select for dropdowns that are part of form submissions, such as modifying a search or submitting a request.

Select can also be used instead of a dropdown to allow users to select a single item from a list with at least 4 options.

### Other input options

To allow **multi-select** from a list of options, use [checkboxes](/reusable-designs/checkbox/). For **shorter lists of single-select options**, use [radio buttons](/reusable-designs/radio-button/) to display them all on the page.

If you want to be able to **use custom styles, or present options to modify content on a page** (such as sorting), use a dropdown.

## Select elements

* **Label**: clearly explains the content of the menu  
* **Menu**: contains the list of all selectable items

## Guidelines for providing options

Avoid overwhelming people with too many choices in the **list of options**. They should be presented in a logical order — alphabetical, numerical, chronological, most commonly selected first, or something else clear.

For **option labels**, use clear and concise language written in sentence case.

Be sure to include input validation as appropriate.

### Relevant WCAG guidelines

* [1.4.11 Non-text contrast](https://www.w3.org/WAI/WCAG21/Understanding/non-text-contrast)  
* [2.1.1 Keyboard](https://www.w3.org/WAI/WCAG21/Understanding/keyboard.html)  
* [2.4.7 Focus visible](https://www.w3.org/WAI/WCAG21/Understanding/focus-visible)  
* [4.1.2 Name, role, value](https://www.w3.org/WAI/WCAG21/Understanding/name-role-value)

## Code example

https://codepen.io/team/umlibrary-designsystem/pen/gOBLOWP
