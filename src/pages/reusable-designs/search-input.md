---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Search Input
eleventyNavigation:
  key: Search Input
  summary: Allow users to search content of a site through a form submit.
  parent: Reusable Designs
  order: 0
---

# {{ title }}

Search allows users to search the content of a site or subset of a site. Users enter a query and then see related results.

## When to use search

Search can be used for an entire site, at the page level, or within an element (such as a data table).

It is a **form submit** and requires the user to select submit to reload the page with search results.

We also use search boxes that show filtered results in a container immediately without the user submitting. These are more complex. Contact us if you’d like to use one and need support.

Do not use search for small amounts of content or simple information.

See [input fields](/reusable-designs/input-fields/) for a broader overview of input controls.

## Guidelines for using search

Place search where users expect to find it and where it is most appropriate relative to the context.

We built a number of usability and accessibility best practices in and it:

* Displays hinted search text (“Search this site” by default) that is replaced with the input text.  
* Features a magnifying glass icon, which is recognizable as associated with search functionality.  
* Employs the search button as a submit button, which reduces the number of keystrokes required. *Form submit version only.*

### Relevant WCAG guidelines

* [SC 1.3.1 Info and Relationships](https://www.w3.org/WAI/WCAG22/Understanding/info-and-relationships)  
* [SC 1.4.11 Non-text contrast](https://www.w3.org/WAI/WCAG22/Understanding/non-text-contrast)  
* [SC 2.1.1 Keyboard](https://www.w3.org/WAI/WCAG22/Understanding/keyboard)  
* [SC 2.4.7 Focus Visible](https://www.w3.org/WAI/WCAG22/Understanding/focus-visible)  
* [SC 4.1.2 Name, Role, Value](https://www.w3.org/WAI/WCAG22/Understanding/name-role-value)

## Code example

https://codepen.io/team/umlibrary-designsystem/pen/PorPmvP
