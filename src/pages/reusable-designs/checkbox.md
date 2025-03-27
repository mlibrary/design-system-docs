---
layout: page-reusable-design.njk
tags: reusableDesign
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Checkbox
eleventyNavigation:
  key: Checkbox
  summary: An input control for choosing multiple options from a list or toggle between two options.
  parent: Reusable Designs
---

# {{ title }}

Checkboxes allow people to choose multiple options from a list or toggle between two options.

## When to use checkboxes

Use checkboxes when someone can select one or more items from a list of related options.

A single checkbox is appropriate for allowing users to acknowledge acceptance of something (like accepting terms of services) or switch between two opposite states (such as, unchecked \= “no” and checked \= “yes).

### Other input options

For **a single selection** from a list of options, use [radio buttons](/reusable-designs/radio-button/) or a dropdown.

## Checkbox states

* **Unselected** indicates that the option has not been chosen.  
* **Selected** indicates that the option has been chosen.  
  **Indeterminate** occurs when a parent checkbox contains a list of child checkboxes, some of which are selected, and some of which are unselected.  
* **Disabled** should only be used in rare cases where an option is temporarily unavailable.

## Guidelines for using checkboxes

Options presented with checkboxes for selection should begin with a **question or prompt** that provides context for the options below.

List the **options in a logical order** — alphabetical, numerical, chronological, most commonly selected first, or something else clear. Lay out lists vertically, with one choice per line.

For the **option labels**, use positive and active wording. For example: “Send me emails” rather than “Don’t send me emails.” Users should not be asked to check a box in order to make something ***not*** happen.

Checkboxes should be **unselected** by default and function independent of each other — selecting one should not change the status of others in the list. The only exception is if a parent checkbox is used to make a bulk selection of child checkboxes.

Be sure to include **input validation** as appropriate.

### Relevant WCAG guidelines

* [ARIA Checkbox Pattern](https://www.w3.org/WAI/ARIA/apg/patterns/checkbox/)  
* [2.4.3 Focus Order](https://www.w3.org/WAI/WCAG22/Understanding/focus-order)  
* [2.4.6 Headings and Labels](https://www.w3.org/WAI/WCAG22/Understanding/headings-and-labels)  
* [2.4.7 Focus Visible](https://www.w3.org/WAI/WCAG22/Understanding/focus-visible)

## Code example

https://codepen.io/team/umlibrary-designsystem/pen/rNqWNWv
