import WPNotice from "./notice";
// @ts-ignore
import $ from 'jquery';
import {LocalizedAcAddonSettings, LocalizedAcAddonsi18n} from "../types/admin-columns";
import AddonDownloader from "./addon-downloader";

declare let AC: LocalizedAcAddonSettings
declare let ACi18n: LocalizedAcAddonsi18n

export class AddonDownload {

    element: HTMLElement
    slug: string
    loadingState: boolean
    downloader: AddonDownloader

    constructor(el: HTMLElement, slug: string) {
        this.downloader = new AddonDownloader(slug, AC.is_network_admin, AC._ajax_nonce)
        this.element = el;
        this.slug = slug;
        this.loadingState = false;

        this.initEvents();
    }

    getButton(): HTMLAnchorElement {
        return this.element.querySelector<HTMLAnchorElement>('[data-install],[data-activate]');
    }

    setLoadingState(): void {
        const button = this.getButton();

        if (button) {
            button.classList.add('button-disabled');
        }

        this.element.querySelectorAll('.ac-addon__actions').forEach(el => el.insertAdjacentHTML('beforeend', '<span class="spinner" style="visibility: visible; transform: translateY(-3px);"></span>'))
        this.loadingState = true;
    }

    setLoadingFinished(): void {
        this.loadingState = false;
        const button = this.getButton();

        this.element.querySelectorAll('.spinner').forEach(el => el.remove());

        if (button) {
            button.classList.remove('button-disabled');
        }
    }

    initEvents() {
        const button = this.getButton();

        if (button && ! button.classList.contains('-disabled')) {
            button.addEventListener('click', e => {
                e.preventDefault();

                if (this.loadingState) {
                    return;
                }

                this.setLoadingState();
                this.download();
            });
        }
    }

    getFooterElement(): HTMLElement {
        return this.element.querySelector('.ac-addon__actions');
    }

    success(status: string) {
        const title = this.element.querySelector('h3');
        const notice = new WPNotice();

        let message = ACi18n.plugin_installed.replace('%s', `<strong>${title.innerHTML}</strong>`);

        notice.setMessage(`<p>${message}</p>`)
            .makeDismissable()
            .addClass('updated');

        this.addNotice(notice);
        this.setLoadingFinished();
        this.getFooterElement().innerHTML = `<div class="ac-addon__state"><span class="-green dashicons dashicons-yes"></span><span class="ac-addon__state__label">${status}</span></div>`
    }

    addNotice(notice: WPNotice) {
        let container = document.querySelector('.ac-addons-groups');

        container.parentElement.insertBefore(notice.render(), container);
    }

    static scrollToTop(ms: number) {
        $('html, body').animate({
            scrollTop: 0
        }, ms);
    }

    failure(message: string) {
        const title = this.element.querySelector('h3');
        const notice = new WPNotice();

        notice.setMessage(`<p><strong>${title.innerHTML}</strong>: ${message}</p>`)
            .makeDismissable()
            .addClass('notice-error');

        this.addNotice(notice);
        this.setLoadingFinished();

        AddonDownload.scrollToTop(200);
    }

    download() {
        this.downloader.download().then((response) => {

            switch (response.data.success) {
                case true:
                    this.success(response.data.data.status);

                    break;
                case false:
                    this.failure(response.data.data);

                    break;
            }

        });
    }
}