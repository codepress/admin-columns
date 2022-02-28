type prioritizedFilters = {
    [key: number] : Array<Function>
}

type hookableFilters = {
    [name: string ] : prioritizedFilters
}

export default class AcHookableFilters {

    filters: hookableFilters;

    constructor(){
        this.filters = {};
    }

    addFilter( name: string, callback: any, priority: number = 10 ){
        if( ! this.filters.hasOwnProperty( name ) ){
            this.filters[name] = {};
        }

        if( ! this.filters[name].hasOwnProperty( priority ) ){
            this.filters[name][priority] = [];
        }

        this.filters[name][ priority ].push( callback );
        console.log( this.filters );
    }

    applyFilters( name: string, value: any, payload: Object = {} ){
        if( ! this.filters.hasOwnProperty(name) ){
            return value;
        }

        Object.keys(this.filters[name]).forEach( (priority:string) => {
            this.filters[ name ][ parseInt( priority ) ].forEach( cb => {
                value = cb( value, payload );
            });
        });

        return value;
    }

}