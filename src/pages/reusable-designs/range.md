---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Range
eleventyNavigation:
  key: Range
  summary: An input control for selecting a numeric value from a given range using a slider.
  parent: Reusable Designs
  order: 0
---

# {{ title }}

Range allows users to select a numeric value within a given range of minimum and maximum values.

Our range input uses a slider that provides a visual indication of adjustable content. The user can increase or decrease the value by moving the handle along a horizontal track.

## When to use range

Use a range when you need to provide a span of options and let users set a selection.

They’re ideal for adjusting settings (such as volume) where relative value is more important, as well as scenarios where users should be able to set a minimum and maximum. Range is typically used for inputs where changes made are reflected immediately, allowing users to see the effect of their adjustments.

Range doesn’t work well if choosing a specific value is important since the interaction isn’t as precise. Do not use range for extremely large or too small ranges, or for anything that cannot be quantified numerically.

See [input fields](/reusable-designs/input-fields/) for a broader overview of input controls.

## Range attributes

* **Min** is the lowest value in the range of permitted values.  
* **Max** is the greatest value in the range of permitted values.  
* **Step** specifies the granularity that a selected value must adhere to. The default is 1\.
* **Value** is the selected number, which defaults to halfway between the specified minimum and maximum.

## Guidelines for using range

* Place labels directly above the input, and align to the left. Keep labels clear and concise.
* Include helper text to clarify how to use the range if necessary.  
* Place the lowest value on the left for horizontal ranges and at the top for vertical.

### Relevant WCAG guidelines

* [SC 1.3.1 Info and Relationships](https://www.w3.org/WAI/WCAG22/Understanding/info-and-relationships)  
* [SC 1.4.11 Non-text contrast](https://www.w3.org/WAI/WCAG22/Understanding/non-text-contrast)  
* [SC 2.1.1 Keyboard](https://www.w3.org/WAI/WCAG22/Understanding/keyboard)  
* [SC 2.4.7 Focus Visible](https://www.w3.org/WAI/WCAG22/Understanding/focus-visible)  
* [SC 4.1.2 Name, Role, Value](https://www.w3.org/WAI/WCAG22/Understanding/name-role-value)

## Code example

https://codepen.io/team/umlibrary-designsystem/pen/xxNxBVy
