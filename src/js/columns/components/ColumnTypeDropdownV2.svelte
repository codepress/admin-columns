<script lang="ts">

    import {columnTypesStore} from "../store/column-types";
    import {SvelteSelectItem} from "../../types/select";
    import {createEventDispatcher} from "svelte";
    import Select from "svelte-select";

    let items = $columnTypesStore

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

</script>

<div on:keyup={handleKeyPress} role="none">
	<Select
		class="-acui"
		listOpen
		on:blur={handleClose}
		on:change={handleSelect}
		items={items}
		{groupBy}/>
</div>