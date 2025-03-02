const path = require('path');
const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .autoProvidejQuery()

    .setOutputPath('./Bundle/AdminBundle/public/')
    .setPublicPath('/bundles/umbrellaadmin/')
    .setManifestKeyPrefix('bundles/umbrellaadmin')

    .addAliases({
        umbrella_core: path.join(__dirname, '/Bundle/CoreBundle/assets/'),
        umbrella_admin: path.join(__dirname, '/Bundle/AdminBundle/assets/'),
    })
    .addEntry('admin', './Bundle/AdminBundle/assets/admin.js')
    
    .enableSassLoader((options) => {
        options.sassOptions = {
            quietDeps: true,
        }
    })

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
