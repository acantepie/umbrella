const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore

    .setOutputPath('./public/')
    .setPublicPath('.')
    .setManifestKeyPrefix('')

    .addEntry('umbrella_admin', './assets/admin.js')
    
    .enableSassLoader((options) => {
        options.sassOptions = {
            quietDeps: true,
        }
    })

    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()

    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .autoProvidejQuery()

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })
;

module.exports = Encore.getWebpackConfig();
