const { src, dest, series, watch } = require('gulp');
const livereload = require('gulp-livereload');
const fs = require('fs');
const argv = require('yargs').argv;

const { styleScript } = require('./styles');

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
      return styleScript(styleSource, styleDest, dev);
    }
  });
  await fs.access(editorStyleSource, err => {
    if (err) {
      console.log('no editor styles');
      return;
    } else {
      return styleScript(editorStyleSource, styleDest, dev);
    }
  });
}

exports.developBlock = function developBlock() {
  if (!argv.block) {
    console.log('Must set option --block');
    return src('index.php');
  }

  const block = argv.block;
  console.log(block);

  const styleSource = `blocks/${block}/${block}.scss`;
  const styleWatch = [`blocks/${block}/**/*.scss`, `!blocks/${block}/**/*-editor.scss`];
  const editorStyleWatch = `blocks/${block}/**/*-editor.scss`;
  const editorStyleSource = `blocks/${block}/${block}-editor.scss`;
  const styleDest = `./dist/${block}`;

  livereload.listen();

  function refresh() {
    return src('index.php').pipe(livereload());
  }

  watch(styleWatch, () => styleScript(styleSource, styleDest, true));
  watch(editorStyleWatch, () => styleScript(editorStyleSource, styleDest, true));
  watch('src/styles/editor-styles.scss', () => styleScript('src/styles/editor-styles.scss', 'dist/', true));

  watch(['**/*.php'], refresh);
};

// const buildBlock = series(buildBlockScript, buildBlockStyles);
const buildBlock = series(buildBlockStyles);

exports.buildBlockStyles = buildBlockStyles;
exports.buildBlock = buildBlockStyles;

function buildBlocks() {}
