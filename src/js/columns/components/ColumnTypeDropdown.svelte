<script lang="ts">

    import {columnTypesStore} from "../store/column-types";
    import {SvelteSelectItem} from "../../types/select";
    import AcDropdownItem from "ACUi/acui-dropdown/AcDropdownItem.svelte";
    import AcDropdownGroup from "ACUi/acui-dropdown/AcDropdownGroup.svelte";
    import {createEventDispatcher, onMount} from "svelte";
    import Select from "svelte-select";

    let items = $columnTypesStore
    let filterText: string = '';
    let inputElement: HTMLInputElement;


    const dispatch = createEventDispatcher();

    interface ProcessedSvelteSelectItem extends SvelteSelectItem {
        groupHeader?: boolean;
    }

    type filterArgs = {
        filterText: string,
        groupBy?: any,
        items: SvelteSelectItem[]
    }

    const handleSelect = (e: CustomEvent<string>) => {
        dispatch('selectItem', e.detail);
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

    onMount(() => {
        //inputElement.focus();
    })

    $: filteredItems = filter({
        filterText,
        groupBy: true,
        items
    });

</script>

<div style="height: 400px;">
<Select class="-acui" listOpen showChevron items={items}>

</Select>
</div>


<!--<div class="acui-dropdown-search acu-sticky acu-top-[0] acu-bg-[white] acu-flex acu-p-[2px]">-->
<!--	<input bind:value={filterText} bind:this={inputElement} on:change|preventDefault|stopPropagation class="acu-h-6 acu-w-full acu-py-0.5 acu-px-1">-->
<!--</div>-->
<!--{#if filteredItems.length > 0 }-->
<!--	{#each filteredItems as item}-->
<!--		{#if item.groupHeader}-->
<!--			<div class="acui-dropdown-group">-->
<!--				{item.label}-->
<!--			</div>-->
<!--		{:else }-->
<!--			<AcDropdownItem on:click={handleSelect} customCss="-selectdd"-->
<!--					value={item.value.toString()}>{@html item.label}</AcDropdownItem>-->
<!--		{/if}-->
<!--	{/each}-->
<!--{:else }-->
<!--	<AcDropdownGroup>No columns found</AcDropdownGroup>-->
<!--{/if}-->