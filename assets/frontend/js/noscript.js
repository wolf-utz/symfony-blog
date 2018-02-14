module.exports = function () {
  var noscripts = document.getElementsByClassName('noscript')
  if (noscripts.length > 0) {
    for (var i = 0; i < noscripts.length; i++) {
      noscripts[i].outerHTML = ''
      delete noscripts[i]
    }
  }
}
