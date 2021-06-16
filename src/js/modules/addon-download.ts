import WPNotice from "./notice";
// @ts-ignore
import $ from 'jquery';
import {LocalizedAcAddonSettings} from "../types/admin-columns";
import AddonDownloader from "./addon-downloader";

declare let ajaxurl: string;
declare let AC: LocalizedAcAddonSettings

export class AddonDownload {

    element: HTMLElement
    slug: string
    loadingState: boolean
    downloader: AddonDownloader

    constructor(el: HTMLElement, slug: string) {
        this.downloader = new AddonDownloader(slug, AC.is_network_admin === '1', AC._ajax_nonce)
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
            button.insertAdjacentHTML('afterend', '<span class="spinner" style="visibility: visible;"></span>');
            button.classList.add('button-disabled');
        }

        this.loadingState = true;
    }

    removeLoadingState(): void {
        const button = this.getDownloadButton();
        const spinner = this.element.querySelector('.spinner');

        if (spinner) {
            spinner.remove();
        }

        if (button) {
            button.classList.remove('button-disabled');
        }

        this.loadingState = false;
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

    success(status: string) {
        const button = this.getDownloadButton();
        const title = this.element.querySelector('h3');
        const notice = new WPNotice();

        notice.setMessage(`<p>The Add-on <strong>${title.innerHTML}</strong> is installed.</p>`)
            .makeDismissable()
            .addClass('updated');

        document.querySelector('.ac-addons').insertAdjacentElement('beforebegin', notice.render());

        if (button) {
            button.insertAdjacentHTML('beforebegin', `<span class="active">${status}</span>`);
            button.remove();
        }

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

        document.querySelector('.ac-addons').insertAdjacentElement('beforebegin', notice.render());
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