## Development Quick Start

Install 11ty and dependencies

```
npm install
```

Start development server and watch Sass (.scss) files

```
npm start
```

View in browser

```
http://localhost:8080
```

## Dev Scripts

- `npm start` to start the eleventy server (serves the `/public` folder) and watch the Sass `/scss` folder
- `npm build` to create a production build. Outputs into `/public`.

### Building and watching files

`npm-run-all` is a CLI tool to run multiple npm-scripts in parallel or sequential. This is used in the dev scripts to watch Sass files and hot reload 11ty files in parallel.

---

## Building Your Site

This is some very basic information. Please read the official [11ty documentation](https://www.11ty.dev/docs/) for an in-depth guide to building with 11ty.

### Edit site metadata

Edit your site metadata (site name, author, contact, url, etc..)

```
src/_data/meta.json
```

### Layouts

Page layouts are located the the `layouts` folder.

```
src/_includes/layouts
```

Layouts use the Nunjucks templating language.
There is a `base.njk` file for the HTML boilerplate.
Additional layouts build off of that boilerplate.

### Create pages/ page content

Page content is created with Markdown (.md).

```
src/pages
```

Create your content in a markdown file. Use YAML front matter to add data to your content. Locally assigned front matter values override things further up the chain.

### Styles

Edit the styles in the `src/scss` folder. 11ty is watching that folder and building the Sass files into `src/css`. 11ty then passes through the CSS to the `public` folder.

### Images

Add images to the `src/img` folder. 11ty is watching that folder and passing through the files to the `public` folder.

### 11ty Features

#### **Plugins**

This site uses the [11ty Navigation Plugin](https://www.11ty.dev/docs/plugins/navigation/).
This plugin supports infinite-depth hierarchical navigation and breadcrumbs.

Add the `eleventyNavigation` object to your front matter data (or in a data directory file). Assign a unique string to the key property inside of `eleventyNavigation`.

```
eleventyNavigation:
  key: about
```

#### **Collections**

Collections allow you to group data in certain ways using `tags`.

_Important distinction_: tags have a singular purpose in Eleventy... to construct collections of content. Not to be confused with tag labels used in blogs.

---

## Resources

Please visit the official [11ty](https://www.11ty.dev/docs/) docs.
