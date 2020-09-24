/**
 * Created by Zzuutt on 27/04/2016.
 */
(function ($) {
    $(document).ready(function () {
        if(typeof default_template_id != 'undefined') {
            var comparator = new Array(new Array(), new Array());
            var getComparator = docCookies.getItem(cookie_name_comparator);
            if (getComparator) {
                comparator = JSON.parse(getComparator);
                if(typeof comparator[default_template_id] == 'undefined'){
                    comparator[default_template_id] = [];
                }
                updateComparator(comparator[default_template_id].toString(), default_action_url);
            }
            else {
                updateComparator('', default_action_url);
                comparator[default_template_id] = [];
            }
        }
        $(document).on('click', '.btn-comparator-add', function () {
            var url_action = $(this).data('action');
            var product_id = $(this).data('id');
            var template_id = $(this).data('template');
            if($.inArray(product_id, comparator[template_id]) == -1) {
                comparator[template_id].push(product_id);
                docCookies.setItem(cookie_name_comparator, JSON.stringify(comparator), '', '/');
                updateComparator(comparator[template_id].toString(), url_action);
            }
            return false;
        });
        $(document).on('click', '.btn-comparator-delete', function () {
            var url_action = $(this).data('action');
            var product_id = $(this).data('id');
            var template_id = $(this).data('template');
            if($.inArray(product_id, comparator[template_id]) !== -1) {
                comparator[template_id].splice($.inArray(product_id,comparator[template_id]),1);
                docCookies.setItem(cookie_name_comparator, JSON.stringify(comparator));
                updateComparator(comparator[template_id].toString(), url_action);
            }
            return false;
        });
        $(document).on('click', '.btn-comparator-delete-all', function () {
            updateComparator('', default_action_url);
            comparator[default_template_id] = [];
            return false;
        });
    });
    function enableDisableButtonComparator(items)
    {
        var buttonId = items.split(',');
        $(".btn-comparator-add").prop('disabled',false);
        $(".btn-comparator-add[data-id='"+ buttonId.join("'],[data-id='") + "']").prop('disabled',true);
    }
    function updateComparator(items, url)
    {
        $(".comparator-title").addClass('add-item');
        $.ajax({
            type: "POST", data: {comparator: items}, url: url,
            success: function (data) {
                enableDisableButtonComparator(items);
                $("#list-items-comparator").html(data);
                $(".comparator-title").removeClass('add-item');
            },
            error: function (e) {
                console.log('Error.', e);
            }
        });
    }
})(jQuery);