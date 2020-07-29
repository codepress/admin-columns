import Table from "./table/table";
import Tooltip from "./table/tooltips";
import Modals from "./modules/modals";
import ScreenOptionsColumns from "./table/screen-options-columns";
import ToggleBoxLink from "./modules/toggle-box-link";
// @ts-ignore
import $ from 'jquery';
import {LocalizedScriptAC} from "./admincolumns";
import {polyfillCustomEvent} from "./polyfill/custom-event";
import {auto_init_show_more} from "./plugin/show-more";
import {init_actions_tooltips} from "./table/functions";
import {EventConstants} from "./constants";
import {getIdFromTableRow} from "./helpers/table";
import {initAdminColumnsGlobalBootstrap} from "./helpers/admin-columns";
import {initDismissibleNotices} from "./plugin/dismissible-notice";

declare let AC: LocalizedScriptAC

let AdminColumns = initAdminColumnsGlobalBootstrap();
polyfillCustomEvent();

Modals.init();

$(document).ready(() => {
    let table: HTMLTableElement = document.querySelector(AC.table_id);

    if (table.tagName !== 'TABLE') {
        table = table.closest('table');
    }

    if (table && table.tagName === 'TABLE') {
        AdminColumns.Table = new Table(table);
        AdminColumns.ScreenOptionsColumns = new ScreenOptionsColumns(AdminColumns.Table.Columns);
    }

    AdminColumns.Tooltips = new Tooltip();

    document.querySelectorAll('.ac-toggle-box-link').forEach(el => {
        new ToggleBoxLink(el);
    });

    $('.wp-list-table').on('updated', 'tr', function () {
        AdminColumns.Table.addCellClasses();
        auto_init_show_more();
    });

    // TODO use more global event name instead of IE
    $('.wp-list-table td').on('ACP_InlineEditing_After_SetValue', function () {
        auto_init_show_more();
    });

});

AdminColumns.events.addListener(EventConstants.TABLE.READY, (e) => {
    auto_init_show_more();
    init_actions_tooltips();
    initDismissibleNotices();

    e.table.getElement().addEventListener('DOMNodeInserted', (e: Event) => {
        let element: HTMLElement = (<HTMLElement>e.target)
        if (element.tagName !== 'TR' || !element.classList.contains('iedit')) {
            return;
        }

        $(element).trigger('updated', {id: getIdFromTableRow((<HTMLTableRowElement>element)), row: element})
    });
});

window.ac_load_table = function (el: HTMLTableElement) {
    AdminColumns.Table = new Table(el);
};
