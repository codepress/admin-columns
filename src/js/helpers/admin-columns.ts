import AcServices from "../modules/ac-services";

declare global {
    interface Window {
        AC_SERVICES: AcServices;
        AdminColumns: any;
        ac_load_table: any;
    }
}

export const initAcServices = (): AcServices => {
    if (!window.AC_SERVICES) {
        window.AC_SERVICES = new AcServices();
    }

    return window.AC_SERVICES;
}