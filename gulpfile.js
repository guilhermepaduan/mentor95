const gulp = require('gulp')
const concat = require('gulp-concat')
const folders = require('gulp-folders-4x')
const rename = require('gulp-rename')
const uglify = require('gulp-uglify')
const sass = require('gulp-sass')


/**
 * Sass watcher
 */
gulp.task('css:watch', function() {
  return gulp.src([
    'wptheme/src/scss/main.scss'
  ])
  .pipe(sass({outputStyle: 'compressed'})).on('error', sass.logError)
  .pipe(rename({
    suffix: '.min'
  }))
  .pipe(gulp.dest('wptheme/dist/css'))
})



/**
 * Javascript watcher
 */
gulp.task('js:watch', function() {
  return gulp.src([
    'wptheme/src/js/components/*.js',
    'wptheme/src/js/pages/*.js',
    'wptheme/src/js/main.js',
  ])
  .pipe(concat('main.js'))
  .pipe(uglify())
  .pipe(rename({
    suffix: '.min'
  }))
  .pipe(gulp.dest('wptheme/dist/js/'))
})

gulp.task('js:watch:external', folders('wptheme/src/js/external', function(folder) {
  return gulp.src(readpath.join('wptheme/src/js/external', folder, '*.js'))
    .pipe(concat(folder + '.js'))
    .pipe(uglify())
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('wptheme/dist/js/'))
}))



/**
 * Watch files & Builders
 */
gulp.task('watchfiles', function() {
  gulp.watch(['wptheme/src/scss/**/*.scss'], gulp.series('css:watch'))
  gulp.watch(['wptheme/src/js/**/*.js'], gulp.series('js:watch', 'js:watch:external'))
})

gulp.task('build:dev', gulp.parallel('watchfiles'))
gulp.task('build:dev:sync', gulp.parallel('watchfiles', 'browsersync'))
