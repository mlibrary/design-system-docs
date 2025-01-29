---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Chat
eleventyNavigation:
  key: Chat
  summary: Provide persistent access to Ask a Librarian chat throughout a site.
  parent: Reusable Designs
  order: 0
---

# {{ title }}

Chat provides persistent access across a site to the **Ask a Librarian** chat service.

It is used on the [main library website](https://lib.umich.edu), [Library Search](https://search.lib.umich.edu), and a number of other U-M Library sites.

## When to use chat

Chat can be added to any site in the U-M Library web presence that’s supported by Ask a Librarian staff and is recommended if the site has a “Contact” page.

In consideration of their staffing capacity, please [contact the Design System team](/about/get-in-touch/) before adding using this reusable design and we will coordinate with our Ask a Librarian colleagues about your use case.

## Features of chat

Our customized [LibraryH3lp](https://libraryh3lp.com/) chat displays collapsed in the lower right corner of the user’s browser window for all pages in the respective site.

* Chat has a border shadow to provide contrast with site backgrounds.  
* Users have the option to expand the chat out into a new window.  
* Chat clearly differentiates which messages are from the user and which are from the library staff member.  
* Keyboard users can access chat through the skip link.

### Relevant WCAG guidelines

* [1.3.1 Info and Relationships](https://www.w3.org/WAI/WCAG21/Understanding/info-and-relationships.html)
* [1.3.2 Meaningful Sequence](https://www.w3.org/WAI/WCAG21/Understanding/meaningful-sequence)  
* [2.1 Keyboard](https://www.w3.org/WAI/WCAG22/Understanding/keyboard-accessible.html)  
* [2.4.1 Bypass Block](https://www.w3.org/WAI/WCAG21/Understanding/bypass-blocks)  
* [4.1.2 Name, Role, Value](https://www.w3.org/WAI/WCAG21/Understanding/name-role-value)  
* [4.1.3 Status Messages](https://www.w3.org/WAI/WCAG21/Understanding/status-messages)

## Code example

https://codepen.io/team/umlibrary-designsystem/pen/XWVpMep
