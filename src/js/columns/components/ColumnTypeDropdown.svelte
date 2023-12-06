<script lang="ts">

    import {columnTypesStore} from "../store/column-types";
    import {SvelteSelectItem} from "../../types/select";
    import AcDropdownItem from "ACUi/acui-dropdown/AcDropdownItem.svelte";
    import AcDropdownGroup from "ACUi/acui-dropdown/AcDropdownGroup.svelte";
    import {createEventDispatcher} from "svelte";

    let items = $columnTypesStore
    let filterText: string = '';

    const dispatch = createEventDispatcher();

    interface ProcessedSvelteSelectItem extends SvelteSelectItem {
        groupHeader?: boolean;
    }

    type filterArgs = {
        filterText: string,
        groupBy?: any,
        items: SvelteSelectItem[]
    }

    const handleSelect = ( e: CustomEvent<string>) => {
        dispatch('selectItem', e.detail );
	}

    const filterGroupItems = (items: SvelteSelectItem[]): ProcessedSvelteSelectItem[] => {
        const groupValues: string[] = [];
        const groups: { [key: string]: SvelteSelectItem[] } = {};

        items.forEach(item => {
            const groupLabel: string = item.group ?? '';

            if (!groupValues.includes(groupLabel)) {
                groupValues.push(groupLabel)
                groups[groupLabel] = [];

                groups[groupLabel].push(Object.assign({}, item, {groupHeader: true, label: groupLabel, value: ''}))
            }

            groups[groupLabel].push(item);
        });

        const groupItems: SvelteSelectItem[] = [];

        groupValues.forEach(groupName => {
            groupItems.push(...groups[groupName]);
        });

        return groupItems;
    }

    const filter = ({
                        filterText,
                        groupBy,
                        items
                    }: filterArgs): ProcessedSvelteSelectItem[] => {
        if (!items) return [];

        let filterResults = items.filter(item => {
            return item.label.toLowerCase().includes(filterText.toLowerCase());
        });

        if (groupBy) {
            filterResults = filterGroupItems(filterResults);
        }

        return filterResults
    }

    $: filteredItems = filter({
        filterText,
        items,
        groupBy: true
    });

</script>
<style>
	.acui-dropdown-search {
		position: sticky;
		top: 0;
		background: #fff;
		display: flex;
	}

	.acui-dropdown-search input {
		height: 30px;
		width: 100%;
	}
</style>
<div class="acui-dropdown-search">
	<input bind:value={filterText}>
</div>
{#if filteredItems.length > 0 }
	{#each filteredItems as item}
		{#if item.groupHeader}
			<AcDropdownGroup>{item.label}</AcDropdownGroup>
		{:else }
			<AcDropdownItem on:click={handleSelect}
					value={item.value.toString()}>{@html item.label}</AcDropdownItem>
		{/if}
	{/each}
{:else }
	<AcDropdownGroup>No columns found</AcDropdownGroup>
{/if}