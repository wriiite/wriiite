define(function(require, exports, module) {
  "use strict";

  // External dependencies.
  var Backbone = require("backbone");
  console.log("module",module);

  
  // Defining the application router.
  module.exports = Backbone.Router.extend({
    routes: {
      "": "index",
      "books": "books_index",
      "books/": "books_index",
      "books/new": "books_edit",
      "books/:id": "books_view",

      "books/:id/view": "books_view",
      "books/:id/edit": "books_edit",
//    "books/:id/delete": "books_edit",

      "books/view/:id": "books_view",
      "books/edit/:id": "books_edit",
//    "books/delete/:id": "books_edit",

    },
    index: function() {
      console.log("Welcome to your / route.");
    },
    books_index: function() {
      console.log("Reading books.");
      var Book = require("modules/book");
      Book.View.index.prototype.render();
      
    },
    books_view: function(id) {
      console.log("Reading book #"+id);
      require("modules/book");
      Book.View.view.prototype.render({id:id});
    },
    books_edit: function(id) {
      console.log("editing book #"+id);
      var Book = require("modules/book");
      Book.View.edit.prototype.render({id:id});
    },
    // Shortcut for building a url.
    go: function() {
      return this.navigate(_.toArray(arguments).join("/"), true);
    }
  });
});
