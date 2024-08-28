<script lang="ts">
    import dashicons from '../../../config/dashicons.json';
    import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";
    import {getColumnSettingsTranslation} from "../../../utils/global";
    import LabelIconModal from "../../LabelIconModal.svelte";

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
		<span class="dashicons dashicons-format-image"></span>
	</div>

	{#if showIconModal && !disabled}
		<LabelIconModal value="" on:change={ handleIconSelect } on:close={closeModal}/>
	{/if}
</AcInputGroup>