import Nanobus from "nanobus";
import axios, {AxiosPromise} from "axios";
import {currentTableUrl} from "../store/table_url";
import UninitializedListScreens = AC.Vars.Admin.Columns.UninitializedListScreens;
import UninitializedListScreen = AC.Vars.Admin.Columns.UninitializedListScreen;


class ListScreenInitializer {

    listScreens: UninitializedListScreens;
    events: Nanobus;
    processed: Array<UninitializedListScreen>;
    errors: Array<UninitializedListScreen>;
    success: Array<UninitializedListScreen>;
    batchSize: number;

    constructor(list_screens: UninitializedListScreens, batchSize = 3) {
        this.listScreens = list_screens;
        this.processed = [];
        this.errors = [];
        this.success = [];
        this.batchSize = batchSize;
        this.events = new Nanobus();
        this.run();
    }

    doAjaxCall(listScreen: UninitializedListScreen): AxiosPromise {
        return axios.get(listScreen.screen_link);
    }

    async run() {
        const listScreenArray = Object.values(this.listScreens);
        let currentIndex = 0;

        // Process items in batches
        while (currentIndex < listScreenArray.length) {
            const batch = listScreenArray.slice(currentIndex, currentIndex + this.batchSize);
            await this.processBatch(batch);
            currentIndex += this.batchSize;
        }
    }

    async processBatch(batch: Array<UninitializedListScreen>) {
        const promises = batch.map(listScreen => this.processListScreen(listScreen));
        await Promise.all(promises);
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

    async processListScreen(listScreen: UninitializedListScreen) {
        try {
            const response = await this.doAjaxCall(listScreen);
            if (response.data === 'ac_success') {
                this.success.push(listScreen);
            } else {
                this.errors.push(listScreen);
            }
        } catch {
            this.errors.push(listScreen);
        } finally {
            this.processed.push(listScreen);
            this.checkFinish();
        }
    }
}

export const initUninitializedListScreens = (listScreens: UninitializedListScreens, listKey: string) => {
    const initializeSideListScreens = () => {
        new ListScreenInitializer(listScreens);
    }

    if (Object.keys(listScreens).length > 0) {

        // Only load main screen first if unitialized, otherwise do the rest in background
        if (listScreens.hasOwnProperty(listKey)) {
            const main_initializer = new ListScreenInitializer({[listKey]: listScreens[listKey]});

            main_initializer.events.on('error', () => {
                // TODO Show notice
            });

            main_initializer.events.on('success', () => {
                // This is a side process that must not prevent any other calls from running

                initializeSideListScreens()
            });

        } else {
            initializeSideListScreens()
        }
    }
}

export const initListScreenHeadings = () => {
    let storedTableUrl = '';

    currentTableUrl.subscribe(tableUrl => {
        if (!tableUrl) {
            return;
        }

        let url = new URL(tableUrl);
        let ajaxUrl = new URL(tableUrl);
        url.searchParams.delete('layout');

        if (storedTableUrl !== url.toString()) {
            ajaxUrl.searchParams.append('save-default-headings', '1');
            axios.get(ajaxUrl.toString());
        }
        storedTableUrl = url.toString();
    })


}