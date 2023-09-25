import Nanobus from "nanobus";
import {AxiosPromise, AxiosResponse} from "axios";
import {LocalizedAcColumnSettings, UninitializedListScreen, UninitializedListScreens} from "../../types/admin-columns";

import axios from "axios";

declare const AC: LocalizedAcColumnSettings

class ListScreenInitializer {

    listScreens: UninitializedListScreens
    events: Nanobus
    processed: Array<UninitializedListScreen>
    errors: Array<UninitializedListScreen>
    success: Array<UninitializedListScreen>

    constructor(list_screens: UninitializedListScreens) {
        this.listScreens = list_screens;
        this.processed = [];
        this.errors = [];
        this.success = [];
        this.events = new Nanobus();
        this.run();
    }

    doAjaxCall(listScreen: UninitializedListScreen): AxiosPromise {
        return axios.get(listScreen.screen_link)
    }

    run() {
        Object.values(this.listScreens).forEach((l:UninitializedListScreen) => this.processListScreen(l));
    }

    onFinish() {
        if (this.success.length === Object.keys(this.listScreens).length) {
            this.events.emit('success');
        }

        if (this.errors.length > 0) {
            this.events.emit('error');
        }
    }

    checkFinish() {
        if (this.processed.length === Object.keys(this.listScreens).length) {
            this.onFinish();
        }
    }

    processListScreen(listScreen: UninitializedListScreen) {
        this.doAjaxCall(listScreen).then((response: AxiosResponse<string>) => {
            response.data === 'ac_success'
                ? this.success.push(listScreen)
                : this.errors.push(listScreen)
        }).catch(() => {
            this.errors.push(listScreen);
        }).finally(() => {
            this.processed.push(listScreen);
            this.checkFinish();
        });
    }

}

export const initUninitializedListScreens = (listScreens: UninitializedListScreens) => {
    if (Object.keys(listScreens).length > 0) {

        // Only load main screen first if unitialized, otherwise do the rest in background
        if (listScreens.hasOwnProperty(AC.list_screen)) {
            const main_initializer = new ListScreenInitializer({[AC.list_screen]: listScreens[AC.list_screen]});

            main_initializer.events.on('error', () => {
                document.querySelectorAll('.ac-loading-msg-wrapper').forEach(el => el.remove());
                document.querySelectorAll('.menu').forEach(el => el.classList.remove('hidden'));
            });

            main_initializer.events.on('success', () => {
                window.location.href = `${location.href}&t=${Date.now()}`;
            });

        } else {
            new ListScreenInitializer(listScreens);
        }

    }
}