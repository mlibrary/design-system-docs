---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Buttons
eleventyNavigation:
  key: Buttons
  summary: Communicate actions users can take using a click, tap, or keypress.
  parent: Reusable Designs
  order: 0
---

# {{ title }}

Buttons communicate actions that users can take. The action is made clear and visible and can be performed with one click, tap, or keypress. Buttons contain a clear label and sometimes an icon.

## When to use buttons

Use buttons to let users:

* Take an action, such as downloading, saving, publishing, uploading, or opening a dialog box  
* Progress to another step (for example: in a multi-page survey)  
* Submit a form

Buttons generally should not be used for standard navigation to another page in the same website. Instead, [use links](/content/grammar-and-style/#link-text-hyperlinks) if the action will take a user to a new page.

## Our button types

In most cases, use the **Primary** button type. This includes actions, progressing to another step, and submitting a form.

The **Secondary** type can be used when placing two buttons near each for differentiation.

We also provide 3 additional types for more specialized uses:

* **Ghost** — form elements and dropdowns
* **Critical** — irreversible actions like deleting data  
* **Search** — search input only

## Guidelines for using buttons

Buttons must include **hover and focus states** that are unique from the resting state. On focus, buttons must identify its name, role and state.

**Button labels** should:

* Be clear and indicate what will happen when a user selects the button.
  * For sets of buttons, use specific labels like “Save” and “Discard” instead of “OK” or “Cancel”.  
* Use active verbs, such as **Add**, **Delete**, or **Save**.  
* Use sentence case (Set preferences, not Set Preferences).  
* Be 3 words or less if possible and avoid wrapping. For best legibility, a label should remain on a single line.

**Icons** may be added to convey meaning and draw more attention, but must come before the text label. Never use more than one icon per button. Avoid icon-only buttons unless they are for quick actions that are widely recognizable when you have limited space.

### Relevant WCAG guidelines

* [ARIA Button Pattern](https://www.w3.org/WAI/ARIA/apg/patterns/button/)  
* [1.3.1 Info and Relationships](https://www.w3.org/WAI/WCAG21/Understanding/info-and-relationships.html)  
* [2.4.7 Focus Visible](https://www.w3.org/WAI/WCAG21/Understanding/focus-visible.html)  
* [3.2.2 On Input](https://www.w3.org/WAI/WCAG21/Understanding/on-input.html)  
* [4.1.2 Name, Role, Value](https://www.w3.org/WAI/WCAG21/Understanding/name-role-value.html)

## Code example

https://codepen.io/team/umlibrary-designsystem/pen/PoOXJjM
