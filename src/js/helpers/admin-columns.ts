import {AdminColumnsInterface} from "../admincolumns";

let nanobus = require('nanobus');

declare let AdminColumns: AdminColumnsInterface
declare global {
    interface Window {
        AdminColumns: any;
        ac_load_table: any;
    }
}

export const initAdminColumnsGlobalBootstrap = (): AdminColumnsInterface => {
    window.AdminColumns = window.AdminColumns || {};
    AdminColumns.events = nanobus();

    return AdminColumns;
}