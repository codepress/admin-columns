import Nanobus from "nanobus";
import {AcEl} from "../helpers/html-element";

export default class AcServices {

    private services: { [key: string]: any }
    private events: Nanobus
    public $: typeof AcEl

    constructor() {
        this.services = {};
        this.events = new Nanobus();
        this.$ = AcEl;
    }

    registerService(name: string, service: any): this {
        this.services[name] = service;

        return this;
    }

    getService<T = any>(name: string): T | null {
        return this.hasService(name) ? this.services[name] : null;
    }

    hasService(name: string): boolean {
        return this.services.hasOwnProperty(name)
    }

    addListener(name: string, callback: any) {
        this.events.addListener(name, callback);
    }

    emitEvent(name: string, args: any) {
        this.events.emit(name, args)
    }

}