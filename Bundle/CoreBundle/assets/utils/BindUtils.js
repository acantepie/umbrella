import AjaxUtils from "./AjaxUtils";

export default class BindUtils
{
    static bindTooltip($container) { // must be rebinded on dom update
        $container.find('[data-toggle="tooltip"]').tooltip({
            container: $container
        });
    }

    static bindPopover($container) { // must be rebinded on dom update
        $container.find('[data-toggle="popover"]').popover({
            container: $container
        });
    }

    // if you don't want your link was bind : use class no-bind
    static bindXhrElement($container) {
        $container.on('click', '[data-xhr]:not(form):not(.no-bind)', (e) => {
            e.preventDefault();
            const $e = $(e.currentTarget);

            AjaxUtils.get({
                url: $e.data('xhr'),
                xhr_id: $e.data('xhr-id') || null,
                confirm: $e.data('confirm') || false,
                spinner: $e.data('spinner') || false
            });
        });
    }

    // if you don't want your form was bind : use class no-bind
    static bindXhrForm($container) {
        $container.on('submit', 'form[data-xhr]:not(.no-bind)', (e) => {
            e.preventDefault();
            const $e = $(e.currentTarget);

            AjaxUtils.request({
                url: $e.data('xhr'),
                xhr_id: $e.data('xhr-id') || null,
                confirm: $e.data('confirm') || false,
                spinner: $e.data('spinner') || false,
                method: $e.attr('method') || 'post',
                data: $e.serializeFormToFormData(),
            })
        });
    }

    static bindAll($container = null) {

        if (null === $container) {
            $container = $('body');
        }

        BindUtils.bindTooltip($container);
        BindUtils.bindPopover($container);
        BindUtils.bindXhrElement($container);
        BindUtils.bindXhrForm($container);
    }
}