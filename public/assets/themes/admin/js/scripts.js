$(document).ajaxComplete(function () {

    if ($(".ckeditor").length) {
        CKEDITOR.replaceClass = 'ckeditor';
    }
    $('.select2-normal').select2();
    $('.select2-normal.tags').select2({
        tags: []
    });

});

// autocomplete question title
var questionTitleSelect = function (url) {
    var cache = {}, ft = true;
    $("#create-question-title").select2({
        tags: true,
        ajax: {
            url: url,
            data: function (params) {
                var el = $(this);
                if (el.val() && ft) {
                    $(".select2-search__field").val(el.val());
                    ft = false;
                }
                var query = {
                    q: params.term || $(this).val()
                };
                // Query parameters will be ?search=[term]&type=public
                return query;
            },
            processResults: function (resp) {
                // Tranforms the top-level key of the response object from 'items' to 'results'
                return {
                    results: _.uniqBy($.map(resp.data, function (obj) {
                        obj.id = obj.text || obj.title; // replace name with the property used for the text
                        obj.text = obj.text || obj.title; // replace name with the property used for the text
                        return obj;
                    }), "text")
                };
            }
        }
    }).on("select2:closing", function () {
        // set first time selection to true
        ft = true;
    });
}
