export default class ServiceContainer {

    private readonly services: Record<string, any>

    constructor() {
        this.services = {}
    }

    setService(name: string, service: any) {
        this.services[name] = service;
    }

    getService<T = any>(name: string): T|null {
        return this.hasService(name) ? this.services[name] : null;
    }

    hasService(name: string): boolean {
        return this.services.hasOwnProperty(name);
    }

}