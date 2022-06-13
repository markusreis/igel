const path = require('path');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
//const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");

module.exports = (env, argv) => {
    return {
        entry: {
            'igel-master': './assets/src/index.js',
        },
        output: {
            filename: '[name].bundle.min.js',
            publicPath: '/wp-content/plugins/igel/assets/dist/',
            path: path.resolve(__dirname, 'assets', 'dist'),
            chunkFilename: '[name].[hash].chunk.min.js',
        },
        plugins: [
            new CleanWebpackPlugin(),
            //new FixStyleOnlyEntriesPlugin(),
            new MiniCssExtractPlugin({
                filename: "[name].[chunkhash:8].css",
            }),
        ],
        watch: true,
        module: {
            rules: [
                {
                    test: /\.css$/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        'css-loader',
                        'postcss-loader',
                    ]
                },
                {
                    test: /\.(glb|gltf)$/,
                    use:
                        [
                            {
                                loader: 'file-loader',
                                options:
                                    {
                                        outputPath: 'assets/models/'
                                    }
                            }
                        ]
                },
                //{
                //    test: /\.(woff|woff2|eot|ttf|otf|svg)$/,
                //    loader: 'file-loader',
                //    options: {outputPath: 'fonts'}
                //},
                {
                    test: /\.(jpg|png)$/,
                    use: {
                        loader: 'url-loader',
                    },
                },

                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    loader: 'babel-loader',
                }
            ]
        }
    }
};