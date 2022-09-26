const EleventyFetch = require("@11ty/eleventy-fetch");

module.exports = async function() {
  // 
  let json = await EleventyFetch("https://design-system-cms.kubernetes.lib.umich.edu/jsonapi/node/page/", {
    duration: "1d", // 1 day
    type: "json",
    method: 'GET',
  });

  return {
    // Placeholders to test fetch 
    pageBody: json.data[0].attributes.body.value,
    pageTitle: json.data[0].attributes.title
  };
};