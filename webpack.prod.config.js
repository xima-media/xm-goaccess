const {merge} = require('webpack-merge');
const devConfig = require('./webpack.config.js');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const {mergeWithCustomize, unique} = require("webpack-merge");

module.exports = mergeWithCustomize(
    {
        customizeArray: unique(
            "plugins",
            ["MiniCssExtractPlugin"],
            (plugin) => plugin.constructor && plugin.constructor.name
        ),
    })(
    devConfig,
    {
        mode: 'production',
        devtool: false,
        watch: false,
        output: {
            filename: 'JavaScript/[name].min.js',
        },
        plugins: [
            new MiniCssExtractPlugin({
                filename: 'Css/[name].min.css',
            }),
        ]
    }
);
