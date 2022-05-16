import {addEventListenerLive} from "./helpers/events";

const $ = require("jquery");

declare let ajaxurl: string;

document.addEventListener('DOMContentLoaded', () => {
    addEventListenerLive('click', 'a.hide-review-notice-soft', (e: Event) => {
        e.preventDefault();

        let notice: HTMLElement | null = (<HTMLElement>e.target).closest('.ac-notice');
        if (notice) {
            notice.querySelector('.info')?.remove();

            let help = notice?.querySelector<HTMLElement>('.help');
            if (help) {
                help.style.display = 'block';
            }
            $.post(ajaxurl, JSON.parse(notice.dataset.dismissibleCallback || ''));
        }

    });

    addEventListenerLive('click', 'a.hide-review-notice', (e: Event) => {
        e.preventDefault();

        (e.target as HTMLElement).closest('.ac-notice')?.querySelector('.notice-dismiss')?.dispatchEvent(new Event('click'));
    });
})