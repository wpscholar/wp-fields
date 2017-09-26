const autoPrefixer = require('autoprefixer');
const dashboardPlugin = require('webpack-dashboard/plugin');
const extractTextPlugin = require('extract-text-webpack-plugin');
const mediaQueryPacker = require('css-mqpacker');
const webpack = require('webpack');
const browsers = require('./browsers.json');

const isProduction = 'production' === process.env.NODE_ENV;
const showDashboard = 'yes' === process.env.DASHBOARD;
const cssFileExtension = isProduction ? '.min.css' : '.css';
const jsFileExtension = isProduction ? '.min.js' : '.js';

const extractCSS = new extractTextPlugin('assets/css/[name]' + cssFileExtension);

const config = {
    entry: {
        'wpscholar-fields': [
            './source/js/wpscholar-fields.js',
            './source/scss/wpscholar-fields.scss'
        ]
    },
    output: {
        filename: 'assets/js/[name]' + jsFileExtension,
        path: __dirname
    },
    externals: {
        'jquery': 'jQuery'
    },
    resolve: {
        extensions: ['.js', '.jsx', '.json'],
        modules: [
            'node_modules'
        ]
    },
    devtool: 'source-map',
    module: {
        rules: [
            {
                test: /\.scss$/,
                use: extractTextPlugin.extract({
                    fallback: 'style-loader',
                    use: [
                        {
                            loader: 'css-loader',
                            options: {sourceMap: true}
                        },
                        {
                            loader: 'postcss-loader',
                            options: {
                                sourceMap: true,
                                plugins: [
                                    autoPrefixer({browsers: browsers}),
                                    mediaQueryPacker()
                                ]
                            }
                        },
                        {
                            loader: 'sass-loader',
                            options: {sourceMap: true}
                        }
                    ]

                })
            },
            {
                test: /\.js$/,
                exclude: /(node_modules)/,
                loader: 'babel-loader',
                query: {
                    presets: [
                        ['babel-preset-env', {modules: false, targets: {browsers: browsers}}]
                    ],
                    plugins: [
                        'transform-class-properties',
                        'transform-object-rest-spread'
                    ]
                }
            }
        ]
    },
    plugins: [
        extractCSS,
        new webpack.DefinePlugin({
            'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV || 'development')
        })
    ]
};

if (showDashboard) {
    config.plugins.push(new dashboardPlugin());
}

module.exports = config;