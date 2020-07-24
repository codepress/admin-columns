import {nanoBusInterface} from "./declarations/libraries";

export interface AdminColumns {
    events: nanoBusInterface,
    Form: any
}

export interface ACLocalizeScript {
    layout: string,
    list_screen: string,
    _ajax_nonce: string
}