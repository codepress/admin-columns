import {AdminColumnsInterface} from "../admincolumns";
import Modals from "../modules/modals";

let nanobus = require('nanobus');

declare let AdminColumns: AdminColumnsInterface
declare global {
    interface Window {
        AdminColumns: any;
        ac_load_table: any;
    }
}

export const initAdminColumnsGlobalBootstrap = (): AdminColumnsInterface => {
    if( window.AdminColumns ){
        return window.AdminColumns;
    }

    window.AdminColumns = window.AdminColumns || {};
    AdminColumns.events = nanobus();
    AdminColumns.Modals = new Modals();

    return AdminColumns;
}