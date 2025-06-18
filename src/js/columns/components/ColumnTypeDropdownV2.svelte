<script lang="ts">

    import {columnTypesStore} from "../store";
    import {SvelteSelectItem} from "../../types/select";
    import {createEventDispatcher, onMount} from "svelte";
    import Select from "svelte-select";
    import {getColumnSettingsConfig} from "../utils/global";
    import ColumnTypeGroupIcon from "./ColumnTypeGroupIcon.svelte";

    let items = $columnTypesStore
    let listOpen = false;

    const dispatch = createEventDispatcher();

    const handleSelect = (e: CustomEvent<any>) => {
        dispatch('selectItem', e.detail.value);
    }

    const groupBy = (item: SvelteSelectItem) => item.group;

    const handleClose = () => {
        dispatch('close');
    }

    const handleKeyPress = (event: KeyboardEvent) => {
        if (event.key === 'Escape') {
            handleClose();
        }
    }

    onMount(() => {
        listOpen = true;
    })

</script>

<div on:keyup={handleKeyPress} role="none">
	<Select
		class="-acui"
		--list-max-height="500px"
		listOpen={listOpen}
		on:blur={handleClose}
		on:change={handleSelect}
		items={items}
		{groupBy}
		label='searchable_label'>
		<div slot="item" let:item>

			{#if item.groupItem}
				<span class="acu-flex acu-items-center acu-relative acu-pl-1">
					{#key item.group_key}
						<ColumnTypeGroupIcon group_key={item.group_key}/>
					{/key}
					{item.label}
				</span>
			{:else}
				{item.value}
			{/if}
		</div>
	</Select>
</div>