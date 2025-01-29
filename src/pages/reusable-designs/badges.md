---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Badges
eleventyNavigation:
  key: Badges
  summary: Visually display static information about a specific item or within a table.
  parent: Reusable Designs
  order: 0
---

# {{ title }}

Badges display static information about a specific item or within a table. They are visually prominent to draw attention and use color to convey meaning along with text labels.

## When to use badges

Use badges to display numerical values, statuses, achievements, or other similar quantifying or qualifying information.

For selectable elements displaying keywords use [tags](/reusable-designs/tags/). Avoid mixing badges and tags.

## Types and variants

**Types:**

* Text only  
* Text and icon

**Variants:**

* `.badge` is a general status and the default  
* `.badge--success` represents a positive status (available, completed, approved)  
* `.badge--warning` represents a waiting or warning status (in progress, due soon)  
* `.badge--critical` represents a problematic status (error, overdue, etc.)

## Guidelines for using badges

Badges are usually placed immediately after or above the specific item or label of what they’re quantifying or qualifying. If being used relative to the row of a table, badges should appear in their own column.

Use clear, concise, and accurate language in badge labels.

### Relevant WCAG guidelines

* [1.3.1: Info and Relationships](https://www.w3.org/WAI/WCAG22/Understanding/info-and-relationships)  
* [1.3.3: Sensory Characteristics](https://www.w3.org/WAI/WCAG22/Understanding/sensory-characteristics) — especially with dynamic status or numerical values in badges that may change during a user’s session based on user actions or system status.  
* [1.4.1: Use of Color](https://www.w3.org/WAI/WCAG22/Understanding/use-of-color)  
* [2.4.4: Link Purpose (In Context)](https://www.w3.org/WAI/WCAG22/Understanding/link-purpose-in-context)  
* [4.1.3: Status Messages](https://www.w3.org/WAI/WCAG22/Understanding/status-messages)

## Code example

https://codepen.io/team/umlibrary-designsystem/pen/wvbJWOe
