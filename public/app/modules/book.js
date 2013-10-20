define([
  // These are path alias that we configured in our bootstrap
  'app',        // general app variables
  'jquery',     // lib/jquery/jquery
  'underscore', // lib/underscore/underscore
  'backbone',   // lib/backbone/backbone
], function(app,$, _, Backbone){
  // Above we have passed in jQuery, Underscore and Backbone
  // They will not be accessible in the global scope

  var Book = {};

  Book.Collection = Backbone.Collection.extend({
    url: app.api+'/book'
  });

  Book.Model = Backbone.Model.extend({
    urlRoot: app.api+'/book'
  });


  Book.View = {
    index: Backbone.View.extend({
      initialize:function() {
        this.listenTo(this.model, "change", this.render);
      },
      el: "body",
      events: {},
      render: function() {
        var that = this;
        console.log('that',that, that.$el)
        console.log("Book.View.index rendering",that);
        var books = new Book.Collection()
        books.fetch({
          success: function(data) {
            if( typeof data.models[0].attributes.error !== "undefined" 
              && data.models[0].attributes.error == false ) {
                var books = data.models[0].attributes.books
                console.log("looks like we have fine books ",data.models[0].attributes);
                require(["text!templates/books/index.html"], function(template) {
                  $(that.el).html(_.template(template,{books: books}));
                });
              }
              else { // there is an error
                require(["text!templates/errors/connection.html"], function(template){
                  $(that.el).html(_.template(template,{message: data.models[0].attributes.message}));
                })
              }
          },
          error: function(e) {
            if(!e.length){
              require(["text!templates/errors/connection.html"], function(template){
                $(that.el).html(template);
              })
            }
            else {
              require(["text!templates/errors/unknown.html"], function(template){
                $(that.el).html(template);
              })
            }
            console.log("error",e, template)
          }
        })
      }
    }),
    // view
    view: Backbone.View.extend({
      el: "body",
      render: function(options) {
        var that = this;
        if(options.id) {
          that.book = new Book.Model({id: options.id})
          console.log('that',that, that.$el)
          console.log("Book.View.view rendering",that);
          var books = new Book.Collection()
          that.book.fetch({
            success: function(data) {
              var book = data.attributes;
              require(["text!templates/books/view.html"], function(template) {
                $(that.el).html(_.template(template,{book: book}));
              });
            },
            error: function(e) {
              console.log('there is an error, and it should be coped with the error manager')
            }
          })
          
        } // options.id
      }
    }),
    // edit
    edit: Backbone.View.extend({
      initialize: function(e){
        console.log("initialize edit",e);
      },

      el: "body",

      render: function(options) {
        var that = this;
        if(options.id) {
          that.book = new Book.Model({id: options.id})
          console.log('that',that, that.$el)
          console.log("Book.View.edit rendering",that);
          var books = new Book.Collection()
          that.book.fetch({
            success: function(data) {
              var book = data.attributes;
              require(["text!templates/books/edit.html"], function(template) {
                $(that.el).html(_.template(template,{book: book}));
                that.initialize();
              });
            },
            error: function(e) {
              console.log('there is an error, and it should be coped with the error manager')
            }
          })
          
        } // options.id
      },

      events: {
        "submit .edit-user-form": "save",
        "click .submit": function(){
          return false;
        }
      },
      save: function(ev) {
        $t = $(ev.currentTarget);
        title = $(".title", $t).value();
        console.log($t);
        ev.preventDefault();
        return false;
      },

    }),
    // get ?
    get: function(id){
      console.log("view",id);
    }
  }


// why can't I directly call it Book.View.index()
// or Book.View.index.render();
//  Book.View.index.prototype.render();
//  I should call the view from routing


  return Book;
  // What we return here will be used by other modules
});