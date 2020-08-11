// @ts-ignore
import $ from 'jquery';

export default class Tooltips {

    constructor() {
        this.init();

    }


    isEnabled(): boolean {
        return typeof $.fn.qtip !== 'undefined';
    }

    init() {
        if (!this.isEnabled()) {
            return;
        }

        $('[data-ac-tip]').qtip({
            content: {
                attr: 'data-ac-tip'
            },
            position: {
                my: 'top center',
                at: 'bottom center'
            },
            style: {
                tip: true,
                classes: 'qtip-tipsy'
            }
        });
    }

}