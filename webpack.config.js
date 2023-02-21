const path = require('path');

module.exports = {
    mode: 'development',
    watch: true,
    devtool: 'cheap-source-map',
    entry: {
        app: './packages/xm_dkfz_net_site/Resources/Private/TypeScript/app.ts',
        terminal: './packages/xm_dkfz_net_site/Resources/Private/TypeScript/terminal.ts'
    },
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, 'packages/xm_dkfz_net_site/Resources/Public/JavaScript/dist/'),
    },
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                use: 'ts-loader',
            },
        ],
    },
    resolve: {
        extensions: ['.tsx', '.ts', '.js'],
    },
};
