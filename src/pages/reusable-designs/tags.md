---
layout: page-reusable-design.njk
tags: reusableDesign
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Tags
eleventyNavigation:
  key: Tags
  summary: Allow navigation or filtering by selecting keyword-based categories.
  parent: Reusable Designs
---

# {{ title }}

Tags are selectable elements displaying keywords that categorize or classify content.

## When to use tags

Use tags for displaying keywords representing categories that users can select as options, as well as to navigate through content or filter results.

Do not use tags as links that direct you to an entirely different page or launch you outside the current website or application.

For non-interactive labels to communicate numerical values, statuses, achievements, or other similar quantifying or qualifying information, use [badges](/reusable-designs/badges/).

## Tag types and states

**Types:**

* Text only  
* Text and icon  
* Dismissible

**States:**

* Default  
* Hover  
* Selected
* Disabled

## Guidelines for using tags

* Keep tag labels concise. We recommend labels with a maximum of 50 characters and ideally, less than 25\.  
* Use tags in moderation to not overwhelm users with choices.
* Be cautious if tags might appear in the same area of the page as buttons and cause confusion. Similarly, avoid mixing tags and badges.  
* Use horizontal alignment when there are only a few tags. If tags run past a single line, they should wrap and form another line.

### Relevant WCAG guidelines

* [1.3.1: Info and Relationships](https://www.w3.org/WAI/WCAG22/Understanding/info-and-relationships)  
* [1.3.3: Sensory Characteristics](https://www.w3.org/WAI/WCAG22/Understanding/sensory-characteristics)
* [1.4.1: Use of Color](https://www.w3.org/WAI/WCAG22/Understanding/use-of-color)  
* [2.1.1: Keyboard](https://www.w3.org/WAI/WCAG22/Understanding/keyboard) — especially when tag is a link  
* [2.4.4: Link Purpose (In Context)](https://www.w3.org/WAI/WCAG22/Understanding/link-purpose-in-context)  
* [4.1.3: Status Messages](https://www.w3.org/WAI/WCAG22/Understanding/status-messages)

## Code example

https://codepen.io/team/umlibrary-designsystem/pen/eYavzXQ
