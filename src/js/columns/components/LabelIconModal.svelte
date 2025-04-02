<script lang="ts">
    import dashicons from '../config/dashicons.json';
    import {AcButton, AcModal} from "ACUi/index";
    import {getColumnSettingsTranslation} from "../utils/global";
    import {createEventDispatcher} from "svelte";

    export let value: string;

    type iconsList = Array<{ name: string, items: string[] }>

    const icons: iconsList = dashicons
    const i18n = getColumnSettingsTranslation();
    const dispatch = createEventDispatcher();

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

    const applyIcon = () => {
        value = `<span class="dashicons dashicons-${selectedIcon}"></span>`
        dispatch('change', value);
    }

    const selectIcon = (dashicon: string) => {
        selectedIcon = dashicon;
    }

</script>


<AcModal visible className="acui2 -iconpicker -xl" appendToBody on:close>
	<span slot="header">
		<h2>Select Icon</h2>
	</span>
	<div slot="content">
		{#each filteredIcons as icongroup}
			<h3 class="acu-font-normal acu-mt-[0] acu-mb-1">{icongroup.name}</h3>
			<div class="ac-ipicker__group">
				{#each icongroup.items as dashicon}
					<button
						class="ac-ipicker__icon"
						data-dashicon={dashicon}
						tabindex="0"
						class:active={dashicon === selectedIcon}
						on:click={() =>selectIcon(dashicon)}
					>
						<span class="dashicons dashicons-{dashicon} acu-w-[40px] acu-h-[40px] acu-text-[28px]"></span>
					</button>
				{/each}
			</div>
		{/each}
	</div>
	<div slot="footer" class="acu-flex acu-items-center">
		<div class="acu-flex-grow">
			<input type="search" style="width:100%" placeholder={i18n.global.search} bind:value={searchQuery}
				on:input={searchIcons}>
		</div>
		<div class="acu-flex acu-w-[60px] acu-items-center acu-justify-center">
			{#if selectedIcon}
				<span class="dashicons dashicons-{selectedIcon}"></span>
			{/if}
		</div>
		<div class="acp-iconmodal-footer__action">
			<AcButton on:click={applyIcon} type="primary">{i18n.global.select}</AcButton>
		</div>

	</div>
</AcModal>
