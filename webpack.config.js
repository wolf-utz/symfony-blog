var Encore = require('@symfony/webpack-encore')

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .addEntry('global', './assets/global/js/main.js')
  .addEntry('backend', './assets/backend/js/main.js')
  .addEntry('frontend', './assets/frontend/js/main.js')
  .enableLessLoader()
  .enableSassLoader()
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .autoProvidejQuery()

module.exports = Encore.getWebpackConfig()
