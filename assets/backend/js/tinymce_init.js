module.exports = function (tinymce) {
  tinymce.init({
    selector: '.rte',
    plugins: ['paste', 'link']
  })
}
