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
    module: {
        rules: [
            {
                test: /\.(woff(2)?|ttf|eot)$/,
                type: 'asset/resource',
                generator: {
                    filename: 'fonts/[name][ext]',
                }
            },
        ]
    },
    output: {
        path: path.resolve(__dirname, 'patternlab/public/dist'),
        // publicPath: '../../',
    }
});
