const path = require('path');

// plugins
const StylelintPlugin = require('stylelint-webpack-plugin');
// const ESLintPlugin = require('eslint-webpack-plugin');
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');
const CopyPlugin = require('copy-webpack-plugin');
// const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
// const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const ReplaceInFileWebpackPlugin = require('replace-in-file-webpack-plugin');

module.exports = {
    mode: 'development',
    watch: true,
    devtool: 'cheap-source-map',
    entry: {
        app: './source/app.ts'
    },
    output: {
        filename: './JavaScript/[name].js',
        auxiliaryComment: 'Copyright - XIMA media GmbH',
        path: path.resolve(__dirname, 'Resources/Public'),
        publicPath: '../'
    },
    experiments: {
        backCompat: false,
    },
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                use: [
                    {
                        loader: 'ts-loader',
                        options: {
                            transpileOnly: true,
                        },
                    },
                ],
                exclude: /node_modules/,
            },
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
                            importLoaders: 1,
                            sourceMap: true,
                            url: {
                                filter: (url) => !url.includes('.svg')
                            }
                        },
                    },
                    'postcss-loader',
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
                                path.resolve(__dirname, './source/_patterns/components/basic/basic-default-functions-and-variables.scss'),
                                path.resolve(__dirname, './source/_patterns/components/icon/_sprite.scss')
                            ],
                        },
                    },
                ],
            },
            {
                test: /\.css$/i,
                use: ['style-loader', 'css-loader'],
            },
            {
                test: /\.(woff(2)?|ttf|eot)$/,
                type: 'asset/resource',
                generator: {
                    filename: './Fonts/[name][ext]',
                }
            },
        ],
    },
    resolve: {
        extensions: ['.tsx', '.ts', '.js'],
    },
    plugins: [
        new CopyPlugin({
            patterns: [
                {
                    from: './source/_patterns/components/image/assets', to: './Images/examples'
                },
                {
                    from: './source/_patterns/components/logo/assets', to: './Images/logo'
                },
                {
                    from: './source/_patterns/components/debug/assets', to: './Images/debug'
                },
                {
                    from: './source/_patterns/components/autocomplete/assets', to: './Images/autocomplete'
                },
            ],
        }),
        new MiniCssExtractPlugin({
            filename: './Css/[name].css',
        }),
        new SVGSpritemapPlugin('./source/_patterns/components/icon/assets/**/*.svg', {
            output: {
                filename: 'icon/icon.min.svg',
                svg4everybody: true,
                svgo: true
            },
            sprite: {
                prefix: 'icon-',
                generate: {
                    title: false
                }
            },
        }),
        new ReplaceInFileWebpackPlugin([{
            files: ['Resources/Public/Icon/icon.min.svg'],
            rules: [
                {
                    search: '<svg xmlns="http://www.w3.org/2000/svg"><symbol',
                    replace: '<svg xmlns="http://www.w3.org/2000/svg"><style>:root>svg{display:none}:root>svg:target{display:block}</style><symbol'
                },
                {
                    search: /symbol/ig,
                    replace: 'svg'
                }
            ]
        }])
    ],
    performance: {
        assetFilter: function (assetFilename) {
            // don't check file size limit of images
            if (assetFilename.endsWith('.jpg') || assetFilename.endsWith('.png')) {
                return false
            }
            return true
        },
    },
    optimization: {
        // runtimeChunk: true,
    //     // splitChunks: {
    //     //     cacheGroups: {
    //     //         styles: {
    //     //             name: 'styles',
    //     //             type: 'css/mini-extract',
    //     //             // For webpack@4
    //     //             test: /\.css$/,
    //     //             chunks: 'all',
    //     //             enforce: true,
    //     //         },
    //     //     },
    //     // },
        minimize: false,
    //     // minimizer: [
    //     //     new CssMinimizerPlugin(),
    //     // ],
    },
};
