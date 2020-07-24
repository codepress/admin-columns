import {addEventListenerLive} from "./helpers/events";

const $ = require("jquery");

declare let ajaxurl: string;

document.addEventListener('DOMContentLoaded', () => {
    addEventListenerLive('click', 'a.hide-review-notice-soft', (e: Event) => {
        e.preventDefault();

        let notice: HTMLElement = (<HTMLElement>e.target).closest('.ac-notice');
        notice.querySelector('.info').remove();
        notice.querySelector<HTMLElement>('.help').style.display = 'block';

        $.post(ajaxurl, JSON.parse(notice.dataset.dismissibleCallback));
    });

    addEventListenerLive('click', 'a.hide-review-notice', (e: Event) => {
        e.preventDefault();

        (<HTMLElement>e.target).closest('.ac-notice').querySelector('.notice-dismiss').dispatchEvent(new Event('click'));
    });
})