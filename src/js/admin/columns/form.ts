import Nanobus from "nanobus";
import {EventConstants} from "../../constants";
import {ColumnSettings} from "./column";
import {ColumnSettingsResponse, submitColumnSettings} from "./ajax";
import {AxiosResponse} from "axios";


export type FormPayload = { form: Form }

export class Form {

    private form: HTMLFormElement
    private events: Nanobus
    private columns: Array<ColumnSettings.Column>

    constructor(element: HTMLFormElement, events: Nanobus) {
        this.form = element;
        this.events = events;
        this.columns = [];

        //this.$container = jQuery('#cpac .ac-admin');


        this.events.emit(EventConstants.SETTINGS.FORM.LOADED, this);
        // TODO See usage
        // jQuery(document).trigger('AC_Form_Loaded');

        this.init();
    }

    getElement(): HTMLFormElement {
        return this.form;
    }

    placeColumn(column: ColumnSettings.Column) {
        this.getElement().querySelector('.ac-columns').append(column.getElement())
    }

    createNewColumn() {
        let column = createColumnFromTemplate();
        this.columns.push(column);

        this.placeColumn(column);
    }


    isDisabled() {
        return this.form.classList.contains('-disabled');
    }

    init() {
        this.initColumns();
        //this.bindFormEvents();
        //this.bindOrdering();

        if (this.isDisabled()) {
            this.disableFields();
        }

        this.events.emit(EventConstants.SETTINGS.FORM.READY, this);
        //jQuery(document).trigger('AC_Form_Ready', this);
    }

    bindOrdering() {
        return;
        if (this.$form.hasClass('ui-sortable')) {
            this.$form.sortable('refresh');
        } else {
            this.$form.sortable({
                items: '.ac-column',
                handle: '.column_sort'
            });
        }

    }

    getOriginalColumns(): Array<ColumnSettings.Column> {
        return this.columns.filter( column => column.isOriginal() );
    }

    // Todo remove
    validateForm() {
        return true;
    }

    bindFormEvents() {

        return;
        let self = this;
        let $buttons = jQuery('.sidebox a.submit, .column-footer a.submit');

        $buttons.on('click', function () {
            if (!self.validateForm()) {
                return;
            }
            $buttons.attr('disabled', 'disabled');
            self.$container.addClass('saving');
            self.submitForm().always(function () {
                $buttons.removeAttr('disabled', 'disabled');
                self.$container.removeClass('saving');
            })
        });

        self.$container.find('.add_column').on('click', function () {
            self.addColumn();
        });

        let $boxes = jQuery('#cpac .ac-boxes');
        if ($boxes.hasClass('disabled')) {
            $boxes.find('.ac-column').each(function (i, col) {
                jQuery(col).data('column').disable();
                jQuery(col).find('input, select').prop('disabled', true);
            });
        }

        /*jQuery('a[data-clear-columns]').on('click', function () {
            self.resetColumns();
        });*/
    }

    initColumns() {
        this.getElement().querySelectorAll('.ac-column').forEach((element: HTMLElement) => {
            this.columns.push(new ColumnSettings.Column(element));
        });
    }

    reindexColumns() {
        let self = this;
        self.columns = {};

        this.$form.find('.ac-column').each(function () {
            let column = jQuery(this).data('column');

            self.columns[column.name] = column;
        });
    }

    resetColumns() {
        Object.keys(this.columns).forEach((key) => {
            let column = this.columns[key];

            column.destroy();
        });

    }

    serialize() {
        return this.$form.serialize();
    }

    disableFields() {
        let form = document.querySelector(this.form);
        if (!form) {
            return;
        }

        let elements = form.elements;

        for (let i = 0; i < elements.length; i++) {
            elements[i].readOnly = true;
            elements[i].setAttribute('disabled', true);
        }
    }

    enableFields() {

    }

    submitForm() {
        let data: URLSearchParams = new URLSearchParams( new FormData( this.getElement() );

        submitColumnSettings( data.toString() ).then( ( response: AxiosResponse<ColumnSettingsResponse> ) => {
            response.data.success
        });
        return;


        return;
        let self = this;

        let xhr = jQuery.post(ajaxurl, {
                action: 'ac-columns',
                id: 'save',
                _ajax_nonce: AC._ajax_nonce,
                data: this.serialize(),
            },

            function (response) {
                if (response) {
                    if (response.success) {
                        self.showMessage(response.data, 'updated');

                        self.$container.addClass('stored');
                    }

                    // Error message
                    else if (response.data) {
                        self.showMessage(response.data.message, 'notice notice-warning');
                    }
                }

            }, 'json');

        // No JSON
        xhr.fail(function (error) {
            self.showMessage(AC.i18n.errors.save_settings, 'notice notice-warning');
        });

        jQuery(document).trigger('AC_Form_AfterUpdate', [self.$container]);

        return xhr;
    }

    showMessage(message, attr_class = 'updated') {
        let $msg = jQuery('<div class="ac-message hidden ' + attr_class + '"><p>' + message + '</p></div>');

        this.$container.find('.ac-message').stop().remove();
        this.$container.find('.ac-admin__main').prepend($msg);

        $msg.slideDown();
    }

    cloneColumn($el) {
        return this._addColumnToForm(new Column($el).clone(), $el.hasClass('opened'), $el);
    }


    removeColumn(name) {
        if (this.columns[name]) {
            this.columns[name].remove();
            delete this.columns[name];
        }
    }

    _addColumnToForm(column, open = true, $after = null) {
        this.columns[column.name] = column;

        if ($after) {
            column.$el.insertAfter($after);
        } else {
            this.$column_container.append(column.$el);
        }

        if (open) {
            column.open();
        }

        column.$el.hide().slideDown();

        jQuery(document).trigger('AC_Column_Added', [column]);

        if (!isInViewport(column.$el)) {
            jQuery('html, body').animate({scrollTop: column.$el.offset().top - 58}, 300);
        }

        return column;
    }

}


const createColumnFromTemplate = () => {
    let columnElement = document.querySelector('#add-new-column-template .ac-column').cloneNode(true) as HTMLElement;

    return new ColumnSettings.Column(columnElement);
}

let isInViewport = ($el) => {
    var elementTop = $el.offset().top;
    var elementBottom = elementTop + $el.outerHeight();
    var viewportTop = jQuery(window).scrollTop();
    var viewportBottom = viewportTop + jQuery(window).height();
    return elementBottom > viewportTop && elementTop < viewportBottom;
};