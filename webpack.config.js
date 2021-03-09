const path = require("path");
const webpack = require("webpack");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const {WebpackManifestPlugin} = require("webpack-manifest-plugin");

module.exports = {
    entry: {
        application: "./bundle/index.js"
    },
    mode: (process.env.NODE_ENV === "production") ? "production" : "development",
    module: {
        rules: [
            {
                test: /\.(css)$/i,
                use: [
                    "style-loader",
                    "css-loader"
                ],
            },
            {
                test: /\.s[ac]ss$/i,
                use: [
                    "style-loader",
                    "css-loader",
                    "sass-loader"
                ]
            },
            {
                test: /\.(js)$/i,
                use: {
                    loader: "babel-loader"
                },
                exclude: /node_modules/,
            },
            {
                test: /\.(png|jpg|gif|svg)$/i,
                use: [
                    {
                        loader: "url-loader",
                        options: {
                            limit: 8192,
                            name: "[name].[hash:7].[ext]"
                        }
                    },
                    {
                        loader: "image-webpack-loader"
                    }
                ]
            },
            {
                test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/i,
                use: [
                    {
                        loader: "url-loader",
                        options: {
                            name: "[name].[ext]",
                            outputPath: "/fonts/"
                        }
                    }
                ]
            },
        ],
    },
    plugins: [
        new WebpackManifestPlugin({
            fileName: "manifest.json"
        }),
        new MiniCssExtractPlugin({
            filename: "[name]-[contenthash].css"
        }),
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery",
            "window.jQuery": "jquery",
            Popper: ["popper.js", "default"]
        })
    ],
    resolve: {
        extensions: [".js", ".jsx"],
    },
    output: {
        filename: "[name]-[fullhash].js",
        chunkFilename: "[id]-[chunkhash].js",
        path: path.join(__dirname, "www", "assets"),
        publicPath: ""
    },
    devServer: {
        contentBase: path.join(__dirname, "www", "assets"),
        port: 3180,
        headers: {
            "Access-Control-Allow-Origin": "*",
            "Access-Control-Allow-Methods": "GET, POST, PUT, DELETE, PATCH, OPTIONS",
            "Access-Control-Allow-Headers": "X-Requested-With, content-type, Authorization"
        }
    },
};
