---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Accessibility and Inclusive Design
eleventyNavigation:
  key: Accessibility and Inclusive Design
  summary: The overarching strategies we practice to make our products accessible and welcoming for all.
  parent: Foundations
  order: 2
---

# {{ title }}

Accessibility is a shared responsibility. We want our products to be accessible and welcoming for all people. Overarching strategies guide our work, best practices are integrated throughout our design system, and we practice inclusive design.

We aim to be compliant with [WCAG 2.2 (Level A and AA)](https://www.w3.org/TR/WCAG22/) and follow the [four principles of accessibility](https://www.w3.org/TR/UNDERSTANDING-WCAG20/intro.html#introduction-fourprincs-head).

## Start with standards

​​We love HTML, CSS, and JavaScript. We prioritize using these foundational web technologies so the Design System can be used by developers working with any framework or no framework at all

We extend HTML by using features from the Accessible Rich Internet Applications (WAI-ARIA or ARIA) specification to build in functionality that is not available in native HTML. This is done in a purposeful manner and with guidance from the [W3C’s ARIA design patterns library](https://www.w3.org/TR/wai-aria-practices/examples/) to make our products more accessible.

## Use hierarchy and structure

Having a clear hierarchy helps everyone. We \<write for people\> using headings and lists, use consistent patterns in our \<reusable designs\>, and love testing and talking about information architecture.

## Write meaningful text

We are deep believers in the power of good content. Our \<content guidelines\> consider how people may come to our content, as well as how they read on the web, and we use plain language whenever possible.

## Make intentional language choices

We write with care to acknowledge our users and make them feel welcome at the library. This means making intentional choices in how we \<write about people\> and includes using inclusive language in how we talk about gender, age, race, ethnicity, nationality, and ability, as well as recognizing and eliminating racist and bigoted phrases and terms. We also follow plain language practices recognizing many people using the library speak or read English as their second language.  

## Be thoughtful about color and images

Our \<color palette\> provides a set of options to support consistency and create accessible combinations.  

## Design for keyboard navigation

We design and develop knowing that some people cannot (or choose not to) use a mouse.

In our designs, anything that can be seen by hovering with a mouse is also accessible through keyboard focus and by other assistive technologies (e.g. screen readers.).  We pay attention to the order of elements on the page and make sure the keyboard focus is visible.

## Test with assistive technology

In addition to our design and development practices, we test our \<visual elements\> and \<reusable designs\> for accessibility with automated and manual techniques.

These include native and third-party tools like:

* Accessibility testing software/evaluation tools: ARC Toolkit, axe testing library, WAVE, Accessibility Insights  
* Screen readers (VoiceOver, NVDA, and JAWS)

We recognize that no manual or automated tests can catch all barriers that people with disabilities may encounter with our content, products, and services. We actively include people with disabilities and those who use assistive technology in product design, testing, and development work to drive human centered improvements.
