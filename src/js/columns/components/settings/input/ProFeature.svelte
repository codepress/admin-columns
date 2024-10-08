<script lang="ts">
    import AcToggle from "ACUi/element/AcToggle.svelte";
    import ProPromotionModal from "../ProPromotionModal.svelte";
    import {getColumnSettingsTranslation} from "../../../utils/global";

    const i18n = getColumnSettingsTranslation();
    let showModal: boolean = false;

    const openModal = () => {
        showModal = true
    }

    const closeModal = () => {
        showModal = false
    }
</script>

<div class="acu-pt-1 acu-flex acu-gap-2 acu-items-center acu-cursor-pointer"
	on:click={openModal}
	on:keydown
	role="button"
	tabindex="0">
	<AcToggle checked={false} disabled={true}></AcToggle>
	<span
		class="acu-bg-[#e9426e] acu-text-[#fff] acu-inline-block acu-px-2 acu-py-[2px] acu-rounded acu-font-bold">PRO
	</span>
	{#if showModal }
		<ProPromotionModal on:close={closeModal} title={i18n.pro.modal.title}/>
	{/if}
</div>