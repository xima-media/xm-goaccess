const path = require('path');
const { merge } = require('webpack-merge');
const common = require('./webpack.common.config.js');
// const CopyPlugin = require('copy-webpack-plugin');

module.exports = merge(common, {
    mode: 'production',
    module: {
        rules: [
            {
                test: /\.(woff(2)?|ttf|eot|otf|svg)(\?v=\d+\.\d+\.\d+)?$/,
                use: [{
                    loader: 'file-loader',
                    options: {
                        name: '[folder]/[name].[ext]',
                        outputPath: 'fonts/',
                        publicPath: '../../Voices/fonts'
                    },
                }],
            },
            // {
            //     test: /\.(png|jpg|gif)$/i,
            //     use: [
            //         {
            //             loader: 'file-loader',
            //             options: {
            //                 name: '[folder]/[name].[ext]',
            //                 outputPath: 'cursor/',
            //                 publicPath: '../../cursor/'
            //             },
            //         },
            //     ],
            // },
        ]
    },
    output: {
        path: path.resolve(__dirname, '../../typo3/web/typo3conf/ext/xm_templates_skd/Resources/Public/Voices'),
        publicPath: '../../typo3/web/typo3conf/ext/xm_templates_skd/Resources/Public/Voices/'
    }
});
