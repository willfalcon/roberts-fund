const { src, series, parallel, watch, dest } = require('gulp');
const livereload = require('gulp-livereload');

const { devThemeStyles, prodThemeStyles, copyNormalize } = require('./styles');
const { devMainScript, prodMainScript } = require('./scripts');

const { buildBlock, developBlock } = require('./block');

function refresh(cb) {
  return src('index.php').pipe(livereload());
}

function watchTask(cb) {
  copyNormalize();
  livereload.listen();
  watch(['src/styles/**/*.scss'], parallel(devThemeStyles));
  // watch(['blocks/**/*.scss'], devBlockStyles);
  watch(['src/scripts/**/*.js'], devMainScript);
  // watch(['blocks/**/*.js'], blockScripts);
  watch(['**/*.php'], refresh);
  cb();
}

function prodCopyNormalize() {
  return copyNormalize(true);
}

exports.build = series(prodCopyNormalize, prodThemeStyles, prodMainScript);
exports.watch = watchTask;
exports.developBlock = developBlock;
exports.devThemeStyles = devThemeStyles;