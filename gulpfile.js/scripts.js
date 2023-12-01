const { src, dest } = require('gulp');
const named = require('vinyl-named');
const compiler = require('webpack');
const webpack = require('webpack-stream');
const livereload = require('gulp-livereload');

const scriptSrces = [
  'src/scripts/index.js',
  'src/scripts/editor.js',
  // 'src/scripts/contact-block/contact-block-editor.js',
  // 'src/scripts/contact-block/contact-block-options-page.js',
];

exports.devMainScript = function devMainScript() {
  return src(scriptSrces)
    .pipe(named())
    .pipe(
      webpack(
        {
          devtool: 'eval-cheap-module-source-map',
          mode: 'development',
          output: {
            filename: '[name].js',
          },
          module: {
            rules: [
              {
                test: /\.js$/,
                use: 'babel-loader',
                exclude: /node_modules/,
              },
            ],
          },
        },
        compiler
      )
    )
    .pipe(dest('dist/'))
    .pipe(livereload());
};

exports.prodMainScript = function prodMainScript() {
  return src(scriptSrces)
    .pipe(named())
    .pipe(
      webpack(
        {
          devtool: 'source-map',
          mode: 'production',
          output: {
            filename: '[name].min.js',
          },
          module: {
            rules: [
              {
                test: /\.js$/,
                use: 'babel-loader',
                exclude: /node_modules/,
              },
            ],
          },
        },
        compiler
      )
    )
    .pipe(dest('dist/'));
};
