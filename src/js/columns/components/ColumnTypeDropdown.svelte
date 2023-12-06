<script lang="ts">

    import {columnTypesStore} from "../store/column-types";
    import {onMount} from "svelte";
    import {SvelteSelectItem} from "../../types/select";

    let items = $columnTypesStore
	let filterText: string = '';

	const filterGroupItems = ( items:SvelteSelectItem[] ) => {
		const groupValues: string[] = [];
        const groups: { [key:string] : SvelteSelectItem[] } = {};

        items.forEach( item => {
            const groupLabel: string = item.group ?? '';

            if( ! groupValues.includes( groupLabel ) ){
				groupValues.push( groupLabel )
				groups[ groupLabel ] = [];

                groups[groupLabel].push(Object.assign(item,{ groupHeader: true } ) )
			}

            groups[groupLabel].push( item );
		});

		return items;
	}


    type filterArgs = {
        filterText: string,
		groupBy?: any,
		items: SvelteSelectItem[]
	}

    const filter = ({
		filterText,
		groupBy,
		items
	}:filterArgs) => {
		if( !items) return [];

        let filterResults = items.filter( item => {
            return item.label.toLowerCase().includes( filterText.toLowerCase() );
		});

        if( groupBy ){
            filterResults = filterGroupItems( filterResults );
		}

        return filterResults
    }


    onMount(() => {

    })


    $: filteredItems = filter({
		filterText,
        items,
		groupBy: true
    });

</script>

<input bind:value={filterText} >
{#each filteredItems as item}
	<div>{item.label}</div>
{/each}