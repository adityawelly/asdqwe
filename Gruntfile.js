/*
 * grunt-cli
 * http://gruntjs.com/
 *
 * Copyright (c) 2012 Tyler Kellen, contributors
 * Licensed under the MIT license.
 * https://github.com/gruntjs/grunt-init/blob/master/LICENSE-MIT
 */

'use strict';

module.exports = function (grunt) {

	grunt.initConfig({
		concat: {
			css: {
				src: [
					'resources/sass/lightbox.min.css',
					'resources/sass/bootstrap.min.css',
        			'resources/sass/atlantis.min.css'
				],
				dest: 'public/css/app.css'
			},

			js: {
				nonull: true,
				src: [
					'resources/js/vendor/core/jquery.3.2.1.min.js',
					'resources/js/vendor/core/popper.min.js',
					'resources/js/vendor/core/bootstrap.min.js',
					'resources/js/vendor/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js',
					'resources/js/vendor/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js',
					'resources/js/vendor/plugin/moment/moment.min.js',
					'resources/js/vendor/plugin/bootstrap-toggle/bootstrap-toggle.min.js',
					'resources/js/vendor/plugin/sweetalert/sweetalert2.all.min.js',
					'resources/js/vendor/plugin/jquery-scrollbar/jquery.scrollbar.min.js',
					'resources/js/vendor/plugin/datepicker/bootstrap-datetimepicker.min.js',
					'resources/js/vendor/plugin/select2/select2.full.min.js',
					'resources/js/vendor/plugin/datatables/datatables.min.js',
					'resources/js/vendor/plugin/bootstrap-notify/bootstrap-notify.min.js',
					'resources/js/vendor/plugin/pace/pace.min.js',
					'resources/js/vendor/plugin/chart.js/chart.min.js',
					'resources/js/vendor/plugin/input-mask/jquery.mask.min.js',
					'resources/js/vendor/plugin/jquery.validate/jquery.validate.min.js',
					'resources/js/vendor/plugin/bootstrap-wizard/bootstrapwizard.js',
					'resources/js/vendor/plugin/lightbox/lightbox.min.js',
					'resources/js/vendor/atlantis.min.js'
				],
				dest: 'public/js/app.js'
			}
		},
		uglify: {
			js: {
				src: 'public/js/app.js',
				dest: 'public/js/app.min.js'
			},
			customjs: {
                options: {
                    mangle: false
                },
				src: 'public/js/custom.js',
				dest: 'public/js/custom.min.js'
			}
		},
		cssmin: {
			css: {
				src: 'public/css/app.css',
				dest: 'public/css/app.min.css'
			},
			customcss: {
				src: 'public/css/custom.css',
				dest: 'public/css/custom.min.css'
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.registerTask('build', ['concat', 'uglify', 'cssmin']);
};
