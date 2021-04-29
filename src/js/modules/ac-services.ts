import Nanobus from "nanobus";

export default class AcServices {

    private services: { [key: string]: any }
    private events: Nanobus

    constructor() {
        this.services = {};
        this.events = new Nanobus();
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