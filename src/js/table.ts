import Table, {TableEventPayload} from "./table/table";
import Tooltips from "./modules/tooltips";
import ScreenOptionsColumns from "./table/screen-options-columns";

import {auto_init_show_more} from "./plugin/show-more";
import {EventConstants} from "./constants";
import {getIdFromTableRow, resolveTableBySelector} from "./helpers/table";
import {initAcServices} from "./helpers/admin-columns";
import {initPointers} from "./modules/ac-pointer";
import ValueModals from "./modules/value-modals";
import {initAcTooltips, Tooltip} from "./plugin/tooltip";
import {ValueModalItemCollection} from "./types/admin-columns";
import JsonViewer from "./modules/json-viewer";
import {getTableConfig, getTableTranslation} from "./table/utils/global";
import {ActionButton} from "./table/actions";

let AC_SERVICES = initAcServices();
const tableConfig = getTableConfig();
const i18n = getTableTranslation();

AC_SERVICES.registerService('tooltips', initAcTooltips);
AC_SERVICES.registerService('initPointers', initPointers);

type TableRowCallback = (row: HTMLTableRowElement) => void;

const observeTableRows = (
    containerElement: Element,
    filterFunction: (node: HTMLElement) => boolean,
    rowCallback: TableRowCallback
) => {
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    const element = node as HTMLElement;
                    if (element.tagName === 'TR' && filterFunction(element)) {
                        rowCallback(element as HTMLTableRowElement);
                    }
                }
            });
        });
    });

    observer.observe(containerElement, {
        childList: true,
        subtree: true
    });
}


document.addEventListener('DOMContentLoaded', () => {

    const table = resolveTableBySelector(tableConfig.table_id);

    initPointers();

    // Initialize the table
    if (table) {
        const TableModule = new Table(table, AC_SERVICES).init();
        AC_SERVICES.registerService('Table', TableModule);
        AC_SERVICES.registerService('ScreenOptionsColumns', new ScreenOptionsColumns(TableModule.Columns));
    }

    // Register services
    AC_SERVICES.registerService('Tooltips', new Tooltips());

    if (table) {
        observeTableRows(
            table,
            () => true, // apply to all <tr>
            () => {
                AC_SERVICES.getService<Table>('Table')!.addCellClasses();
                auto_init_show_more();
            }
        );
    }
});

AC_SERVICES.addListener(EventConstants.TABLE.READY, (event: TableEventPayload) => {
    auto_init_show_more();

    // Tooltip setup
    document.querySelectorAll('.cpac_use_icons').forEach((el: HTMLElement) => {
        el?.parentElement?.querySelectorAll('.row-actions a').forEach((el: HTMLElement) => {
            new Tooltip(el, el.innerText);
        });
    });

    if( tableConfig.show_edit_columns ){
        const editColumnsButtons = ActionButton
            .createWithMarkup( 'edit-columns', i18n.edit_columns );
        editColumnsButtons.getElement().setAttribute('href',tableConfig.edit_columns_url);
        event.table.Actions?.addButton( editColumnsButtons,0 )
    }

    // Row observation
    observeTableRows(
        event.table.getElement(),
        (node) => node.classList.contains('iedit'),
        (row) => {
            row.dispatchEvent(
                new CustomEvent('updated', {
                    detail: {
                        id: getIdFromTableRow(row),
                        row
                    }
                })
            );
        }
    );

    event.table.Cells.getAll().forEach(cell => {
        cell.events.addListener('setValue', () => {
            auto_init_show_more();
        });
    });

    // Modal creation
    let items: { [key: string]: ValueModalItemCollection } = {};

    event.table.getElement().querySelectorAll<HTMLElement>('td [data-modal-value]').forEach(link => {
        let cell = event.table.Cells.getByElement(link.closest('td') ?? document.createElement('td'));

        if (cell) {
            if (!items.hasOwnProperty(cell.getName())) {
                items[cell.getName()] = [];
            }

            let params = link.dataset.modalParams ?? null;

            items[cell.getName()].push({
                element: link,
                editLink: link.dataset.modalEditLink ?? '',
                downloadLink: link.dataset.modalDownloadLink ?? '',
                viewLink: link.dataset.modalViewLink ?? '',
                title: link.dataset.modalTitle ?? null,
                view: link.dataset.view ?? '',
                columnName: cell.getName(),
                objectId: cell.getObjectID(),
                params: params ? JSON.parse(params) : {}
            });
        }
    });

    Object.keys(items).forEach(i => new ValueModals(items[i]))

    // JSON viewer setup
    document.querySelectorAll<HTMLElement>('[data-component="ac-json"]').forEach(el => {
        new JsonViewer(el);
    })

    event.table.Actions?.refresh();
});