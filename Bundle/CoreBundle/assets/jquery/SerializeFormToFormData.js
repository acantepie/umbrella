/**
 * @see https://stackoverflow.com/questions/5392344/sending-multipart-formdata-with-jquery-ajax
 */
(function($) {
    $.fn.serializeFormToFormData = function() {
        var $obj = $(this);
        /* ADD FILE TO PARAM AJAX */
        var formData = new FormData();
        $.each($obj.find("input[type='file']"), function(i, tag) {
            $.each($(tag)[0].files, function(i, file) {
                formData.append(tag.name, file);
            });
        });
        var params = $obj.serializeArray();
        $.each(params, function (i, val) {
            formData.append(val.name, val.value);
        });
        return formData;
    };
})(jQuery);