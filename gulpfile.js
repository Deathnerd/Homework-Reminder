/**
 * Created by Deathnerd on 9/9/14.
 */
var gulp = require('gulp');
var usemin = require('gulp-usemin');
var uglify = require('gulp-uglify');
var minifyCSS = require('gulp-minify-css');

gulp.task('default', function(){
	gulp.src('src/header.php')
		.pipe(usemin({
			css: [minifyCSS(), 'concat'],
			js: [uglify(), 'concat']
		}))
		.pipe(gulp.dest('dist'));
	gulp.src('src/index.php').pipe(gulp.dest('dist'));
	gulp.src('src/adminer-4.1.0.php').pipe(gulp.dest('dist'));
	gulp.src('src/footer.php').pipe(gulp.dest('dist'));
	gulp.src('src/actions.php').pipe(gulp.dest('dist'));
	gulp.src('src/mail_config_vals.php').pipe(gulp.dest('dist'));
	gulp.src('src/send_emails.php').pipe(gulp.dest('dist'));
	gulp.src('src/Utilities.php').pipe(gulp.dest('dist'));
	gulp.src('src/phpmailer/**').pipe(gulp.dest('dist/phpmailer'));
});