import {addEventListenerLive} from "../helpers/events";

import $ from "jquery";

declare let ajaxurl: string;

export const postDismissCallback = (notice: HTMLElement) => {
    let data = notice.dataset.dismissibleCallback ? JSON.parse(notice.dataset.dismissibleCallback) : null;

    if (data) {
        $.post(ajaxurl, data);
    }
}

export const dismissNotice = (selector: string) => {
    document.querySelectorAll<HTMLElement>(selector).forEach((el) => {
        addEventListenerLive('click', '[data-dismiss], .notice-dismiss', function (this: HTMLElement, e: Event) {
            e.preventDefault();

            postDismissCallback(el);

            // WordPress removes the notice itself when its own dismiss button is used
            if (!this.classList.contains('notice-dismiss')) {
                el.remove();
            }
        }, el);
    });
}

export const initDismissibleNotices = () => {
    dismissNotice('.ac-notice');
}