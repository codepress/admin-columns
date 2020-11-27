import {Column} from "../column";
import {AdminColumnsInterface} from "../../../admincolumns";
import {EventConstants} from "../../../constants";

declare const AdminColumns: AdminColumnsInterface;

export const COLUMN_SWITCH_TYPE_EVENT = 'switch_type';

export type ColumnSwitchPayload = { column: Column, type: string }

export const initTypeSelector = (column: Column) => {
    let select = column.getElement().querySelector<HTMLSelectElement>('select.ac-setting-input_type');
    if (!select) {
        return;
    }

    select.addEventListener('change', () => {
        column.events.emit( COLUMN_SWITCH_TYPE_EVENT, { column: column, type: select.value } as ColumnSwitchPayload )
        //AdminColumns.events.emit(EventConstants.SETTINGS.COLUMN.SWITCH, { column: column, type: select.value });
    });
}
/*

let selector = function( column ) {
	let $ = jQuery;
	column.$el.find( 'select.ac-setting-input_type' ).change( function() {
		column.$el.addClass( 'loading' );
		column.switchToType( $( this ).val() ).always( function() {
			column.$el.removeClass( 'loading' );

			AdminColumns.Form.reindexColumns();
		} ).fail( () => {
			column.showMessage( AC.i18n.errors.loading_column );
		} );
	} );
};

export default selector;*/
