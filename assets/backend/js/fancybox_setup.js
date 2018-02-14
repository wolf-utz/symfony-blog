module.exports = function ($) {
  $('[data-fancybox]').fancybox({
    buttons: [
      'close'
    ],
    arrows: false,
    infobar: false,
    clickSlide: false,
    clickOutside: false,
    dblclickContent: false,
    dblclickSlide: false,
    dblclickOutside: false,
    touch: false
  })
}