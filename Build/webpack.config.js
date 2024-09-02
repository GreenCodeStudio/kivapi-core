const MiniCssExtractPlugin = require('mini-css-extract-plugin');
var path = require('path');
module.exports = {
    entry: {
        style: './style.scss',
        js: './js.js',
        panelStyle: './panelStyle.scss',
        panelJs: './panelJs.js',
        inSiteEditJs: '../../Core/InSiteEdit/Js/index.js',
    }, output: {
        path: path.resolve(__dirname, '../../BuildResults/Dist'),
        publicPath: "/Dist/",
        filename: '[name].js',
        chunkFilename: '[name].[id].js'
    },
    module: {
        rules: [
            {
                test: /\.s?css$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                    }, {
                        loader: 'resolve-url-loader',
                    }, {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: true
                        }
                    }
                ]
            },
            {
                test: /\.(woff2?.|ttf)$/,
                use: [
                    "file-loader"
                ]
            },
            {
                test: /i18n\.xml$/,
                use: ["../../Core/Js/Internationalization/i18nWebpackLoader"]//path from tmp/build
            }
        ]
    },
    plugins: [new MiniCssExtractPlugin()],
};
