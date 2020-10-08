var CopyWebpackPlugin = require('copy-webpack-plugin');
var Encore = require('@symfony/webpack-encore');
Encore.setOutputPath('public/build/').
  setPublicPath('/build').
  addEntry('global', './assets/global/js/main.js').
  addEntry('backend', './assets/backend/js/main.js').
  addEntry('frontend', './assets/frontend/js/main.js').
  enableLessLoader().
  enableSassLoader().
  cleanupOutputBeforeBuild().
  enableBuildNotifications().
  autoProvidejQuery().
  copyFiles({ from: 'node_modules/tinymce/skins', to: 'skins/[path][name].[ext]' }).
  autoProvideVariables({
    Tether: 'tether'
  });

module.exports = Encore.getWebpackConfig();
