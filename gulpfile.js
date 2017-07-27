var gulp = require('gulp');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var neat = require('node-neat').includePaths;
var bourbon = require('node-bourbon').includePaths;
var bourbonbitters = require('bourbon-bitters').includePaths;

gulp.task('sass', function(){
  return gulp.src('sass/main.scss')
    .pipe(sass({
      includePaths: [bourbon, bourbonbitters, neat ],
      style: 'compressed',
      quiet: true
    })) // Converts Sass to CSS with gulp-sass
    .pipe(gulp.dest('assets/css'))
});

gulp.task('scripts', function() {
  return gulp.src([
    'assets/js/jquery.mb.YTPlayer.js',
    'assets/js/jquery.stellar.min.js',
    'assets/js/headroom.min.js',
    'assets/js/jquery.headroom.js',
    'assets/js/owl.carousel.js',
    'assets/js/jquery.fitvids.js',
    'assets/js/masonry.pkgd.min.js',
    'assets/js/jquery.flexslider.js',
    'assets/js/jquery.imagesloaded.min.js',
    'assets/js/plugins-scroll.js',
    'assets/js/waypoint.min.js',
    'assets/js/scripts.js'])
    .pipe(concat('scripts.js'))
    .pipe(gulp.dest('assets/js/min'))
    .pipe(rename('scripts.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('assets/js/min'));
});

gulp.task('watch', function() {
  gulp.watch('sass/**/*.scss', ['sass'])
});
