module.exports = function ($) {
  $(document).ready(function () {
    var callFancbox = function () {
      $('.reply').fancybox({
        beforeLoad: function (instance, slide) {
          var parent = slide.opts.$orig.data('parent')
          var target = slide.opts.$orig.data('target')
          var $formWrapper = $('#reply-form')
          $formWrapper.data('parent', parent)
          $formWrapper.data('target', target)
          $('#reply-comment').html($(target)[0].outerHTML)
        }
      })
    }
    callFancbox()
    // Handle form ajax.
    $('#reply-form').find('form').on('submit', function (e) {
      e.preventDefault()
      $.fancybox.close()
      var $form = $(this)
      var url = $form.attr('action') + '/' + $('#reply-form').data('parent')
      var data = $form.serialize()
      $.post({
        'url': url,
        'data': data
      }).done(function (result) {
        var $target = $($('#reply-form').data('target'))
        $('html, body').animate({
          scrollTop: $target.offset().top
        }, 500)
        $target.parent().append(result)
        callFancbox()
      })
    })
  })
}
