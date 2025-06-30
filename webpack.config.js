const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    entry: './src/index.js',    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: 'bundle.js',
        publicPath: '/obelis-store/dist/',
    },
    module: {
        rules: [
            {
                test: /\.(js|jsx)$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env', '@babel/preset-react'],
                    },
                },
            },
            {
                test: /\.css$/,
                use: [MiniCssExtractPlugin.loader, 'css-loader'],
            },
            {
                test: /\.(png|jpg|gif|svg)$/,
                use: {
                    loader: 'file-loader',
                },
            },
        ],
    },
    resolve: {
        extensions: ['.js', '.jsx'],
    },    plugins: [
        new MiniCssExtractPlugin(),
    ],
    devServer: {
        historyApiFallback: true,
        port: 3000,
        proxy: {
            '/api': 'http://localhost:80',  // Proxy para las llamadas a la API de PHP
        },
    },
};
