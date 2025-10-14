---
layout: page.njk
tags: pages
permalink: "{{eleventyNavigation.parent | slugify}}/{{page.fileSlug}}/"

title: Motion
eleventyNavigation:
  key: Motion
  summary: Guidelines for using micro-interactions and animations.
  parent: Visual Elements, Content
  order: 5
---

# {{ title }}

Motion — micro-interactions and animations — can bring design elements to life and guide users through a digital experience. Micro-interactions ensure seamless and delightful usability for specific actions, while animations add to the tone and feel of the interface.

There are important factors to consider in your implementation of any motion in a user interface. Movement can cause dizziness and nausea for users with vestibular disorders and distract users with attention-deficit/hyperactivity disorder, causing them to lose track of their focus. You have to use motion thoughtfully and strategically and it must not harm or distract users. 

{% callout "info" %}Anyone who wants to add motion should directly contact the [Design System team](/about/our-team/) for help.{% endcallout %}

## Types of motion

A **micro-interaction** is a subtle, functional motion or feedback triggered by a user action or system state. A micro-interaction can improve usability, provide feedback, or reinforce an action, such as a subtle vibration or icon animation indicating a successful action. If you’re interested in adding motion, we encourage micro-interactions.

**Animation** uses motion to enhance the visual experience, tell a story, or communicate a concept. It often spans larger elements (entire screens, large-scale visual elements, or transitions) within an interface. A hero banner image with animated elements on page load is one example. We do not recommend pushing animations to a large audience (such as on a homepage). 

## Considerations and guidelines

The use of motion should be intentional, informational, and delightful. Our visual language is whimsical and classic, so using motion can help express that personality. 

Adding motion to an interface requires thinking through a number of considerations and should be limited to less than a third of any individual page overall. Any meaningful graphic elements must follow color contrast guidelines

### Purpose

Motion is fun to think about, but you should have a reason for using it. Expressing personality (delight) is a valid purpose\! The purpose may also be to focus or inform the user.

### Trigger

Consider what action by the user will trigger the motion. Options are generally scroll, click or check, or hover. Motion can also use autoplay and have no trigger. 

{% callout "info" %}Keep in mind that if an **animation** autoplays and is 5 seconds or longer, it **must** have a play/pause mechanism.{% endcallout %}

### Duration

Micro-interactions are…micro. Depending on all of the other considerations, micro-interactions typically last between 100 and 500ms (.1 to .5 seconds). For animation, the duration should be no more than 3-5 seconds. 

### Feeling (or easing)

Motion brings life to your interface and it has a vibe. Will your motion be calm and quiet or bouncy and playful? 

### Effect

Finally, what’s the motion going to do? Motion effects can include scale, opacity, movement, elevation, and more.   
Be sure to avoid flashes and bounces (they can be disorienting and trigger epileptic seizures), as well as parallax scrolling effects.

For animation specifically, the effect should be within a specific, contained area of the page (such as a hero banner image or within a modal) and any important, contextual information contained in the animation presented in text as well.

## Tools 

There are various options for creating animation. Depending on the type and complexity of the motion, different techniques may be used. Some methods our team may use:

### CSS

CSS is ideal for simple animations and is optimized for performance.

* [Keyframes](https://www.w3schools.com/css/css3_animations.asp) allows defining sequences of animation steps. It’s suitable for repeated animations like spinning or bouncing.  
* [Transitions](https://www.w3schools.com/css/css3_transitions.asp) is useful for animating changes between an element’s state (such as hover effects) or an element’s properties (such as width or position).


### JavaScript

For more advanced animations that may require dynamic control or complex sequencing. HTML DOM Animation is useful for directly manipulating DOM elements to create custom animations.

### 2D Canvas

The HTML [<canvas>](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/canvas) element provides a drawable surface for creating graphics, animations, and interactive visuals directly in the browser using JavaScript. Elements can be animated with the [canvas API](https://developer.mozilla.org/en-US/docs/Web/API/Canvas_API) or the [WebGL API](https://developer.mozilla.org/en-US/docs/Web/API/WebGL_API).

### SVG

SVG animations are vector-based and can be ideal for manipulating vector points and shapes as part of an overall graphic. [SVG \<animate\>](https://developer.mozilla.org/en-US/docs/Web/SVG/Reference/Element/animate) provides a way to animate vector graphics directly within SVG files.

### Third-party animation tools

[GreenSock Animation Platform (GSAP)](https://gsap.com/) is a powerful library for creating high-performance animations.

## Accessibility

No tools provide accessibility natively. We are responsible for the accessibility of our motion design.


### Providing controls

Where appropriate, we provide controls that allow users to start, stop, and adjust the speed of animations. This can be crucial to users who may be sensitive to motion or need more time processing moving content.

### prefers-reduced-motion

The prefers-reduced-motion CSS media feature detects if a user has enabled a setting on their device to minimize the amount of non-essential motion.

We provide the following in our DS CSS file:  
\`\`\`css  
@media (prefers-reduced-motion: reduce) {  
  \*,  
  \*::before,  
  \*::after {  
    animation-duration: 0.01ms \!important;  
    animation-iteration-count: 1 \!important;  
    transition-duration: 0.01ms \!important;  
    scroll-behavior: auto \!important;  
  }

  html {  
    scroll-behavior: initial;  
  }  
}  
\`\`\`

### Relevant WCAG guidelines

We follow the WCAG 2.2 requirements for motion.

* [1.4.11 Non-text Contrast](https://www.w3.org/WAI/WCAG22/Understanding/non-text-contrast.html)  
* [2.2.2 Pause, Stop, Hide](https://www.w3.org/WAI/WCAG22/Understanding/pause-stop-hide.html)  
* [2.3.1 Three Flashes or Below Threshold](https://www.w3.org/WAI/WCAG22/Understanding/three-flashes-or-below-threshold.html)  
* [2.3.3 Animations from Interactions (this is AAA)](https://www.w3.org/WAI/WCAG22/Understanding/animation-from-interactions.html) 

## Code examples

https://codepen.io/team/umlibrary-designsystem/pen/wBwRrOp  
