/*global module:false*/
module.exports = function(grunt) {

    // Project configuration.
    grunt.initConfig({
        // Metadata.
        pkg: grunt.file.readJSON('package.json'),
        banner:
            '/*! <%= pkg.title || pkg.name %>\n' +
            '<%= pkg.homepage ? "* " + pkg.homepage + "\\n" : "" %>' +
            '* Copyright (c) <%= grunt.template.today("yyyy") %> <%= pkg.author.name %>;' +
            ' Licensed <%= _.pluck(pkg.licenses, "type").join(", ") %> */\n',
        // Task configuration.
        karma: {
            options: {
                configFile: 'karma.conf.js'
            },
            continuous: {
                singleRun: true,
                browsers: ['PhantomJS']
            },
        },
        shell: {
            target: {
                command: './compile'
            }
        },
        watch: {
            components : {
                files: ['src/**/*.js'],
                tasks: 'karma'
            },
            compile: {
                files: ['src/**/*.php', 'src/**/*.phtml', 'src/**/*.xml'],
                tasks: 'shell'
            }
        }
    });

    // These plugins provide necessary tasks.
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-karma');
    grunt.loadNpmTasks('grunt-shell');

    // Default task.
    grunt.registerTask('default', ['karma', 'shell']);
    grunt.registerTask('compile', ['shell', 'karma']);
    grunt.registerTask('compile:module', ['shell']);
    grunt.registerTask('compile:script', ['karma']);

};
