export default class AcHtmlElement {
    element: HTMLElement

    constructor( el: string|HTMLElement ) {
        this.element = el instanceof HTMLElement ? el : document.createElement( el );
    }

    static create( el: string|HTMLElement ){
        return new AcHtmlElement( el )
    }

    addId( id: string ){
        this.element.id = id;
        return this;
    }

    addClass( className: string ){
        this.element.classList.add(className);
        return this;
    }

    addClasses( ...classNames: string[] ){
        classNames.forEach( className => this.addClass( className ) );
        return this;
    }

    addHtml( html: string ){
        this.element.innerHTML = html;
        return this;
    }

    Css( property: any, value: any){
        this.element.style[property] = value;
        return this;
    }

}