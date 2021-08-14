const path = require('path');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");

module.exports = {
    entry  : {
        'app'   : './js/index.js',
        'main'  : ["./scss/style.css"],
        'inline': ["./scss/atf/style.css"]
    },
    output : {
        filename     : '[name].[hash].bundle.js',
        publicPath   : '/wp-content/themes/igel/assets/dist/',
        path         : path.resolve(__dirname, 'dist'),
        chunkFilename: '[name].[hash].chunk.min.js',
    },
    plugins: [
        new CleanWebpackPlugin(),
        new FixStyleOnlyEntriesPlugin(),
        new MiniCssExtractPlugin({
                                     filename: "[name].[chunkhash:8].css",
                                 }),
    ],
    watch  : true,
    module : {
        rules: [
            {
                test: /\.css$/,
                use : [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    "postcss-loader"
                ]
            },
            {
                test: /\.(glb|gltf)$/,
                use :
                    [
                        {
                            loader : 'file-loader',
                            options:
                                {
                                    outputPath: 'assets/models/'
                                }
                        }
                    ]
            },
            {
                test   : /\.(woff|woff2|eot|ttf|otf|svg)$/,
                loader : 'file-loader',
                options: {outputPath: 'fonts'}
            },
            {
                test: /\.(jpg|png)$/,
                use : {
                    loader: 'url-loader',
                },
            },
            {
                test   : /\.js$/,
                exclude: /node_modules/,
                loader : 'babel-loader',
                query  : {
                    presets: [
                        [
                            "@babel/env",
                            {
                                targets    : {
                                    edge   : '17',
                                    firefox: '60',
                                    chrome : '67',
                                    safari : '11.1',
                                    ie     : '11',
                                },
                                useBuiltIns: 'usage', //polyfill config
                            },
                        ]
                    ]
                }
            }
        ]
    },
};