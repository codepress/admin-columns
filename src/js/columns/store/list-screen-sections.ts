import {SvelteComponent} from "svelte";

type Section = {
    priority: number
    //component: typeof SvelteComponent
    component: HTMLElement
}

type SectionsCollection = {
    [key: string]: Section[]
}

type Location = 'before_columns' | 'after_columns';

export default class ListScreenSections {

    static sections: SectionsCollection = {}

    static registerSection(location: Location, component: HTMLElement, priority: number = 10) {
        if (!ListScreenSections.sections.hasOwnProperty(location)) {
            ListScreenSections.sections[location] = [];
        }

        ListScreenSections.sections[location].push({priority, component})
    }



    static _registerSection(location: Location, component: typeof SvelteComponent, priority: number = 10) {
        if (!ListScreenSections.sections.hasOwnProperty(location)) {
            ListScreenSections.sections[location] = [];
        }

        ListScreenSections.sections[location].push({priority, component})
    }

    //static getSections(location: Location): typeof SvelteComponent [] {
    static getSections(location: Location): HTMLElement [] {
        if (!ListScreenSections.sections.hasOwnProperty(location)) {
            return [];
        }

        let sections =  ListScreenSections.sections[location].sort((a, b) => {
            return a.priority > b.priority ? -1 : 1;
        });

        return sections.map( d => d.component );
    }

}