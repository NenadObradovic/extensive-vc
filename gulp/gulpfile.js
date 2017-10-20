// Include gulp
var gulp = require('gulp');

// Include libraries
var sass = require('gulp-sass');
var sassGlob = require('gulp-sass-glob');
var sourcemaps = require('gulp-sourcemaps');
var concat = require('gulp-concat');
var concatCss = require('gulp-concat-css');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var cssmin = require('gulp-cssmin');
var rtlcss = require('gulp-rtlcss');

// Compile sass files
gulp.task('sass', function () {
    return gulp.src([
    	    '../assets/css/scss/modules/*-map.scss'
	    ])
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(sassGlob())
        .pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
        .pipe(sourcemaps.write('../modules'))
        .pipe(gulp.dest('../assets/css/scss/modules'))
});

// Concatenate and minify all css files
gulp.task('css', ['sass'], function () {
	return gulp.src([
			'../assets/css/scss/modules/*-map.css'
		])
		.pipe(concatCss('../assets/css/main.css'))
		.pipe(gulp.dest('../css'))
		.pipe(cssmin())
		.pipe(rename({ suffix: '.min' }))
		.pipe(gulp.dest('../css'));
});

// Compile admin sass files
gulp.task('admin-sass', function () {
	return gulp.src([
		'../assets/css/admin/scss/modules/*-map.scss'
		])
		.pipe(sourcemaps.init({loadMaps: true}))
		.pipe(sassGlob())
		.pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
		.pipe(sourcemaps.write('../modules'))
		.pipe(gulp.dest('../assets/css/admin/scss/modules'));
});

// Concatenate and minify all admin css files
gulp.task('admin-css', ['admin-sass'], function () {
	return gulp.src([
		'../assets/css/admin/scss/modules/*-map.css'
		])
		.pipe(concatCss('../assets/css/admin/main-admin.css'))
		.pipe(gulp.dest('../admin'))
		.pipe(cssmin())
		.pipe(rename({ suffix: '.min' }))
		.pipe(gulp.dest('../admin'));
});

// Compile js files
gulp.task('modules-js', function () {
	return gulp.src([
			'../post-types/**/assets/js/*.js',
			'../shortcodes/**/assets/js/*.js'
		])
		.pipe(concat('modules.js'))
		.pipe(gulp.dest('../assets/js/modules'));
});

// Concatenate and minify all js files
gulp.task('js', ['modules-js'], function () {
	return gulp.src([
			'../assets/js/modules/*.js'
		])
		.pipe(concat('../assets/js/main.js'))
		.pipe(gulp.dest('../js'))
		.pipe(uglify())
		.pipe(rename({ suffix: '.min' }))
		.pipe(gulp.dest('../js'));
});

// Compile all files
gulp.task('minify', ['css', 'admin-css', 'js']);

// Watch plugin files for changes
gulp.task('watch', function () {
    gulp.watch([
        '../assets/css/scss/*.scss',
	    '../post-types/**/assets/css/scss/*.scss',
	    '../shortcodes/**/assets/css/scss/*.scss'
    ], ['css']);
	
	gulp.watch([
		'../assets/js/modules/common.js',
		'../post-types/**/assets/js/*.js',
		'../shortcodes/**/assets/js/*.js'
	], ['js']);
	
	gulp.watch([
		'../assets/css/admin/scss/modules/*.scss'
	], ['admin-css']);
});