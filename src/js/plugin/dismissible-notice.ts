import {addEventListenerLive} from "../helpers/events";

const $ = require("jquery");

declare let ajaxurl: string;

export const dismissNotice = (selector: string) => {
    document.querySelectorAll(selector).forEach((el: HTMLElement) => {
        addEventListenerLive('click', '.ac-notice__dismiss, [data-dismiss], .notice-dismiss', (e: Event) => {
            e.preventDefault();

            let data = el.dataset.dismissibleCallback ? JSON.parse(el.dataset.dismissibleCallback) : null;

            if (data) {
                $.post(ajaxurl, data);
            }
        }, el);
    });
}

export const initDismissibleNotices = () => {
    dismissNotice('.ac-notice');
}