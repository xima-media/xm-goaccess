const path = require('path');
const { merge } = require('webpack-merge');
const common = require('./webpack.common.config.js');

module.exports = merge(common,{
    mode: 'production', // development || production
    devtool: 'cheap-source-map',
    // watchOptions: {
    //     poll: true,
    //     ignored: /node_modules/
    // },
    // module: {
    //     rules: [
    //         {
    //             test: /\.(woff(2)?|ttf|eot|otf|svg)(\?v=\d+\.\d+\.\d+)?$/,
    //             use: [{
    //                 loader: 'file-loader',
    //                 options: {
    //                     name: '[folder]/[name].[ext]',
    //                     outputPath: 'fonts/',
    //                     publicPath: '../../fonts'
    //                 },
    //             }],
    //         },
    //         // {
    //         //     test: /\.(png|jpg|gif)$/i,
    //         //     use: [
    //         //         {
    //         //             loader: 'file-loader',
    //         //             options: {
    //         //                 name: '[folder]/[name].[ext]',
    //         //                 outputPath: 'cursor/',
    //         //                 publicPath: '../../assets/cursor/'
    //         //             },
    //         //         },
    //         //     ],
    //         // },
    //     ]
    // },
    output: {
        // path: path.resolve(__dirname, 'patternlab/source/assets'),
        path: path.resolve(__dirname, 'patternlab/public/dist'),
        publicPath: '../../',
    }
});
