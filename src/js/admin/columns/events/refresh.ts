import {Column} from "../column";

export const initColumnRefresh = (column: Column) => {
    column.getElement().querySelectorAll<HTMLElement>('[data-refresh="column"]').forEach(element => {
        element.addEventListener('change', () => {
            console.log('refres');
            column.refresh();
        });
    });
}


let refresh = function (column) {
    let $ = jQuery;

    column.$el.find('[data-refresh="column"]').on('change', function () {
        // Allow plugins to hook into this event

        column.$el.addClass('loading');

        setTimeout(function () {
            column.refresh().always(function () {
                column.$el.removeClass('loading');
            }).fail(() => {
                column.showMessage(AC.i18n.errors.loading_column);
            });
        }, 200);

    });
};

export default refresh;