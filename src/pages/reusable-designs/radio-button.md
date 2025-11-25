---
layout: page-reusable-design.njk
tags: reusableDesign
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Radio Button
eleventyNavigation:
  key: Radio Button
  summary: An input control for short lists of options that display directly on the page and allow a single choice.
  parent: Reusable Designs
---

# {{ title }}

Radio buttons display a list of actions or options and allow selecting one item. All of the options display directly on the page, so radio buttons are best for shorter lists.

## When to use radio buttons

Use radio buttons when a user must make a single selection from a list of two or more options. A 5-7 option maximum is recommended.

Radio buttons may also be appropriate for choices such as opting in or out of something or modifying search parameters (for example: toggling between boolean operators).

### Other input options

To allow **multi-select from a list of options**, use [checkboxes](/reusable-designs/checkbox/).

For **longer lists of options**, use a dropdown or [select](/reusable-designs/select/). The best choice will depend on context and whether you want to apply custom styles or not.

## Guidelines for use of radio buttons

Radio buttons should always offer a **default selection**. Select the safest or most private option. If safety and security aren’t factors, select the most likely option.

Make sure that the **list of options** is comprehensive and each item is distinct. If it’s impossible to be comprehensive, add an “Other” option. List the items in a logical order — alphabetical, numerical, chronological, most commonly selected first, or something else clear.

**Lay out lists** **vertically**, with one choice per line. If limited space requires you to lay out the options horizontally, be sure to space the options so that it’s abundantly clear which button is paired with which label.

For **option labels**, use clear and concise language written in sentence case.

Be sure to include **input validation** as appropriate.

### Relevant WCAG guidelines

* [ARIA Radio Group Pattern](https://www.w3.org/WAI/ARIA/apg/patterns/radio/)  
* [1.3.1 Info and relationships](https://www.w3.org/WAI/WCAG21/Understanding/info-and-relationships)  
* [1.4.1 Use of color](https://www.w3.org/WAI/WCAG21/Understanding/use-of-color)  
* [2.1.1 Keyboard](https://www.w3.org/WAI/WCAG21/Understanding/keyboard)

## Code example

https://codepen.io/team/umlibrary-designsystem/pen/ZEqBEOv
