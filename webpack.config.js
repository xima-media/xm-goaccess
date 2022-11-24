const path = require('path');

module.exports = {
    mode: 'development',
    watch: true,
    devtool: 'cheap-source-map',
    entry: {
        app: './packages/xm_dkfz_net_site/Resources/Private/TypeScript/app.ts'
    },
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, 'packages/xm_dkfz_net_site/Resources/Public/JavaScript/dist/'),
    },
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                use: [{
                    loader: 'ts-loader',
                    options: {
                        transpileOnly: true,
                    },
                }],
                exclude: /node_modules/,
            },
        ],
    },
    resolve: {
        extensions: ['.tsx', '.ts', '.js'],
    },
};
