import Tooltip from "./tooltips";

const $ = require("jquery");

export class Pointer {

    element: HTMLElement
    settings: any

    constructor(el: HTMLElement) {
        this.element = el;
        this.settings = this.getDefaults();
        this.init();

        this.setInitialized();
    }

    setInitialized() {
        AcPointers.add(this.element);
    }

    getDefaults() {
        return {
            width: this.element.getAttribute('data-width') ? this.element.getAttribute('data-width') : 250,
            noclick: this.element.getAttribute('data-noclick') ? this.element.getAttribute('data-noclick') : false,
            position: this.getPosition()
        }
    }

    isInitialized() {
        return AcPointers.isInitialized(this.element);
    }

    init() {
        if (this.isInitialized()) {
            return;
        }

        // create pointer
        $(this.element).mouseenter(() => {
            $(this.element).pointer({
                content: this.getRelatedHTML(),
                position: this.settings.position,
                pointerWidth: this.settings.width,
                pointerClass: this.getPointerClass()
            });

        });
        this.initEvents();
    }

    getPosition() {
        let position = {
            at: 'left top',		// position of wp-pointer relative to the element which triggers the pointer event
            my: 'right top',	// position of wp-pointer relative to the at-coordinates
            edge: 'right',		// position of arrow
        };

        let pos = this.element.getAttribute('data-pos');
        let edge = this.element.getAttribute('data-pos_edge');

        if ('right' === pos) {
            position = {
                at: 'right middle',
                my: 'left middle',
                edge: 'left'
            };
        }

        if ('right_bottom' === pos) {
            position = {
                at: 'right middle',
                my: 'left bottom',
                edge: 'none'
            };
        }

        if ('left' === pos) {
            position = {
                at: 'left middle',
                my: 'right middle',
                edge: 'right'
            };
        }

        if (edge) {
            position.edge = edge;
        }

        return position;
    }

    getPointerClass() {
        let classes = [
            'ac-wp-pointer',
            'wp-pointer',
            'wp-pointer-' + this.settings.position.edge
        ];

        if (this.settings.noclick) {
            classes.push('noclick');
        }

        return classes.join(' ');
    }

    getRelatedHTML(): string {
        return document.getElementById(this.element.getAttribute('rel') ?? '' )?.innerHTML ?? '';
    }

    initEvents() {
        let el = $(this.element);

        // click
        if (!this.settings.noclick) {
            el.click(function () {
                if (el.hasClass('open')) {
                    el.removeClass('open');
                } else {
                    el.addClass('open');
                }
            });
        }

        el.click(function () {
            el.pointer('open');
        });

        el.mouseenter(function () {
            el.pointer('open');
            setTimeout(() => {
                el.pointer('open');
            }, 2);
        });

        el.mouseleave(function () {
            setTimeout(() => {
                if (!el.hasClass('open') && $('.ac-wp-pointer.hover').length === 0) {
                    el.pointer('close');
                }
            }, 1);
        });

        el.on('close', () => {
            setTimeout(() => {
                if (!el.hasClass('open')) {
                    el.pointer('close');
                }
            })
        });
    }
}

class AcPointers {
    static initElements: Array<HTMLElement> = [];

    static isInitialized(element: HTMLElement): boolean {
        return this.initElements.filter(el => el === element).length > 0;
    }

    static add(element: HTMLElement) {
        this.initElements.push(element);
    }
}


export const initPointers = (elements: NodeListOf<HTMLElement>|null = null) => {
    if (!elements) {
        elements = document.querySelectorAll<HTMLElement>('.ac-pointer')
    }

    elements.forEach(element => {
        new Pointer(element);
    });

    $('.ac-wp-pointer').hover(function () {
        $(this).addClass('hover');
    }, function () {
        $(this).removeClass('hover');
        $('.ac-pointer').trigger('close');
    }).on('click', '.close', function () {
        $('.ac-pointer').removeClass('open');
    });

    new Tooltip();
};