import {Form, FormPayload} from "./admin/columns/form";
import {AdminColumnSettingsInterface} from "./admin/columns/interfaces";
import {EventConstants} from "./constants";
import {initAdminColumnsGlobalBootstrap} from "./helpers/admin-columns";

declare let AdminColumns: AdminColumnSettingsInterface;

initAdminColumnsGlobalBootstrap();

document.addEventListener('DOMContentLoaded', () => {
    let formElement = document.querySelector<HTMLFormElement>('#listscreen_settings');
    if (formElement) {
        AdminColumns.Form = new Form(formElement, AdminColumns.events);
    }

});

AdminColumns.events.addListener(EventConstants.SETTINGS.FORM.LOADED, (form: Form) => {
    document.querySelector('.add_column').addEventListener( 'click', ( e:MouseEvent ) => {
        form.createNewColumn();
    });
});

AdminColumns.events.addListener(EventConstants.SETTINGS.FORM.READY, (form: Form) => {
    console.log( form.getOriginalColumns() );

    let data = new FormData( form.getElement() );
    console.log( new URLSearchParams( new FormData( form.getElement() ).toString() )


});