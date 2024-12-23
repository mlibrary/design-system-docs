# Design System Docs

This is the 11ty Front-end codebase for https://design-system.lib.umich.edu.

The drupal cms repository is here:
https://github.com/mlibrary/design-system-cms

## Doc Site Development Quick Start

1. Run the initialization script. (You are encouraged to read what's in it.
   There's nothing magical.) This will build the Docker image and install node modules.

  ```
  ./init.sh
  ```

2. Edit `.env` with real values. Ask a Design System developer for help.

3. Starts the development server and watch changes in `.scss` and `.js` files.

```
docker-compose up
```

4. View in browser

```
http://localhost:8888
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

This is some very basic information. Please read the official [11ty
documentation](https://www.11ty.dev/docs/) for an in-depth guide to building
with 11ty.

### Edit site metadata

```
src/_data/meta.json
```

This data can be used in the markdown and Nunjucks files. The following
properties are supported:

<table>
    <thead>
        <tr>
            <th>Key</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>siteName</code></td>
            <td>The full name of the website.</td>
        </tr>
        <tr>
            <td><code>productName</code></td>
            <td>Shortened name to describe the Design System.</td>
        </tr>
        <tr>
            <td><code>url</code></td>
            <td>Site url</td>
        </tr>
        <tr>
            <td><code>siteDescription</code></td>
            <td>Description of the site used for SEO.</td>
        </tr>
         <tr>
            <td><code>language</code></td>
            <td>Language set in the <code>html</code> for the site.</td>
        </tr>
         <tr>
            <td><code>author</code></td>
            <td>Creator of the site. An object with author's <code>name</code>, <code>url</code>, and <code>email</code>.</td>
        </tr>
    </tbody>
</table>

### Layouts

Layouts use the Nunjucks templating language.

Page layouts are located the the `layouts` folder.

```
src/_includes/layouts
```

There is a `base.njk` file for the HTML boilerplate.
Additional layouts build off of that boilerplate.

```
/layouts
  base.njk
  home.njk
  landing-page.njk
  page.njk
```

Partials use the Nunjucks `{% include %}` and are located in the `partials` folder.

```
src/_includes/partials
```

## Create pages/ page content

### Index.md and 404.md

Static page content is created with markdown (.md).
Use YAML front matter to add data to your content. Locally assigned front matter
values override things further up the chain.

### Drupal content

Pages are generated from the Drupal content coming from the Drupal JSON:API
using the [11ty pagination feature](https://www.11ty.dev/docs/pagination/).

```
src/_data/drupal.js
```

#### Pagination files to create landing page and page

There are two Nunjuck files- one to generate a page (`page-generator.njk`) and
one to generate landing pages (`landing_page-generator.njk`).

## Styles

Edit the styles in the `src/scss` folder. 11ty is watching that folder. 11ty then passes through the compressed CSS to the
`public` folder.

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

Collections allow you to group data in certain ways using `tags`.

_Important distinction_: tags have a singular purpose in Eleventy... to
construct collections of content. Not to be confused with tag labels used in
blogs.

---

## Resources

Please visit the official [11ty](https://www.11ty.dev/docs/) docs.

These were helpful for data configuration:

- [Generate 11ty pages from external data](https://egghead.io/lessons/11ty-generate-eleventy-11ty-pages-from-external-data)
- [Using 11ty JavaScript Data files to mix Markdown and CMS content...](https://bryanlrobinson.com/blog/using-11ty-javascript-data-files-to-mix-markdown-and-cms-content-into-one-collection/)
