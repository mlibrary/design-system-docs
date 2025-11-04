const { JSDOM } = require('jsdom');
const path = require('path');
const pluginNavigation = require("@11ty/eleventy-navigation");
const eleventySass = require("eleventy-sass");
const updatePermalinks = require('./lib/update-permalinks');
const updateMarkup = require('./lib/update-markup');
const { execSync } = require('child_process');

const markdownit = require('markdown-it');
const md = markdownit({ html: true, linkify: true });

module.exports = function(eleventyConfig) {

 // Custom collections
 // Returns an array collection from the reusableDesign tag, sorted alphabetically
  eleventyConfig.addCollection("reusableDesignAtoZ", function (collectionApi) {
    return collectionApi.getFilteredByTag("reusableDesign").sort((a, b) => {
      return a.data.title.localeCompare(b.data.title, undefined, { sensitivity: "base" });
    });
  });

  // All plugins used
  eleventyConfig.addPlugin(pluginNavigation);
  eleventyConfig.addPlugin(eleventySass, {
    compileOptions: {
      permalink: function(contents, inputPath) {
        return (data) => data.page.filePathStem.replace(/^\/scss\//, "/css/") + ".css";
      }
    },
    sass: {
      style: "compressed",
      sourceMap: false
    },
  });
  eleventyConfig.addPlugin(updatePermalinks);
  eleventyConfig.addPlugin(updateMarkup);

  // Paired shortcode for the callout component- variant types are info, print, alert, block
  eleventyConfig.addPairedShortcode("callout", function(content, variant) {
    return `<article class="umich-lib-callout ${variant}"><p><span class="visually-hidden">${variant} callout</span>${md.renderInline(content)}</p></article>`;
  });
  eleventyConfig.addShortcode("year", () => `${new Date().getFullYear()}`);

  // The addWatchTarget config method allows you to manually add a file for Eleventy to watch.
  eleventyConfig.addWatchTarget("./src/scss");
  eleventyConfig.addWatchTarget("./src/js");

  // The Pass Through feature tells Eleventy to copy things to our output folder
  // Eleventy passes through our compiled CSS to the public directory.
  eleventyConfig.addPassthroughCopy("./src/img");
  eleventyConfig.addPassthroughCopy("./src/js");
  
  //Robots
  eleventyConfig.addPassthroughCopy({ 'src/robots.txt': '/robots.txt' });
  

  eleventyConfig.on('eleventy.after', () => {
    execSync(`npx pagefind --site public --glob \"**/*.html\"`, { encoding: 'utf-8' })
  })

  return {
    passthroughFileCopy: true,
    dir: {
      input: "src",
      output: "public",
      layouts: "_includes/layouts",
    },
  };
};
