import Nanobus from "nanobus";
import {EventConstants} from "../../constants";
import {Column} from "./column";
import {ColumnSettingsResponse, submitColumnSettings} from "./ajax";
import {AxiosResponse} from "axios";
import {fadeIn} from "../../helpers/animations";

export class Form {

    private form: HTMLFormElement
    private events: Nanobus
    private columns: Array<Column>

    constructor(element: HTMLFormElement, events: Nanobus) {
        this.form = element;
        this.events = events;
        this.columns = [];

        this.events.emit(EventConstants.SETTINGS.FORM.LOADED, this);
        // TODO See usage
        // jQuery(document).trigger('AC_Form_Loaded');

        this.init();
    }

    init() {
        this.initColumns();

        if (this.isDisabled()) {
            this.disableFields();
            this.disableColumns();
        }

        this.events.emit(EventConstants.SETTINGS.FORM.READY, this);
    }

    getElement(): HTMLFormElement {
        return this.form;
    }

    placeColumn(column: Column) {
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


    getOriginalColumns(): Array<Column> {
        return this.columns.filter(column => column.isOriginal());
    }

    disableColumns() {
        this.columns.forEach(col => col.disable());
    }

    initColumns() {
        this.getElement().querySelectorAll('.ac-column').forEach((element: HTMLElement) => {
            let column = new Column(element);
            column.init();
            this.events.emit( EventConstants.SETTINGS.COLUMN.INIT, column );
            this.columns.push( column );
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
        this.columns.forEach((column) => {
            column.destroy();
        });
        this.columns = [];
    }

    getSerializedFormData(): string {
        let params = new URLSearchParams(new FormData(this.getElement()) as any)

        return params.toString();
    }

    disableFields() {
        let elements = this.getElement().elements;

        for (let i = 0; i < elements.length; i++) {
            elements[i].setAttribute('readonly', 'readonly');
            elements[i].setAttribute('disabled', 'disabled');
        }
    }

    submitForm() {
        this.events.emit(EventConstants.SETTINGS.FORM.SAVING, this);

        submitColumnSettings(this.getSerializedFormData()).then((response: AxiosResponse<ColumnSettingsResponse>) => {
            if (response.data.success) {
                this.showMessage(response.data.data, 'updated')
            } else if (response.data) {
                //tODO test
                let error: any = response.data as unknown;
                this.showMessage(error.data.message, 'notice notice-warning');
            }

        }).finally(() => {
            this.events.emit(EventConstants.SETTINGS.FORM.SAVED, this);
        });

        // TODO
        /* xhr.fail(function (error) {
             self.showMessage(AC.i18n.errors.save_settings, 'notice notice-warning');
         });*/
    }

    showMessage(message: string, className: string = 'updated') {
        let messageContainer = document.querySelector('.ac-admin__main');
        messageContainer.querySelectorAll('.ac-message').forEach((el: HTMLElement) => el.remove());

        let element: HTMLDivElement = document.createElement('div');
        element.classList.add('ac-message');
        element.classList.add(className);
        element.innerHTML = `<p>${message}</p>`;
        messageContainer.insertAdjacentElement('afterbegin', element);
        fadeIn(element, 600);
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

    return new Column(columnElement);
}

let isInViewport = ($el) => {
    var elementTop = $el.offset().top;
    var elementBottom = elementTop + $el.outerHeight();
    var viewportTop = jQuery(window).scrollTop();
    var viewportBottom = viewportTop + jQuery(window).height();
    return elementBottom > viewportTop && elementTop < viewportBottom;
};