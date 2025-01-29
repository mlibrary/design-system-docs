---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Breadcrumbs
eleventyNavigation:
  key: Breadcrumbs
  summary: An important navigational element for deep sites with clear hierarchy to show users where they are.
  parent: Reusable Designs
  order: 0
---

# {{ title }}

Breadcrumbs are an important navigation element that make users aware of their current location in relation to the rest of the website. They can help facilitate discovery when used appropriately, but are not always necessary.

## When to use breadcrumbs

**Use breadcrumbs** if the website is large (deep), and has a clear hierarchy. They’re also valuable if it’s likely a user may land on the page from an external source (such as internet search results).

**Don’t use breadcrumbs** for sites that are “flat” or single-level, or that don’t have a logical hierarchy or grouping.

## Guidelines for using breadcrumbs

When using breadcrumbs, the trail should:

* Display the current page’s position within the site hierarchy, not the session history.
* Appear in the top left corner of the screen, just below the primary navigation and above the page title.  
* Include the current page as the last item in the breadcrumb trail, unlinked.  
* Start with the homepage and display a maximum of 4 levels. As needed, start with dropping the 1st level (homepage) on the 5th level, and so forth.  
* Use the “\>” character to separate links.

Avoid letting breadcrumbs wrap to multiple lines. On mobile, consider shortening the breadcrumb trail to only include the parent page. This supports navigating back without being overwhelming on a smaller screen.

### Relevant WCAG guidelines

* [ARIA Breadcrumb Pattern](https://www.w3.org/WAI/ARIA/apg/patterns/breadcrumb/)  
* [2.4.8 Location](https://www.w3.org/WAI/WCAG21/Understanding/location.html)  
* [4.1.2 Name, Role, Value](https://www.w3.org/WAI/WCAG21/Understanding/name-role-value.html)

## Code example

https://codepen.io/team/umlibrary-designsystem/pen/qBVLPYJ
