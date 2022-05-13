const path = require('path');

// plugins
// const StylelintPlugin = require('stylelint-webpack-plugin');
// const ESLintPlugin = require('eslint-webpack-plugin');
// const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');
// const CopyPlugin = require('copy-webpack-plugin');
// const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
// const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');

module.exports = {
    mode: 'development',
    entry: {
        app: './patternlab/source/app.js'
    },
    output: {
        filename: 'js/[name].min.js',
        auxiliaryComment: 'Copyright - XIMA media GmbH'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                enforce: 'pre',
                use: ['source-map-loader'],
            },
            {
                test: /\.s[ac]ss$/i,
                use: [
                    'style-loader',
                    {
                        loader: MiniCssExtractPlugin.loader,
                        options: {
                            esModule: false,
                        },
                    },
                    {
                        loader: 'css-loader',
                        options: {
                            sourceMap: true,
                        },
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: true,
                        },
                    },
                    {
                        loader: 'sass-resources-loader',
                        options: {
                            // Must be true b/c of mixins
                            hoistUseStatements: false,
                            resources: [
                                path.resolve(__dirname, './patternlab/source/_patterns/components/basic/basic-default-functions-and-variables.scss')
                            ],
                        },
                    },
                ],
            },
            {
                test: /\.css$/i,
                use: ['style-loader', 'css-loader'],
            },
        ],
    },
    plugins: [
        // new CopyPlugin({
        //     patterns: [
        //         {
        //             from: './patternlab/source/_patterns/components/filter/assets/html', to: 'html/example'
        //         },
        //         {
        //             from: './patternlab/source/_patterns/components/stream-wrapper/assets/html', to: 'html/example'
        //         },
        //         {
        //             from: './patternlab/source/_patterns/components/favicon/assets', to: 'image/favicon'
        //         },
        //         {
        //             from: './patternlab/source/_patterns/components/logo/assets', to: 'image/logo'
        //         },
        //         {
        //             from: './patternlab/source/_patterns/components/image/assets', to: 'image/examples'
        //         },
        //         {
        //             from: './patternlab/source/_patterns/components/onscreen-video/assets/video', to: 'video/onscreen'
        //         },
        //         {
        //             from: './patternlab/source/_patterns/components/onscreen-video/assets/image', to: 'image/onscreen'
        //         },
        //         {
        //             from: './patternlab/source/_patterns/components/onscreen-video/assets/subtitles', to: 'subtitles/onscreen'
        //         }
        //     ],
        // }),
        new MiniCssExtractPlugin({
            filename: 'css/[name].min.css',
        }),
        // new CleanWebpackPlugin(),
        // new StylelintPlugin({
        //     files: ['**/*.scss'],
        //     configFile: '.stylelintrc.yml',
        //     emitWarning: true
        // }),
        // new ESLintPlugin({
        //     extensions: ['js']
        // }),
        // new SVGSpritemapPlugin('./patternlab/source/_patterns/components/icon/assets/**/*.svg', {
        //     output: {
        //         filename: 'icon/icon.min.svg'
        //     },
        //     sprite: {
        //         prefix: 'icon-'
        //     }
        // }),
    ],
    // optimization: {
    //     splitChunks: {
    //         cacheGroups: {
    //             styles: {
    //                 name: 'styles',
    //                 type: 'css/mini-extract',
    //                 // For webpack@4
    //                 test: /\.css$/,
    //                 chunks: 'all',
    //                 enforce: true,
    //             },
    //         },
    //     },
    //     // minimize: false,
    //     // minimizer: [
    //     //     new CssMinimizerPlugin(),
    //     // ],
    // },
};
