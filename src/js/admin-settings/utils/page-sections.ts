type Section = {
    priority: number
    //component: typeof SvelteComponent
    component: HTMLElement
}

type SectionsCollection = {
    [key: string]: Section[]
}

type Location = 'after_general' | 'inside_general'

export default class SettingSections {

    static sections: SectionsCollection = {}

    static registerSection(location: Location, component: HTMLElement, priority: number = 10) {
        if (!SettingSections.sections.hasOwnProperty(location)) {
            SettingSections.sections[location] = [];
        }

        SettingSections.sections[location].push({priority, component})
    }


    //static getSections(location: Location): typeof SvelteComponent [] {
    static getSections(location: Location): HTMLElement [] {
        if (!SettingSections.sections.hasOwnProperty(location)) {
            return [];
        }

        let sections =  SettingSections.sections[location].sort((a, b) => {
            return a.priority > b.priority ? -1 : 1;
        });

        return sections.map( d => d.component );
    }

}