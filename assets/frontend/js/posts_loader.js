module.exports = function ($) {
  var $newerPostsBtn = $('#newer-posts-btn')
  var $olderPostsBtn = $('#older-posts-btn')
  var $ajaxResultTarget = $('#posts-wrapper')
  var $loadingSpinner = $('#loading-spinner')
  $('#ajax-post-loader-controls').show()

  /**
   *
   * @param url
   * @param page
   * @param maxPages
   */
  var getPostsAjax = function (url, page, maxPages) {
    var animationDuration = 500
    $.get({
      url: url + '/' + page,
      success: function (result) {
        $loadingSpinner.show()
        $ajaxResultTarget.animate({opacity: 0}, animationDuration, function () {
          $ajaxResultTarget.html(result)
          refreshCurrentPageData(page)
          $loadingSpinner.hide()
          handleButtonVisibility(page, maxPages)
          $ajaxResultTarget.animate({opacity: 1}, animationDuration, function () {
            scrollTo('posts-wrapper')
          })
        })
      }
    })
  }

  /**
   *
   * @param page
   */
  var refreshCurrentPageData = function (page) {
    $newerPostsBtn.data('current-page', page)
    $olderPostsBtn.data('current-page', page)

  }

  /**
   *
   * @param hash
   */
  var scrollTo = function (hash) {
    location.hash = '#' + hash
  }
  /**
   *
   * @param page
   * @param maxPages
   */
  var handleButtonVisibility = function (page, maxPages) {
    if (page <= 1) {
      $newerPostsBtn.hide()
    } else {
      $newerPostsBtn.show()
    }
    if (page >= maxPages) {
      $olderPostsBtn.hide()
    } else {
      $olderPostsBtn.show()
    }
  }

  //
  $newerPostsBtn.on('click', function () {
    $btn = $(this)
    var maxPages = $btn.data('max-pages')
    var currentPage = $btn.data('current-page')
    var url = $btn.data('ajax-url')
    var nextpage = currentPage > 1 ? currentPage - 1 : 1
    getPostsAjax(url, nextpage, maxPages)
  })
  //
  $olderPostsBtn.on('click', function () {
    $btn = $(this)
    var maxPages = $btn.data('max-pages')
    var currentPage = $btn.data('current-page')
    var url = $btn.data('ajax-url')
    var nextpage = currentPage < maxPages ? currentPage + 1 : maxPages
    getPostsAjax(url, nextpage, maxPages)
  })
}
