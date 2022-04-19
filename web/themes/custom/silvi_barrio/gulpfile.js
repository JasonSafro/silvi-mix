const gulp = require('gulp');
autoprefixer = require('gulp-autoprefixer');
sass = require('gulp-sass')(require('sass'));
sourcemaps = require('gulp-sourcemaps');
browserSync = require("browser-sync").create();

function silvi(done) {
  gulp.src('scss/**/*.scss')
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer())
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('css/'))
    .pipe(browserSync.reload({ stream: true }));
  done();
}

// BrowserSync
function sync(done) {
  browserSync.init({
    proxy: "http://silvi-mix.docksal/"
  });
  gulp.watch('scss/**/*.scss', silvi);
  gulp.watch('templates/**/*.twig').on('change', browserSync.reload);
  done();
}

exports.default = sync;
