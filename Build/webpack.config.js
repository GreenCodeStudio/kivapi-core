const MiniCssExtractPlugin = require('mini-css-extract-plugin');
var path = require('path');
var fs = require('fs')
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
        filename: (x)=>{
            if(x.runtime=='js'){
                const file=fs.openSync(path.resolve(__dirname, '../../BuildResults/Dist/js.html'), 'w')
                fs.writeSync(file, '<script src="/Dist/js.'+x.hash+'.js"></script>')
            }
            if(x.runtime=='inSiteEditJs'){
                const file=fs.openSync(path.resolve(__dirname, '../../BuildResults/Dist/inSiteEditJs.html'), 'w')
                fs.writeSync(file, '<script src="/Dist/inSiteEditJs.'+x.hash+'.js"></script>')
            }
            if(x.runtime=='panelJs'){
                const file=fs.openSync(path.resolve(__dirname, '../../BuildResults/Dist/panelJs.html'), 'w')
                fs.writeSync(file, '<script src="/Dist/panelJs.'+x.hash+'.js"></script>')
            }
            if(x.runtime=='style'){
                const file=fs.openSync(path.resolve(__dirname, '../../BuildResults/Dist/style.html'), 'w')
                fs.writeSync(file, '<link href="/Dist/style.css?'+x.hash+'" rel="stylesheet">')
            }
            if(x.runtime=='panelStyle'){
                const file=fs.openSync(path.resolve(__dirname, '../../BuildResults/Dist/panelStyle.html'), 'w')
                fs.writeSync(file, '<link href="/Dist/panelStyle.css?'+x.hash+'" rel="stylesheet">')
            }
            console.log(x);
            return '[name].[fullhash].js'
        },
        chunkFilename: '[chunkhash].js'
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
                type: 'asset/resource'
            },
            {
                test: /i18n\.xml$/,
                use: ["../../Core/Js/Internationalization/i18nWebpackLoader"]//path from tmp/build
            }
        ]
    },
    plugins: [new MiniCssExtractPlugin()],
};
