<script lang="ts">
    import dashicons from '../../config/dashicons.json';
    import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";
    import AcModal from "ACUi/AcModal.svelte";
    import AcButton from "ACUi/element/AcButton.svelte";
    import {getColumnSettingsTranslation} from "../../utils/global";

    export let value: string;
    export let disabled: boolean = false;

    type iconsList = Array<{ name: string, items: string[] }>

    const icons: iconsList = dashicons
    const i18n = getColumnSettingsTranslation();

    let showIconModal: boolean = false;
    let selectedIcon: string;
    let searchQuery: string = '';


    let filteredIcons: iconsList = icons;

    const searchIcons = () => {
        let filtered: iconsList = [];

        icons.forEach(g => {
            let groupIcons: string[] = [];
            g.items.forEach(i => {
                if (i.includes(searchQuery.toLowerCase())) {
                    groupIcons.push(i)
                }
            });
            if (groupIcons.length > 0) {
                filtered.push({name: g.name, items: groupIcons})
            }
        });

        filteredIcons = filtered;
    }

    const addIcon = () => {
        showIconModal = true;
    }

    const applyIcon = () => {
        value = `<span class="dashicons dashicons-${selectedIcon}"></span>`
        showIconModal = false;
    }

    const selectIcon = (dashicon: string) => {
        selectedIcon = dashicon;
    }

</script>

<AcInputGroup>
	<input type="text" bind:value={value} {disabled}>
	<div role="none" class="acui-input-group-text" on:click={addIcon} on:keypress>
		<span class="dashicons dashicons-format-image"></span>
	</div>

	{#if showIconModal && !disabled}
		<AcModal visible on:close={()=>showIconModal = false} --modalWidth="960px" className="-iconpicker">
			<span slot="header">{i18n.settings.label.select_label}</span>
			<div slot="content">
				{#each filteredIcons as icongroup}
					<h3>{icongroup.name}</h3>
					{#each icongroup.items as dashicon}
						<button
							class="acp-iconpicker__icon"
							data-dashicon={dashicon}
							tabindex="0"
							on:click={() =>selectIcon(dashicon)}
						>
							<span class="dashicons dashicons-{dashicon}"></span>
						</button>
					{/each}

				{/each}

			</div>
			<div slot="footer" class="acp-iconmodal-footer">
				<div class="acp-iconmodal-footer__input">
					<input type="search" placeholder={i18n.global.search} bind:value={searchQuery} on:input={searchIcons}>
				</div>
				<div class="acp-iconmodal-footer__selection">
					{#if selectedIcon}
						<span class="dashicons dashicons-{selectedIcon}"></span>
					{/if}
				</div>
				<div class="acp-iconmodal-footer__action">
					<AcButton on:click={applyIcon} type="primary">{i18n.global.select}</AcButton>
				</div>

			</div>
		</AcModal>
	{/if}
</AcInputGroup>