var fs = require('fs');

module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    clean: {
      install: [
        'public/libs/*'
      ]
    },
    touch: {
      install: {
        files: [
          {
            src:'public/packages/.gitkeep'
          }
        ]
      }
    },
    copy: {
      install: {
        flatten: true,
        expand: true,
        src: [
          'public/packages/jquery/jquery.js',
          'public/packages/angular/angular.js',
          'public/packages/angular-animate/angular-animate.js',
          'public/packages/angular-resource/angular-resource.js',
          'public/packages/angular-route/angular-route.js',
          'public/packages/pace/pace.js'
        ], 
        dest: 'public/libs/', 
        filter: 'isFile'
      }
    }
  });

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-copy');

  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.registerMultiTask('touch', 'create files', function() {
    for (var i = 0; i < this.data.files.length; i++) {
      var file = this.data.files[i].src.toString();
      fs.openSync(file, 'w');
      grunt.log.writeln('created '+file);
    }

  });

  // Default task(s).
  grunt.registerTask('default', ['lint','watch']);
  grunt.registerTask('lint', ['jshint']);


  grunt.registerTask('install', ['clean:install','touch:install','copy:install']);
//  grunt.registerTask('update', ['clean:install','touch:install','copy:install']);
  
  //    var package = require('./package.json'),
//        property = package.property[0];

};