var gulp = require('gulp');
var sass = require('gulp-sass');
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
