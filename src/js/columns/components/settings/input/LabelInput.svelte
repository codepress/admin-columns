<script lang="ts">
    import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";
    import LabelIconModal from "../../LabelIconModal.svelte";
    import {MaterialIcon} from "@ac/material-icons/src";

    export let value: string;
    export let disabled: boolean = false;

    let showIconModal: boolean = false;

    const addIcon = () => {
        showIconModal = true;
    }

    const handleIconSelect = (event: CustomEvent) => {
        value = event.detail;
        showIconModal = false;
    }

    const closeModal = () => {
        showIconModal = false;
    }


</script>

<AcInputGroup>
	<input type="text" bind:value={value} {disabled}>
	<div role="none" class="acui-input-group-text" on:click={addIcon} on:keypress>
		<MaterialIcon icon="sentiment_satisfied" className="acu-cursor-pointer" />
	</div>

	{#if showIconModal && !disabled}
		<LabelIconModal value="" on:change={ handleIconSelect } on:close={closeModal}/>
	{/if}
</AcInputGroup>