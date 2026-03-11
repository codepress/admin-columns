import Nanobus from "nanobus";
import AcHtmlElement from "../helpers/html-element";
import AcHookableFilters from "./ac-hookable-filters";

export default class AcServices {

    public filters: AcHookableFilters
    private services: { [key: string]: any }
    private events: Nanobus
    public $: typeof AcHtmlElement

    constructor() {
        this.services = {};
        this.events = new Nanobus();
        this.filters = new AcHookableFilters();
        this.$ = AcHtmlElement;
    }

    registerService(name: string, service: any): this {
        this.services[name] = service;

        this.events.emit(`Service.Registered.${name}`, service);

        return this;
    }

    getService<T = any>(name: string): T | null {
        return this.hasService(name) ? this.services[name] : null;
    }

    hasService(name: string): boolean {
        return this.services.hasOwnProperty(name)
    }

    addListener<
        TEventMap extends Record<string, any>,
        K extends keyof TEventMap & string = keyof TEventMap & string
    >(
        name: K,
        callback: (payload: TEventMap[K]) => void
    ): void;
    addListener(name: string, callback: any): void;
    addListener(name: string, callback: any) {
        this.events.addListener(name, callback);
    }

    emitEvent<
        TEventMap extends Record<string, any>,
        K extends keyof TEventMap & string = keyof TEventMap & string
    >(
        name: K,
        args: TEventMap[K]
    ): void;
    emitEvent(name: string, args: any): void;
    emitEvent(name: string, args: any) {
        this.events.emit(name, args)
    }

}