<script lang="ts">
    import AcModal from "ACUi/AcModal.svelte";
    import {getColumnSettingsConfig, getColumnSettingsTranslation} from "../../utils/global";

    export let feature: string = "";

    const config = getColumnSettingsConfig();
    const i18n = getColumnSettingsTranslation().pro.modal;
    const proBanner = config.pro_banner;

    const featureMap: Record<string, string> = {
        'sorting': 'sorting',
        'filter': 'filter',
        'search': 'search',
        'bulk-edit': 'bulk_edit',
        'editing': 'inline_edit',
        'export': 'export',
        'column-sets': 'list_tables',
    };

    // Features shown in the "also get" list — sorting and filter share the same slot
    const alsoGetKeys = ['inline_edit', 'bulk_edit', 'sort_filter', 'search', 'export', 'list_tables'];

    // Map sorting/filter to sort_filter for the "also get" exclusion
    const sortFilterKeys = ['sorting', 'filter'];

    $: featureKey = featureMap[feature] || '';
    $: featureData = featureKey ? (i18n.features[featureKey] || null) : null;

    $: badge = featureData ? featureData.badge : '';
    $: headline = featureData ? featureData.headline : i18n.title;
    $: description = featureData ? featureData.description : i18n.subtitle;

    // For "also get" list, exclude the active feature's group
    $: excludeAlsoGetKey = sortFilterKeys.includes(featureKey) ? 'sort_filter' : featureKey;

    $: alsoGetItems = alsoGetKeys
        .filter(key => key !== excludeAlsoGetKey)
        .map(key => {
            // For sort_filter, use the sorting entry's label
            const dataKey = key === 'sort_filter' ? 'sorting' : key;
            return {
                key,
                label: i18n.features[dataKey]?.label || '',
            };
        })
        .filter(item => item.label);

    $: upgradeUrl = (() => {
        if (proBanner && feature) {
            const found = proBanner.features.find((f: any) => f.url.includes('usp-' + feature));
            if (found) return found.url;
        }
        return config.urls.upgrade;
    })();
</script>

<AcModal visible contentNoPadding className="acui2 -promotion" appendToBody on:close>
    <div slot="header">
    </div>
    <div slot="content">
        <div class="ac-promo-modal">
            {#if badge}
                <div class="ac-promo-modal__topbar">
                    <span class="ac-promo-modal__badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2z"/></svg>
                        {badge}
                    </span>
                </div>
            {/if}

            <h2 class="ac-promo-modal__title">{headline}</h2>
            <p class="ac-promo-modal__description">{description}</p>

            {#if alsoGetItems.length > 0}
                <div class="ac-promo-modal__features">
                    <p class="ac-promo-modal__features-title">{i18n.also_get}</p>
                    <ul>
                        {#each alsoGetItems as item}
                            <li>
                                <span class="ac-promo-modal__check">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </span>
                                {item.label}
                            </li>
                        {/each}
                    </ul>
                </div>
            {/if}

            <div class="ac-promo-modal__actions">
                <a href="{upgradeUrl}" target="_blank" class="button button-primary">
                    {i18n.upgrade}
                </a>
                <a href="{config.urls.learn_more}" target="_blank" class="ac-promo-modal__see-all">
                    {i18n.see_all} &rarr;
                </a>
            </div>

            <p class="ac-promo-modal__guarantee">{i18n.guarantee}</p>

            <svg class="ac-promo-modal__zebra">
                <use xlink:href="{config.assets}/images/symbols.svg#zebra-thumbs-up"></use>
            </svg>
        </div>
    </div>
</AcModal>
