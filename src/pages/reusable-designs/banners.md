---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Banners
eleventyNavigation:
  key: Banners
  summary: Grab the user’s attention and display important information across the full-width of a site.
  parent: Reusable Designs
  order: 0
---

# {{ title }}

Banners are full-width elements used to grab the user’s attention and display important information.

They contain a short description and can also feature an icon on the left side of the text.

## When to use banners

We have two types of banner: informational and warning

Teal **informational** banners are used to communicate non-urgent information, such as action items for users or information about changes to services. These banners have a lower urgency, but provide important details warranting its use.

Orange **warning** banners are used to alert users of outages, development status, building closures, construction, and other urgent information.  These banners may include pressing information about things that could impact a user’s ability to interact with the library.

## Guidelines for using banners

Banners should:

* Display under the Universal Header (if present) and/or above the website header  
* Use concise, plain language  
* Be no more than two sentences

We built accessibility best practices into our banner so it:

* Appears on page load and therefore does not use ARIA live region roles.  
* Hides the SVG icons used from assistive technology.
* Has screen reader only text to indicate whether the message is informational or a warning.

### Relevant WCAG guidelines

* [SC 1.3.1: Info and Relationships](https://www.w3.org/WAI/WCAG22/Understanding/info-and-relationships)  
* [SC 1.3.3: Sensory Characteristics](https://www.w3.org/WAI/WCAG22/Understanding/sensory-characteristics)  
* [SC 1.4.1: Use of Color](https://www.w3.org/WAI/WCAG22/Understanding/use-of-color)  
* [SC 2.2.3: No Timing](https://www.w3.org/WAI/WCAG22/Understanding/no-timing)  
* [SC 2.2.4: Interruptions](https://www.w3.org/WAI/WCAG22/Understanding/interruptions)  
* [SC 4.1.3: Status Messages](https://www.w3.org/WAI/WCAG22/Understanding/status-messages)

## Code example

https://codepen.io/team/umlibrary-designsystem/pen/NWJeYwV
