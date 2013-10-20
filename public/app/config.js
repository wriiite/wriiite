// This is the runtime configuration file.  It complements the Gruntfile.js by
// supplementing shared properties.
require.config({
  paths: {
    // Make vendor easier to access.
    "vendor": "./vendor",

    // Almond is used to lighten the output filesize.
    "almond": "./vendor/almond/almond",

    // requirejs text plugin
    "text" : "/vendor/requirejs-text/text",

    // Opt for Lo-Dash Underscore compatibility build over Underscore.
    "underscore": "/vendor/lodash/dist/lodash.underscore",

    // Map remaining vendor dependencies.
    "jquery": "/vendor/jquery/jquery",
    "backbone": "/vendor/backbone/backbone"
  },

  shim: {
    // This is required to ensure Backbone works as expected within the AMD
    // environment.
    "backbone": {
      // These are the two hard dependencies that will be loaded first.
      deps: ["jquery", "underscore"],

      // This maps the global `Backbone` object to `require("backbone")`.
      exports: "Backbone"
    }
  }
});
