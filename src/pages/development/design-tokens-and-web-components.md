---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Design Tokens and Web Components
eleventyNavigation:
  key: Design Tokens and Web Components
  summary: An introduction to the evolving web platform technologies we use.
  parent: Develop
  order: 2
---

# {{ title }}

In addition to copy-and-paste HTML and CSS, our team is building out support for web components and design tokens, which offer increased sustainability and scalability.

## Design tokens

Design tokens are stored values for the visual elements that make up our design system such as colors, fonts, and spacing. Instead of assigning a hard coded value to a reusable design, we use design tokens for our themed values. These help us ensure our designs are consistent and can change as needs arise.

In the Design System, tokens are implemented as [CSS custom properties](https://developer.mozilla.org/en-US/docs/Web/CSS/--*) (also called CSS variables). Custom properties are defined once and referenced throughout your CSS using the `var()` function, so a single change to a token value cascades everywhere it's used.

For example, the design token `--color-teal-400` resolves to `#1d7491`

```css
a {  
  color: var(--color-teal-400);  
}
```

### Primitive and semantic tokens

We use two layers of design tokens for flexibility.

**Primitive tokens** are raw values. This is every color, size, and weight available in the system. They describe *what a value is*. `--color-teal-400` is a primitive token: it's always `#1d7491`, no matter where or how it's used.

**Semantic tokens** reference a primitive token and describe *how a value is used*. For example, `--link` will reference `--color-teal-400` in light theme and a different primitive in dark theme. Resuable designs reference semantic tokens rather than primitives, so the same code works correctly across themes without any changes.

```css
/* Primitive token — the raw value */
--color-teal-400: #1d7491;

/* Semantic token — references the primitive, carries meaning */
--link: var(--color-teal-400);

/* The Reusable Design component references the semantic token */
a {
  color: var(--link);
}
```

This two-layer approach is what makes themes possible. When the theme changes, only the semantic token mappings update.

## Web components

Web components are reusable custom HTML elements built on standard web platform technologies — HTML, CSS, and JavaScript. You can drop them into your websites and applications with no additional coding or styling required.

We have three web components available:

* [Universal Header](/reusable-designs/universal-header/)
* [Chat](/reusable-designs/chat/) 
* [Website Header](/reusable-designs/website-header/)

### Framework compatibility

Web components work with any framework or with no framework at all. Because they are built on web standards rather than any one library, they are usable for teams using different technology stacks across the U-M Library's digital products.

### How they work

A web component is a custom HTML element with its structure, styles, and behavior encapsulated inside it. You use it the same way you use any native HTML element:
```html
  <m-website-header name="Deep Blue Documents" variant="dark">
    <a href="/" style="color: white;">Log in</a>
  </m-website-header>
```

The element handles its own rendering and functionality. You don't need to use additional JavaScript or apply styles.

### What becomes a web component

Not every reusable design is a good candidate for a web component. Our team is deliberate about what we build as one, prioritizing designs that are used across many products, require consistent behavior, or carry significant accessibility requirements. This keeps the library focused and maintainable as browser support for the underlying platform continues to evolve.

## Ready to get started?

Visit the [Build User Interfaces](/develop/build-user-interfaces/) page for CDN, npm, and download options.
