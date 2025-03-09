import TomSelect from 'tom-select';

export default class UmbrellaSelect extends HTMLSelectElement {

    constructor() {
        super()
    }

    connectedCallback() {
        this.tomSelect = new TomSelect(this, {
            create: true,
        })
    }
}