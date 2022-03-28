import {insertAfter} from "../helpers/elements";
import AcServices from "./ac-services";
import Tooltips from "./tooltips";
import {LocalizedAcTable} from "../types/table";

const $ = require("jquery");

declare const ajaxurl: string
declare const AC: LocalizedAcTable
declare const AC_SERVICES: AcServices

export default class ToggleBoxLink {
    contentBox: HTMLElement|null

    constructor( private element: HTMLLinkElement) {
        this.element = element;
        this.initEvents();

        this.contentBox = element?.parentElement?.querySelector('.ac-toggle-box-contents') ?? null;
        if (!this.contentBox) {
            this.createContenBox();
        }
    }

    isAjax() {
        return parseInt(this.element.dataset.ajaxPopulate ?? '') === 1;
    }

    isInited() {
        return this.element.dataset.toggleBoxInit;
    }

    createContenBox() {
        let contentBox = document.createElement('div');

        contentBox.classList.add('ac-toggle-box-contents');

        insertAfter(contentBox, this.element);

        this.contentBox = contentBox;

        return this.contentBox;
    }

    initEvents() {
        if (this.isInited()) {
            return;
        }

        this.element.addEventListener('click', (e) => {
            e.preventDefault();

            if (this.isAjax() && !this.hasContent()) {
                this.manageAjaxValue();
            }

            this.toggleContentBox();
        });

        this.element.dataset.toggleBoxInit = 'true';
    }

    hasContent() {
        return this.getContentBox().innerHTML.length > 0
    }

    setContent(content: string) {
        this.getContentBox().innerHTML = content;
    }

    getContentBox() {
        if (!this.contentBox) {

            return this.createContenBox();
        }

        return this.contentBox;
    }

    setLabel(open: boolean) {
        let label = this.element.dataset.label;

        if (open && this.element.dataset.labelClose) {
            label = this.element.dataset.labelClose
        }

        this.element.innerHTML = label + '<span class="spinner"></span>';
    }

    toggleContentBox() {
        if (this.getContentBox().classList.contains('-open')) {
            this.getContentBox().classList.remove('-open');
            this.setLabel(false);
        } else {
            this.getContentBox().classList.add('-open');
            this.setLabel(true);
        }
    }

    manageAjaxValue() {
        this.element.classList.add('loading');
        this.retrieveAjaxValue().done((response: any) => {
            this.setContent(response);

            $(this.element.parentElement).trigger('ajax_column_value_ready');
            AC_SERVICES.getService<Tooltips>('Tooltips')?.init();
        }).always(() => {
            this.element.classList.remove('loading');
        });

    }

    retrieveAjaxValue() {
        return $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'ac_get_column_value',
                list_screen: AC.list_screen,
                layout: AC.layout,
                column: this.element.dataset.column,
                pk: this.element.dataset.itemId,
                _ajax_nonce: AC.ajax_nonce
            }
        });
    }

}