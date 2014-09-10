/**
 * Created by Wes Gilleland on 9/9/14.
 */
var gulp = require('gulp');
var usemin = require('gulp-usemin');
var uglify = require('gulp-uglify');
var minifyCSS = require('gulp-minify-css');
var autoprefixer = require('gulp-autoprefixer');
var ignore = require('gulp-ignore');
var util = require('gulp-util');
var filesize = require('gulp-filesize');
/*
 * The build for the CSS files. Doesn't include Bootstrap because I trust Twitter to not be dumb
 */
gulp.task('css', function () {
	gulp.src('src/css/*.css')
		.pipe(filesize())   //show the original filesize
		.pipe(autoprefixer())
		.pipe(filesize())   //show the filesize after prefixing
		.pipe(minifyCSS())
		.pipe(filesize())   //show the final, minified filesize
		.pipe(gulp.dest('src/css/min'));
});
/*
 *  The build for the general JS files
 */
gulp.task('js', function () {
	gulp.src('src/js/*.js')
		.pipe(filesize())   //show the original filesize
		.pipe(uglify())
		.pipe(filesize())   //show the uglified filesize
		.pipe(gulp.dest('src/js/min'));
});
/*
 * The build for the general PHP files. This will run the most
 */
gulp.task('php', function () {
	/*
	 *  Just pipe all the PHP files through usemin because what harm is it, really?
	 *  Calls minifyCSS() and uglify() to make sure no files are missed before concatenating
	 *  Excludes adminer from the stream just so nothing fucks up
	 */
	gulp.src('src/*.php')
		.pipe(ignore.exclude('adminer*.php'))
		.pipe(usemin({
			css: [minifyCSS(), 'concat'],
			js:  [uglify(), 'concat']
		}))
		.pipe(gulp.dest('dist'));
	/*
	 * Move over adminer
	 */
	gulp.src('src/adminer*.php')
		.pipe(gulp.dest('dist'));
});
/*
 * Move the phpmailer stuff if it changes
 */
gulp.task('phpmailer', function () {
	gulp.src('src/phpmailer/**')
		.pipe(gulp.dest('dist/phpmailer'));
});
/*
 *  Default run task. Let the games begin!
 */
gulp.task('default', function () {

	/*
	 *  Take care of the CSS build
	 */
	gulp.run('css');
	gulp.watch('src/css/*.css', function () {
		gulp.run('css');
	})
	/*
	 *  Take care of the JS build
	 */
	gulp.run('js');
	gulp.watch('src/js/*.js', function () {
		gulp.run('js');
	});
	/*
	 *  Take care of the PHP build
	 */
	gulp.run('php');
	gulp.watch('src/**/*.php', function () {
		gulp.run('php');
	});
});