---
layout: page-reusable-design.njk
tags: reusableDesign
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Tables
eleventyNavigation:
  key: Tables
  summary: Display matrix style relationships or collections of data using rows and columns.
  parent: Reusable Designs
---

# {{ title }}

Tables organize content in a grid-like structure with rows and columns. They can make it easier to understand complex content.

## When to use tables

Use tables when you need to display a matrix-style relationship,  a collection of data with shared categories, or otherwise structured content. A good example is a user’s checkout history in Account, which contains each item’s title and author, call number, and checkout and return dates.

Only use tables if they’re the best option for displaying the content. Never use them to create layouts.

## Guidelines for tables

* Always set the top row as the header.  
* Use concise, plain language written in sentence case for the column labels in the header row.
* Minimize the number of columns. Reading down is easier than reading across.
* Use the caption to provide extra context about the information within the table as appropriate.

### Relevant WCAG guidelines

* [ARIA Table Pattern](https://www.w3.org/WAI/ARIA/apg/patterns/table/)  
* [1.3.1 Info and Relationships](https://www.w3.org/WAI/WCAG22/Understanding/info-and-relationships.html)  
* [2.4.3 Focus Order](https://www.w3.org/WAI/WCAG22/Understanding/focus-order)  
* [2.4.4 Link Purpose (In Context)](https://www.w3.org/WAI/WCAG22/Understanding/link-purpose-in-context)

## Code example

https://codepen.io/team/umlibrary-designsystem/pen/JjMadNW/ef172f133ff5791d22f1589db1b7b32c
