---
layout: page-reusable-design.njk
tags: reusableDesign
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: File Upload
eleventyNavigation:
  key: File Upload
  summary: An input control for uploading one or more files.
  parent: Reusable Designs
---

# {{ title }}

File upload lets users choose one or more files from their device storage and upload it. This input control is commonly found in forms, but can also serve as a standalone element.

## Guidelines for using file upload

Single upload is the default. Accept multiple files by using the “multiple” attribute.

Be sure to include a label with helper text if there are any restrictions (such as file type or size) that the user should know.

### Relevant WCAG guidelines

* [SC 1.3.1 Info and Relationships](https://www.w3.org/WAI/WCAG22/Understanding/info-and-relationships)  
* [SC 2.5.3 Label in Name](https://www.w3.org/WAI/WCAG22/Understanding/label-in-name.html)  
* [SC 2.4.3 Focus Order](https://www.w3.org/WAI/WCAG22/Understanding/focus-order)  
* [SC 2.4.7 Focus Visible](https://www.w3.org/WAI/WCAG22/Understanding/focus-visible)  
* [SC 3.3.1 Error Identification](https://www.w3.org/WAI/WCAG22/Understanding/error-identification)  
* [SC 3.3.2 Labels or Instructions](https://www.w3.org/WAI/WCAG22/Understanding/labels-or-instructions)  
* [SC 3.3.6 Error Prevention](https://www.w3.org/WAI/WCAG22/Understanding/error-prevention-all)  
* [SC 4.1.2 Name, Role, Value](https://www.w3.org/WAI/WCAG22/Understanding/name-role-value)

## Code example

https://codepen.io/team/umlibrary-designsystem/pen/ZEqBEex
