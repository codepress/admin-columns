<script lang="ts">
    import dashicons from './dashicons.json';
    import {AcButton} from "./element";
    import AcModal from "./AcModal.svelte";
    import {createEventDispatcher} from "svelte";

    // Current value as a dashicon slug, e.g. "dashicons-database".
    export let value: string = '';
    export let title: string = 'Select Icon';
    export let searchLabel: string = 'Search';
    export let selectLabel: string = 'Select';

    type IconGroup = { name: string, items: string[] };

    const icons: IconGroup[] = dashicons;
    const dispatch = createEventDispatcher();

    // Work with the bare name internally; expose the slug on the boundary.
    let selectedIcon: string = value.replace(/^dashicons-/, '');
    let searchQuery: string = '';
    let filteredIcons: IconGroup[] = icons;

    const searchIcons = () => {
        const query = searchQuery.toLowerCase();

        filteredIcons = icons
            .map(group => ({name: group.name, items: group.items.filter(i => i.includes(query))}))
            .filter(group => group.items.length > 0);
    }

    const selectIcon = (dashicon: string) => {
        selectedIcon = dashicon;
    }

    const applyIcon = () => {
        dispatch('change', `dashicons-${selectedIcon}`);
    }
</script>

<AcModal visible className="acui2 -iconpicker -xl" appendToBody on:close>
	<span slot="header">
		<h2>{title}</h2>
	</span>
	<div slot="content">
		{#each filteredIcons as icongroup}
			<h3 class="acu-font-thin acu-mt-[0] acu-mb-1">{icongroup.name}</h3>
			<div class="ac-ipicker__group acu-mb-6">
				{#each icongroup.items as dashicon}
					<button
						class="ac-ipicker__icon"
						data-dashicon={dashicon}
						tabindex="0"
						class:active={dashicon === selectedIcon}
						on:click={() => selectIcon(dashicon)}
					>
						<span class="dashicons dashicons-{dashicon} acu-w-[40px] acu-h-[40px] acu-text-[28px]"></span>
					</button>
				{/each}
			</div>
		{/each}
	</div>
	<div slot="footer" class="acu-flex acu-items-center">
		<div class="acu-flex-grow">
			<input type="search" style="width:100%" placeholder={searchLabel} bind:value={searchQuery}
				on:input={searchIcons}>
		</div>
		<div class="acu-flex acu-w-[60px] acu-items-center acu-justify-center">
			{#if selectedIcon}
				<span class="dashicons dashicons-{selectedIcon}"></span>
			{/if}
		</div>
		<div class="acp-iconmodal-footer__action">
			<AcButton on:click={applyIcon} type="primary">{selectLabel}</AcButton>
		</div>
	</div>
</AcModal>
