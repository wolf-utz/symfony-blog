var CopyWebpackPlugin = require('copy-webpack-plugin')
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
  .addPlugin(new CopyWebpackPlugin([
    // Copy the skins from tinymce to the build/skins directory
    {from: 'node_modules/tinymce/skins', to: 'skins'}
  ]))

module.exports = Encore.getWebpackConfig()
