const { src, dest } = require('gulp');
const sourcemaps = require('gulp-sourcemaps');
const sass = require('gulp-sass')(require('sass'));
const concat = require('gulp-concat');
const postcss = require('gulp-postcss');
const cssnano = require('cssnano');
const autoprefixer = require('autoprefixer');
const livereload = require('gulp-livereload');

const postCSSPlugins = [autoprefixer(), cssnano()];

exports.copyNormalize = function copyNormalize(prod = false) {
  const source = src('node_modules/normalize.css/normalize.css');
  return prod ? source.pipe(postcss(postCSSPlugins)).pipe(concat('normalize.min.css')).pipe(dest('dist/')) : source.pipe(dest('dist/'));
};

exports.devThemeStyles = function devThemeStyles() {
  return src('src/styles/styles.scss')
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(concat('styles.css'))
    .pipe(sourcemaps.write())
    .pipe(dest('dist/'))
    .pipe(livereload());
};

exports.prodThemeStyles = function prodThemeStyles(cb) {
  return src('src/styles/styles.scss')
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(concat('styles.min.css'))
    .pipe(postcss(postCSSPlugins))
    .pipe(sourcemaps.write('./'))
    .pipe(dest('dist/'));
};
