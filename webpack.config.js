const path = require('path');
const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .autoProvidejQuery()

    .setOutputPath('./public/')
    .setPublicPath('/bundles/umbrellaadmin/')
    .setManifestKeyPrefix('bundles/umbrellaadmin')

    .addAliases({
        umbrella_admin: path.join(__dirname, '/assets/'),
    })
    .addEntry('admin', './assets/admin.js')
    .enableSassLoader()

    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()

    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    // add hash after file name
    .configureFilenames({
        js: '[name].js?[chunkhash]',
        css: '[name].css?[contenthash]',
    })
;

module.exports = Encore.getWebpackConfig();
