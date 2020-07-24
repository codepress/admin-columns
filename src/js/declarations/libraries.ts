export interface nanoBusInterface {
    emit(eventName: string, data?: any): void;

    on(eventName: string, callback: Function): void;

    addListener(eventName: string, callback: Function): void;

    once(eventName: string, callback: Function): void;

    prependListener(eventName: string, callback: Function): void;

    removeListener(eventName: string, callback: Function): void;
}