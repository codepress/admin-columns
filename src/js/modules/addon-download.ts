import WPNotice from "./notice";
// @ts-ignore
import $ from 'jquery';
import {LocalizedAcAddonSettings} from "../types/admin-columns";
import AddonDownloader from "./addon-downloader";

declare let AC: LocalizedAcAddonSettings

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

    getDownloadButton(): HTMLAnchorElement {
        return this.element.querySelector<HTMLAnchorElement>('[data-install]');
    }

    setLoadingState(): void {
        const button = this.getDownloadButton();

        if (button) {
            button.classList.add('button-disabled');
        }

        this.element.querySelectorAll('h3').forEach(el => el.insertAdjacentHTML('afterend', '<span class="spinner" style="visibility: visible; transform: translateY(-3px);"></span>'))
        this.loadingState = true;
    }

    setLoadingFinished(): void {
        this.loadingState = false;
        const button = this.getDownloadButton();

        this.element.querySelectorAll('.spinner').forEach(el => el.remove());

        if (button) {
            button.classList.remove('button-disabled');
        }
    }

    initEvents() {
        const button = this.getDownloadButton();

        if (button) {
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

        notice.setMessage(`<p>The Add-on <strong>${title.innerHTML}</strong> is installed.</p>`)
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
            if (response.data.success) {
                this.success(response.data.data.status);
            } else {
                let fallback = response.data.data as unknown;
                this.failure(fallback as string);
            }
        });
    }

}