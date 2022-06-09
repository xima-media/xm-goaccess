const path = require('path');
const { merge } = require('webpack-merge');
const common = require('./webpack.common.config.js');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
// const CopyPlugin = require('copy-webpack-plugin');

module.exports = merge(common, {
    mode: 'production',
    output: {
      filename: 'JavaScript/[name].min.js',
    },
    plugins: [
      new MiniCssExtractPlugin({
        filename: 'Css/[name].min.css',
      }),
    ]
});
