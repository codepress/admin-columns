<script lang="ts">
    import AcModal from "ACUi/AcModal.svelte";
    import {getColumnSettingsConfig, getColumnSettingsTranslation} from "../../utils/global";

    export let title: string = "";
    export let feature: string = "";

    const config = getColumnSettingsConfig();
    const i18n = getColumnSettingsTranslation().pro.modal;
    const proBanner = config.pro_banner;

    const featureMap: Record<string, string> = {
        'sorting': 'sort_filter',
        'filter': 'sort_filter',
        'search': 'search',
        'bulk-edit': 'bulk_edit',
        'editing': 'inline_edit',
        'export': 'export',
        'column-sets': 'list_tables',
    };

    const items = [
        { key: 'sort_filter', label: i18n.sort_filter },
        { key: 'search', label: i18n.search },
        { key: 'bulk_edit', label: i18n.bulk_edit },
        { key: 'inline_edit', label: i18n.inline_edit },
        { key: 'export', label: i18n.export },
        { key: 'list_tables', label: i18n.list_tables },
        { key: 'addons', label: i18n.addons },
    ];

    $: highlightKey = featureMap[feature] || '';

    $: sortedItems = (() => {
        if (!highlightKey) return items;
        const highlighted = items.filter(item => item.key === highlightKey);
        const rest = items.filter(item => item.key !== highlightKey);
        return [...highlighted, ...rest];
    })();

    $: upgradeUrl = (() => {
        if (proBanner && feature) {
            const found = proBanner.features.find((f: any) => f.url.includes('usp-' + feature));
            if (found) return found.url;
        }
        return config.urls.upgrade;
    })();

</script>


<AcModal visible className="acui2 -promotion" appendToBody on:close>
	<div slot="header">
		<h3 class="acu-my-[0]">{title}</h3>
	</div>
	<div slot="content">
		<p class="acu-pt-[0] acu-font-bold">{i18n.subtitle}</p>
		<ul>
			{#each sortedItems as item}
				<li class:acu-text-pink={highlightKey === item.key} class:acu-font-bold={highlightKey === item.key}>
					<span class="dashicons dashicons-yes" class:acu-text-pink={highlightKey === item.key}></span> {item.label}
				</li>
			{/each}
		</ul>
	</div>
	<div slot="footer">
		<svg class="acu-absolute acu-right-[20px] acu-bottom-[20px] acu-w-[120px] acu-h-[200px]">
			<use xlink:href="{config.assets}/images/symbols.svg#zebra-thumbs-up"></use>
		</svg>

		<div class="acu-flex acu-flex-col acu-items-start acu-gap-2 acu-pb-2">
			<a href="{upgradeUrl}" target="_blank" class="button button-primary !acu-inline-block !acu-w-auto acu-px-4 acu-py-1 !acu-text-[14px]">
				{i18n.upgrade}
			</a>
			<a href="{config.urls.learn_more}" target="_blank" class="acu-text-[#666] acu-text-[12px] acu-no-underline hover:acu-underline">
				{i18n.learn_more}
			</a>
		</div>
	</div>
</AcModal>
