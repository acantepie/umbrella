export default class Utils {

    static bytes_to_size(bytes) {
        let sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (0 === bytes) return '0';
        let i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }

    // Objectify a formData
    //
    // PHP supports nestsed params with brackets on input name
    // So <input name"foo[bar][0]" value="10"/> must return { foo : { bar : [10] }}
    //
    // Solution used :
    // https://stackoverflow.com/questions/41431322/how-to-convert-formdata-html5-object-to-json (Joyce Babu answer)
    static objectify_formdata(formData) {
        if (!(formData instanceof FormData)) {
            throw new Error('Expected a FormData object')
        }

        const obj = Array.from(formData.entries())
            .reduce((data, [field, value]) => {
                let [_, prefix, keys] = field.match(/^([^[]+)((?:\[[^\]]*\])*)/);

                if (keys) {
                    keys = Array.from(keys.matchAll(/\[([^\]]*)\]/g), m => m[1]);
                    value = Utils.__objectify_formdata_update(data[prefix], keys, value);
                }
                data[prefix] = value;
                return data;
            }, {});

        return obj
    }

    static __objectify_formdata_update(data, keys, value) {
        if (keys.length === 0) {
            // Leaf node
            return value;
        }

        let key = keys.shift();
        if (!key) {
            data = data || [];
            if (Array.isArray(data)) {
                key = data.length;
            }
        }

        // Try converting key to a numeric value
        let index = +key;
        if (!isNaN(index)) {
            // We have a numeric index, make data a numeric array
            // This will not work if this is a associative array
            // with numeric keys
            data = data || [];
            key = index;
        }

        // If none of the above matched, we have an associative array
        data = data || {};

        let val = Utils.__objectify_formdata_update(data[key], keys, value);
        data[key] = val;

        return data;
    }
}
