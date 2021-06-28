const path = require('path');
const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('./Bundle/AdminBundle/public/')
    .setPublicPath('/bundles/umbrellaadmin/')
    .setManifestKeyPrefix('bundles/umbrellaadmin')

    .addAliases({
        umbrella_core: path.join(__dirname, '/Bundle/CoreBundle/assets/'),
        umbrella_admin: path.join(__dirname, '/Bundle/AdminBundle/assets/'),
    })
    .addEntry('admin', './Bundle/AdminBundle/assets/admin.js')
    .enableSassLoader()

    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()

    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // add hash after file name
    .configureFilenames({
        js: '[name].js?[chunkhash]',
        css: '[name].css?[contenthash]',
    })
;

module.exports = Encore.getWebpackConfig();
