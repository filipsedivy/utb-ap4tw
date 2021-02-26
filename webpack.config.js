const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const {WebpackManifestPlugin} = require('webpack-manifest-plugin');

module.exports = {
    entry: {
        application: './bundle/index.js'
    },
    mode: (process.env.NODE_ENV === 'production') ? 'production' : 'development',
    module: {
        rules: [
            {
                test: /\.css$/i,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                        options: {
                            publicPath: ''
                        }
                    },
                    {
                        loader: 'css-loader',
                        options: {
                            importLoaders: 1
                        }
                    }, {
                        loader: 'postcss-loader',
                        options: {
                            postcssOptions: {
                                plugins: [
                                    require('autoprefixer')({
                                        overrideBrowserslist: ['last 3 versions', 'ie >9']
                                    })
                                ]
                            }
                        }
                    }
                ]
            },
            {
                test: /\.(scss)$/,
                use: [{
                    loader: 'style-loader', // inject CSS to page
                }, {
                    loader: 'css-loader', // translates CSS into CommonJS modules
                }, {
                    loader: 'postcss-loader', // Run post css actions
                    options: {
                        plugins: function () { // post css plugins, can be exported to postcss.config.js
                            return [
                                //require('precss'),
                                require('autoprefixer')
                            ];
                        }
                    }
                }, {
                    loader: 'sass-loader' // compiles Sass to CSS
                }]
            },
            {
                test: /\.(js)$/,
                use: {
                    loader: "babel-loader"
                },
                exclude: /node_modules/,
            },
            {
                test: /\.(png|jpg|gif|svg)$/i,
                use: [{
                    loader: 'url-loader',
                    options: {
                        limit: 8192,
                        name: '[name].[hash:7].[ext]'
                    }
                },
                    {
                        loader: 'image-webpack-loader'
                    }
                ]
            },
        ],
    },
    plugins: [
        new WebpackManifestPlugin({
            fileName: 'manifest.json'
        }),
        new MiniCssExtractPlugin({
            filename: "[name]-[contenthash].css"
        }),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery',
            'window.Nette': 'nette-forms',
            Popper: ['popper.js', 'default']
        })
    ],
    resolve: {
        extensions: ['.js', '.jsx'],
    },
    output: {
        filename: '[name]-[fullhash].js',
        chunkFilename: '[id]-[chunkhash].js',
        path: path.join(__dirname, 'www', 'assets'),
        publicPath: ""
    },
    devServer: {
        contentBase: path.join(__dirname, 'www', 'assets'),
        port: 3180
    },
};