// @ts-ignore
import $ from 'jquery';
import {LocalizedAcAddonSettings} from "../types/admin-columns";
import AddonDownloader from "./addon-downloader";

declare let AC: LocalizedAcAddonSettings

export class AddonActivator {

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
        return this.element.querySelector<HTMLAnchorElement>('[data-activate]');
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

        if (button) {
            button.addEventListener('click', e => {
                e.preventDefault();

                if (!this.loadingState) {
                    this.setLoadingState();
                    this.activate();
                }
            });
        }
    }

    getFooterElement(): HTMLElement {
        return this.element.querySelector('.ac-addon__actions');
    }

    activate() {
        this.downloader.download().then(d => {
            if (d.data.data.activated) {
                this.success(d.data.data.status);
            }
        }).finally(() => this.setLoadingFinished());
    }

    success(status: string) {
        this.setLoadingFinished();
        this.getFooterElement().innerHTML = `<div class="ac-addon__state"><span class="-green dashicons dashicons-yes"></span><span class="ac-addon__state__label">${status}</span></div>`
    }

}