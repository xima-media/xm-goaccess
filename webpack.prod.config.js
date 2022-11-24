const {merge} = require('webpack-merge');
const devConfig = require('./webpack.config.js');
const TerserPlugin = require("terser-webpack-plugin");

module.exports = merge(
    devConfig,
    {
        mode: 'production',
        devtool: false,
        watch: false,
        output: {
            filename: '[name].min.js',
        },
        optimization: {
            minimize: true,
            minimizer: [new TerserPlugin()],
        },
    }
);
