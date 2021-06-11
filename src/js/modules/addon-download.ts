import WPNotice from "./notice";
// @ts-ignore
import $ from 'jquery';
import {LocalizedAcAddonSettings} from "../types/admin-columns";

declare let ajaxurl: string;
declare let AC: LocalizedAcAddonSettings

export class AddonDownload {

    element: HTMLElement
    slug: string
    loadingState: boolean

    constructor(el: HTMLElement, slug: string) {
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
        let request = this.request();

        request.done((response: any) => {
            this.removeLoadingState();
            if (response.success) {
                this.success(response.data.status);
            } else {
                this.failure(response.data);
            }
        });
    }

    request() {
        let data = {
            action: 'acp-install-addon',
            plugin_name: this.slug,
            network_wide: AC.is_network_admin,
            _ajax_nonce: AC._ajax_nonce
        };

        return $.ajax({
            url: ajaxurl,
            method: 'post',
            data: data
        });
    }

}