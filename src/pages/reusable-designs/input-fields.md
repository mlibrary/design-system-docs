---
layout: page-reusable-design.njk
tags: reusableDesign
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Input Fields
eleventyNavigation:
  key: Input Fields
  summary: Input controls for interactive fields (text, numerical, date, time, phone number) commonly used in forms.
  parent: Reusable Designs
---

# {{ title }}

Inputs are interactive fields that accept responses from users, commonly as part of forms. They accept many formats, but text or text area, numerical, telephone number, date, and time are most common.

These inputs are essentially the same in appearance, but the HTML syntax and formatting requirements are each slightly different.  

Beyond fields, we have reusable designs for the following additional input controls: [checkbox](/reusable-designs/checkbox/), [file upload](/reusable-designs/file-upload/), [radio button](/reusable-designs/radio-button/), [range](/reusable-designs/range/), [select](/reusable-designs/select/), and [search](/reusable-designs/search/).

## Field types and uses

| Type | HTML syntax | Use to allow people to |
| :---- | :---- | :---- |
| Text | text | Enter, select, or search for text. |
| Text area | textarea | Enter longer form text that will span over multiple lines.  |
| Numerical  | number | Enter numbers. |
| Email address | email | Enter an email address. |
| Date | date | Enter or select a date. Uses browser default display. |
| Time | time | Enter or select a time. Uses browser default display. |
| Phone number | tel | Enter a phone number. |

## Input field elements

* **Label:** indicate what information the field requires and display left-aligned, directly above the input field.
* **Placeholder text:** avoid using unless it’s a search field positioned directly beside a search button.  
* **Helper text:** provide context that helps the user complete a field. It is always available and appears underneath the field.  
* **Input area:** this is where users enter the information.

## Helper text and placeholders

Avoid using **placeholder text** whenever possible. Communicate any critical information in the field label or using helper text below the field. Never use placeholder text in lieu of a label to save space as it hides context and presents accessibility issues.

Use **helper text** to provide an example, specific syntax for the input, character count, or critical information for completing the field. Try not to overuse it.

## Additional guidelines for input fields

The **input field width** should be just slightly wider than the expected input. This provides users with a contextual clue for how long their input should be, and in the case of text, prevents it from being wrapped or hidden too soon.

Effective **labels** help users understand what to enter into a text field. Keep labels short and clear — aim for 1–5 words — written in sentence case. Do not include colons at the end of labels.

Be sure to include **input validation** as appropriate.

### Relevant WCAG guidelines

* [SC 1.3.1 Info and Relationships](https://www.w3.org/WAI/WCAG22/Understanding/info-and-relationships)  
* [SC 2.4.3 Focus Order](https://www.w3.org/WAI/WCAG22/Understanding/focus-order)  
* [SC 2.4.7 Focus Visible](https://www.w3.org/WAI/WCAG22/Understanding/focus-visible)  
* [SC 3.3.1 Error Identification](https://www.w3.org/WAI/WCAG22/Understanding/error-identification)  
* [SC 3.3.2 Labels or Instructions](https://www.w3.org/WAI/WCAG22/Understanding/labels-or-instructions)  
* [SC 3.3.6 Error Prevention](https://www.w3.org/WAI/WCAG22/Understanding/error-prevention-all)  
* [SC 4.1.2 Name, Role, Value](https://www.w3.org/WAI/WCAG22/Understanding/name-role-value)  
* [SC 4.1.3 Status Messages](https://www.w3.org/WAI/WCAG22/Understanding/status-messages)

## Code example

https://codepen.io/team/umlibrary-designsystem/pen/poQWomP
