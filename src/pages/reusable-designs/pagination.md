---
layout: page-reusable-design.njk
tags: reusableDesign
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Pagination
eleventyNavigation:
  key: Pagination
  summary: Pagination is a navigation element and pattern that divides content into multiple pages and provides controls to navigate between them.
  parent: Reusable Designs
---

# {{ title }}

Pagination is a navigation element that divides content into multiple pages and provides controls to navigate between them. Pagination can improve load times and help users navigate large amounts of information.

## When to use pagination

Use pagination to split up search results, article archives, directories, photo galleries, and so on. You can use it in-page or with a data table. 

There is no set maximum number of items that call for pagination, but you can use it anytime there’s too many results to show on one page or within one scroll. When making decisions about pagination, consider factors including load time, amount of information you need to display per item, and available screen space.

Our most detailed pagination design pattern has the following features:

* A page and results summary
* Control elements for First, Previous, Next, and Last  
* Control elements for selected and unselected pages  
* Ellipses for overflow content (more than the maximum number of pages)

### Pagination variations

You may need to make a per-product decision to strip pagination down in some cases, but the presentation should be consistent within the overall product. This could mean providing only the previous and next control elements, or only the numbered page links. We have examples of these variations in the CodePen below.

Pagination can also look and function differently depending on the underlying technology. Tools like Drupal or Bootstrap have built-in pagination components that you can style to look as similar as possible to our design.

In some circumstances, loading more items within the same page using a “show more” button can be an alternative option to pagination. This is especially useful for dynamic results lists within an otherwise static website. If you think this might be appropriate for your product, please [contact us](/our-team-and-approach//#get-in-touch) for a consultation.

## Guidelines for pagination

Place pagination at the bottom of your list. Our pagination is center aligned by default, but different alignment may be appropriate based on your use case.

We recommend retaining our provided page and results summary — which includes counters for both and can also have search criteria, depending on your implementation — and clearly identify which page the user is on within it. This may be at the top or bottom of your results (or both) depending on your site.

For your pagination control elements:

* Disable First, Previous, Next, or Last when the user is on the corresponding page.  
* Show a maximum of 10 pages in the pagination on a full screen and 6 on a small screen. The appropriate maximums for your site may be less than this.
  * When the number of pages exceeds this limit, use unlinked ellipses to truncate the overflow.  
  * If there is an overflow before *and* after the selected page, use ellipses to indicate the truncation for both.  
* Ensure touch targets are a minimum of 44px and don’t place them too close together.

### Relevant WCAG guidelines

* [1.3.1: Info and Relationships](https://www.w3.org/WAI/WCAG22/Understanding/info-and-relationships)  
* [1.4.1: Use of Color](https://www.w3.org/WAI/WCAG22/Understanding/use-of-color)  
* [2.1.1: Keyboard](https://www.w3.org/WAI/WCAG22/Understanding/keyboard)   
* [2.4.4 Link Purpose (In Context)](https://www.w3.org/WAI/WCAG22/Understanding/link-purpose-in-context)  
* [2.4.7 Focus Visible](https://www.w3.org/WAI/WCAG22/Understanding/focus-visible)

## Code example

https://codepen.io/team/umlibrary-designsystem/pen/zxrENRK