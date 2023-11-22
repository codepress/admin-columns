<script lang="ts">
    import dashicons from '../../config/dashicons.json';
    import ColumnSetting from "../ColumnSetting.svelte";
    import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";
    import AcModal from "ACUi/AcModal.svelte";
    import AcButton from "ACUi/element/AcButton.svelte";

    export let config: AC.Column.Settings.AbstractColumnSetting;
    export let value: string;

    type iconsList = Array<{ name: string, items: string[] }>

    let showIconModal: boolean = false;
    let selectedIcon: string;
    let searchQuery: string = '';

    const icons: iconsList = dashicons
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

<ColumnSetting label={config.label}>
	<AcInputGroup>
		<input type="text" bind:value={value}>
		<div role="none" class="acui-input-group-text" on:click={addIcon} on:keypress>
			<span class="dashicons dashicons-format-image"></span>
		</div>
	</AcInputGroup>
</ColumnSetting>
{#if showIconModal}
	<AcModal visible on:close={()=>showIconModal = false}>
		<span slot="header">Select Icon</span>
		<div slot="content">
			{#each filteredIcons as icongroup}
				<h3>{icongroup.name}</h3>
				{#each icongroup.items as dashicon}
					<button
							class="ac-ipicker__icon"
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
				<input type="search" bind:value={searchQuery} on:input={searchIcons}>
			</div>
			<div class="acp-iconmodal-footer__buttons">
				{#if selectedIcon}
					<button class="ac-ipicker__icon">
						<span class="dashicons dashicons-{selectedIcon}"></span>
					</button>
				{/if}
				<AcButton on:click={applyIcon} type="primary">Select</AcButton>
			</div>

		</div>
	</AcModal>
{/if}
<style>
	h3 {
		font-weight: normal;
		margin: 30px 0 0 0;
	}

	.ac-ipicker__icon {
		width: 40px;
		height: 40px;
		display: inline-flex;
		align-items: center;
		background: none;
		border: none;
	}

	.acp-iconmodal-footer {
		display: flex;
	}

	.acp-iconmodal-footer__buttons {
		display: flex;
		gap: 10px;
	}

	.acp-iconmodal-footer__input {
		display: flex;
		align-items: center;
		flex-grow: 1;
	}
</style>