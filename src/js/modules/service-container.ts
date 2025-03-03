import {keyAnyPair} from "../helpers/types";

export default class ServiceContainer {

    private services: keyAnyPair

    constructor() {
        this.services = {}
    }

    setService(name: string, service: any) {
        this.services[name] = service;
    }

    getService<T = any>(name: string): T | null{
        return this.hasService(name) ? this.services[name] : null;
    }

    hasService(name: string): boolean {
        return this.services.hasOwnProperty(name);
    }

}