export default class Utils {

    static bytes_to_size(bytes) {
        let sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (bytes == 0) return '0';
        let i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }

    static decode_html(html) {
        let txt = document.createElement('textarea');
        txt.innerHTML = html;
        return txt.value;
    }

    // https://stackoverflow.com/questions/41431322/how-to-convert-formdata-html5-object-to-json
    static objectify_formdata(formData) {
        let object = {};
        formData.forEach((value, key) => {
            // Reflect.has in favor of: object.hasOwnProperty(key)
            if (!Reflect.has(object, key)) {
                object[key] = value;
                return;
            }
            if (!Array.isArray(object[key])) {
                object[key] = [object[key]];
            }
            object[key].push(value);
        });

        return object;
    }

    static create_formdata_with_files(form)
    {
        let formData = new FormData(form);

        // rewrite this part on vanilla js
        let $form = $(form);

        $.each($form.find('input[type=file]'), (i, tag) => {
            $.each($(tag)[0].files, (i, file) => {
                formData.append(tag.name, file);
            });
        });

        return formData;
    }
}