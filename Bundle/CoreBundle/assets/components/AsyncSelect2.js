import mustache from 'mustache';
import Utils from "../utils/Utils";

// don't use this.options on HTMLSelectElement elements
export default class AsyncSelect2 extends HTMLSelectElement {

    constructor() {
        super();

        mustache.tags = [ '[[', ']]' ];

        this.$view = $(this);

        let data_options = this.$view.data('options');

        this._options = data_options ? JSON.parse(Utils.decode_html(data_options)) : {};
        this.s2_options = this._options['select2'] ? this._options['select2'] : {};

        let request;
        let scroll = this._options['scroll'] || false;
        let prefix = Date.now();
        let cache = [];

        this.s2_options['ajax'] = {
            url: this._options['ajax_url'] || null,
            delay: this._options['ajax_delay'] || 250,
            transport: (params, success, failure) => {
                // is caching enabled?
                if (this._options['ajax_cache']) {
                    // try to make the key unique to make it less likely for a page+q to match a real query
                    var key = prefix + ' page:' + (params.data.page || 1) + ' ' + params.data.q;
                    var cacheTimeout = this._options['ajax_cache_timeout'];
                    // no cache entry for 'term' or the cache has timed out?
                    if (typeof cache[key] === 'undefined' || (cacheTimeout && Date.now() >= cache[key].time)) {
                        return $.ajax(params).fail(failure).done((data) => {
                            cache[key] = {
                                data: data,
                                time: cacheTimeout ? Date.now() + cacheTimeout : null
                            };
                            success(data);
                        });
                    } else {
                        // return cached data with no ajax request
                        success(cache[key].data);
                    }
                } else {
                    // no caching enabled. just do the ajax request
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax(params).fail(failure).done(success).always(() => {
                        request = undefined;
                    });

                    return request;
                }
            },
            data: (params) => {
                let ret = {
                    'q': params.term,
                    'field_name': this._options['name']
                };

                // only send the 'page' parameter if scrolling is enabled
                if (scroll) {
                    ret['page'] = params.page || 1;
                }

                return ret;
            },
            processResults: (data, params) => {
                let results, more = false,
                    response = {};
                params.page = params.page || 1;

                if ($.isArray(data)) {
                    results = data;
                } else if (typeof data === 'object') {
                    // assume remote result was proper object
                    results = data.results;
                    more = data.more;
                } else {
                    // failsafe
                    results = [];
                }

                if (scroll) {
                    response.pagination = {more: more};
                }
                response.results = results;

                return response;
            }
        };


        if (this._options['render_html']) {
            this.s2_options['escapeMarkup'] = (text) => {
                return text;
            };
            this.s2_options['templateResult'] = (option) => {
                return option.html ? option.html : option.text;
            };
            this.s2_options['templateSelection'] = (option) => {
                return option.text;
            };
        } else {
            // templating ?
            let mustacheTemplate = null;

            if (this._options['template_selector']) {
                const $template = $(this._options['template_selector']);
                if ($template.length === 0) {
                    console.error("No template found with selector " + this._options['template_selector']);
                } else {
                    mustacheTemplate = $template.html();
                }
            }

            if (this._options['template_html']) {
                mustacheTemplate = this._options['template_html'];
            }

            if (mustacheTemplate) {
                this.s2_options['templateResult'] = (state) => {
                    if (!state.id) {
                        return state.text;
                    }

                    return $('<span>' + mustache.render(mustacheTemplate, state) + '</span>');

                };
            }
        }
    }

    connectedCallback() {
        this.$view.select2(this.s2_options);
    }

    disconnectedCallback() {
        this.$view.select2('destroy');
    }
}