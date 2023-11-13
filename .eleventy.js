const { JSDOM } = require('jsdom');
const path = require('path');
const pluginNavigation = require("@11ty/eleventy-navigation");
const updatePermalinks = require('./lib/update-permalinks');
const updateMarkup = require('./lib/update-markup');
const { execSync } = require('child_process')

module.exports = function(eleventyConfig) {

  // Add the plugins used
  eleventyConfig.addPlugin(pluginNavigation);

  // eleventyConfig.addPlugin(updateMarkup);

  eleventyConfig.addPlugin(updatePermalinks, {
    src: path.resolve('./src/_data/drupal.js')
  });

  eleventyConfig.addPlugin(updateMarkup);

  eleventyConfig.addShortcode("year", () => `${new Date().getFullYear()}`);

  // The addWatchTarget config method allows you to manually add a file for Eleventy to watch.
  eleventyConfig.addWatchTarget("./src/scss");
  eleventyConfig.addWatchTarget("./src/js");

  // The Pass Through feature tells Eleventy to copy things to our output folder
  // Eleventy passes through our compiled CSS to the public directory. 
  eleventyConfig.addPassthroughCopy("./src/css");
  eleventyConfig.addPassthroughCopy("./src/img");
  eleventyConfig.addPassthroughCopy("./src/js");

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