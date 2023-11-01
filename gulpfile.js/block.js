const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const { src, dest, series, watch } = require('gulp');
const livereload = require('gulp-livereload');
const rename = require('gulp-rename');
const sass = require('gulp-sass')(require('sass'));
const sourcemaps = require('gulp-sourcemaps');
const postcss = require('postcss');
const fs = require('fs');
const argv = require('yargs').argv;

const postCSSPlugins = [autoprefixer(), cssnano()];

const blockStyleScript = (srcFile, destFile, dev) =>
  dev
    ? src(srcFile)
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(sourcemaps.write())
        .pipe(dest(destFile))
        .pipe(livereload())
    : src(srcFile)
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(postcss(postCSSPlugins))
        .pipe(rename({ extname: '.min.css' }))
        .pipe(sourcemaps.write())
        .pipe(dest(destFile));

async function buildBlockStyles() {
  const dev = !!argv.D;
  const styleSource = `blocks/${argv.block}/${argv.block}.scss`;
  const editorStyleSource = `blocks/${argv.block}/${argv.block}-editor.scss`;
  const styleDest = `./dist/${argv.block}`;
  await fs.access(styleSource, err => {
    if (err) {
      console.log('no default styles');
      return;
    } else {
      return blockStyleScript(styleSource, styleDest, dev);
    }
  });
  await fs.access(editorStyleSource, err => {
    if (err) {
      console.log('no editor styles');
      return;
    } else {
      return blockStyleScript(editorStyleSource, styleDest, dev);
    }
  });
}

exports.developBlock = function developBlock() {
  if (!argv.block) {
    console.log('Must set option --block');
    return src('index.php');
  }

  console.log(argv.block);

  const styleSource = `blocks/${argv.block}/${argv.block}.scss`;
  const styleWatch = `blocks/${argv.block}/**/*.scss`;
  const styleDest = `./dist/${argv.block}`;

  livereload.listen();

  function refresh() {
    return src('index.php').pipe(livereload());
  }

  watch([styleWatch], () => blockStyleScript(styleSource, styleDest, true));
  watch(['**/*.php'], refresh);
};

// const buildBlock = series(buildBlockScript, buildBlockStyles);
const buildBlock = series(buildBlockStyles);

exports.buildBlockStyles = buildBlockStyles;
exports.buildBlock = buildBlock;
