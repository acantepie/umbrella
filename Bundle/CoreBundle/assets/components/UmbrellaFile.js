import Utils from "../utils/Utils";

export default class UmbrellaFile extends HTMLElement {

    constructor($view) {
        super();
        this.$view = $(this);

        this.$fileInput = this.$view.find('.js-file-input');
        this.$deleteCheckbox = this.$view.find('.js-delete-checkbox');

        this.$fileInfo = this.$view.find('.file-info');
        this.$browseAction = this.$view.find('.js-browse-action');
        this.$deleteAction = this.$view.find('.js-delete-action');

        this.initialFileInfo = this.$fileInfo.html();
    }

    connectedCallback() {
        this.$browseAction.on('click', () => {
            this.$fileInput.click();
        });

        this.$fileInput.on('change', () => {
            this.renderFileInfo();
        });

        this.$deleteAction.on('click', () => {
            if (this.isDeleted()) {
                this.undoDelete();
            } else {
                this.delete();
            }
        });
    }


    renderFileInfo() {
        let files = this.$fileInput[0].files;

        if (files.length > 0) {
            let file = files[0];
            this.$fileInfo.text(file.name + ' - ' + Utils.bytes_to_size(file.size));
        } else { // cancel
            this.resetFileInfo();
        }
    }

    isDeleted() {
        return this.$deleteCheckbox.prop('checked');
    }

    delete() {
        this.$deleteCheckbox.prop('checked', true);
        this.$view.addClass('deleted');
        this.$browseAction.prop('disabled', true);
        this.clearInputFile();
    }

    undoDelete() {
        this.$deleteCheckbox.prop('checked', false);
        this.$view.removeClass('deleted');
        this.$browseAction.prop('disabled', false);
        this.resetFileInfo();
    }

    resetFileInfo() {
        this.$fileInfo.html(this.initialFileInfo);
    }

    clearInputFile() {
        this.$fileInput.replaceWith(this.$fileInput.val('').clone(true));
        this.$fileInput = this.$view.find('.js-file-input');
    }

}