// @ts-ignore
import $ from 'jquery';

const STATES = {
    CLOSED: 'closed',
    OPEN: 'open'
};


export class Column {
    private element: HTMLElement
    private settings: Array<any>
    private name: string
    private type: string
    private state: string
    private original: boolean
    private disabled: boolean

    constructor(element: HTMLElement) {
        this.element = element;
        this.settings = [];
        this.state = STATES.CLOSED;
        this.name = element.dataset.columnName;
        this.type = element.dataset.type;
        this.original = element.dataset.original === '1';
        this.disabled = element.classList.contains('disabled');
    }

    getName() {
        return this.name;
    }

    getType() {
        return this.type;
    }

    isOriginal() {
        return this.original;
    }

    getElement() {
        return this.element;
    }

    isDisabled(): boolean {
        return this.element.classList.contains('disabled');
    }

    disable() {
        this.element.classList.add('disabled');

        return this;
    }

    enable() {
        this.element.classList.remove('disabled');

        return this;
    }

    init() {

    }

    initNewInstance() {
        let temp_column_name = '_new_column_' + AC.Column.getNewIncementalName();
        let original_column_name = this.name;

        this.$el.find('input, select, label').each(function (i, v) {
            let $input = jQuery(v);

            // name attributes
            if ($input.attr('name')) {
                $input.attr('name', $input.attr('name').replace(`columns[${original_column_name}]`, `columns[${temp_column_name}]`));
            }

            // id attributes
            if ($input.attr('id')) {
                $input.attr('id', $input.attr('id').replace(`-${original_column_name}-`, `-${temp_column_name}-`));
            }

        });

        this.name = temp_column_name;

        AC.incremental_column_name++;

        return this;
    }

    /**
     *
     * @returns {Column}
     */
    bindEvents() {
        let column = this;
        column.$el.data('column', column);

        Object.keys(AC.Column.events).forEach(function (key) {
            if (!column.isBound(key)) {
                AC.Column.events[key](column);
                column.bind(key);
            }
        });

        this.bindSettings();


        return this;
    }

    bindSettings() {
        let column = this;

        Object.keys(AC.Column.settings).forEach(function (key) {
            if (!column.isBound(key)) {
                AC.Column.settings[key](column);
                column.bind(key);
            }
        });
    }

    destroy() {
        this.element.remove();
    }

    remove(duration = 350) {
        let self = this;

        this.$el.addClass('deleting').animate({opacity: 0, height: 0}, duration, function () {
            self.destroy();
        });
    }

    getState() {
        return this.state;
    }

    toggle(duration = 150) {
        this.getState() === STATES.OPEN
            ? this.close(duration)
            : this.open(duration);
    }

    close(duration = 0) {
        this.getElement().classList.remove('opened');
        $(this.getElement()).find('.ac-column-body').slideUp(duration);
        this.state = STATES.CLOSED;
    }

    open(duration = 0) {
        this.getElement().classList.add('opened');
        $(this.getElement()).find('.ac-column-body').slideDown(duration);
        this.state = STATES.OPEN;
    }

    showMessage(message) {
        this.$el.find('.ac-column-setting--type .msg').html(message).show();
    }

    switchToType(type: string) {
        //Todo
        return;
        //let self = this;

        /*return jQuery.ajax({
            url: ajaxurl,
            method: 'post',
            dataType: 'json',
            data: {
                action: 'ac-columns',
                id: 'select',
                type: type,
                data: AdminColumns.Form.serialize(),
                current_original_columns: AdminColumns.Form.originalColumns(),
                original_columns: AC.original_columns,
                _ajax_nonce: AC._ajax_nonce,
            },
            success: function (response) {
                if (true === response.success) {
                    let column = jQuery(response.data);

                    self.$el.replaceWith(column);
                    self.$el = column;
                    self.el = column[0];
                    self._type = type;
                    self.initNewInstance();
                    self.bindEvents();
                    self.open();

                    jQuery(document).trigger('AC_Column_Change', [self]);
                } else {
                    self.showMessage(response.data.error)
                }
            }
        });*/
    }

    refresh() {
        //todo
        return;
        /* let self = this;
         let data = this.$el.find(':input').serializeArray();
         let request_data = {
             action: 'ac-columns',
             id: 'refresh',
             _ajax_nonce: AC._ajax_nonce,
             data: AdminColumns.Form.serialize(),
             column_name: this.name,
             original_columns: AC.original_columns
         };

         jQuery.each(request_data, function (name, value) {
             data.push({
                 name: name,
                 value: value
             });
         });

         return jQuery.ajax({
             type: 'post',
             url: ajaxurl,
             data: data,

             success: function (response) {
                 if (true === response.success) {
                     let column = jQuery(response.data);

                     self.$el.replaceWith(column);
                     self.$el = column;
                     self.el = column[0];
                     self.bindEvents();

                     if (self.getState() === STATES.OPEN) {
                         self.open();
                     }

                     jQuery(document).trigger('AC_Column_Refresh', [self]);
                 }
             }

         });*/
    }

    /**
     * @returns {Column}
     */
    create() {
        // TODO move out ckass
        /*this.initNewInstance();
        this.bindEvents();

        jQuery(document).trigger('AC_Column_Created', [this]);
        return this;*/
    }


    clone() {
        // TODO move out class
        /*let $clone = this.$el.clone();
        $clone.data('column-name', this.$el.data('column-name'));

        let clone = new Column($clone);

        clone.initNewInstance();
        clone.bindEvents();

        return clone;*/
    }
}
