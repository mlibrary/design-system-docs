# Design System Docs

This is the 11ty codebase for https://design-system.lib.umich.edu.

## Doc Site Development Quick Start

1. Install packages

```
npm install
```

2. Start the development server

```
npm start
```

or if you need a different port:

```
npx eleventy --port 6789 --serve
```

3. View in browser

```
http://localhost:8080
```

or if you changed the port in step 2

```
http://localhost:6789
```


## Dev Scripts

- `npm start` to start the eleventy server (serves the `/public` folder) and watch the `/scss` folder.
`.scss` files compile and output to `/public/css`.
- `npm build` to create a production build. Outputs into `/public`.

### Building and watching files

`eleventy-sass` is used as a dev dependency to watch and compile the `.scss` files and output to the `/public/css` directory.

Eleventy Dev Server watches the `/js` and `/scss` folders and triggers a build when files in those folders are changed.

---

## Developing the Site

Design System contributors should read the internal documentation for Development for the Doc site.
Please read the official [11ty documentation](https://www.11ty.dev/docs/) for an in-depth guide to building with 11ty.

### Layouts & Partials

Layouts use the Nunjucks templating language.

Page layouts are located the the `layouts` folder.

Partials use the Nunjucks `{% include %}` and are located in the `partials` folder.

```
src/_includes/partials
```

## Create pages/ page content

Design System contributors should read the internal documentation for Authoring in 11ty.

Static page content is created with markdown (.md).

### Front matter

Use YAML front matter to add data to your content. Locally assigned front matter
values override things further up the chain.

## Styles

Edit the styles in the `src/scss` folder. 11ty is watching that folder. 11ty then passes through the compiled CSS to the `public` folder.

## Images

Add images to the `src/img` folder. 11ty is watching that folder and passing
through the files to the `public` folder.

## 11ty Features

### **Plugins**

This site uses the [11ty Navigation Plugin](https://www.11ty.dev/docs/plugins/navigation/).
This plugin supports infinite-depth hierarchical navigation and breadcrumbs.

Add the `eleventyNavigation` object to your front matter data (or in a data
directory file). Assign a unique string to the key property inside of
`eleventyNavigation`.

```
eleventyNavigation:
  key: about
```

#### **Collections**

Collections allow you to group data in certain ways using `tags`. We use collections and custom collections.

_Important distinction_: tags have a singular purpose in Eleventy... to
construct collections of content. Not to be confused with tag labels used in
blogs.
