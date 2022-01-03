export default class Utils {

    static bytes_to_size(bytes) {
        let sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (0 === bytes) return '0';
        let i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }

    // https://stackoverflow.com/questions/41431322/how-to-convert-formdata-html5-object-to-json
    static objectify_formdata(formData) {

        if (!formData instanceof FormData) {
            throw new Error('Expected a FormData object')
        }

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
}
