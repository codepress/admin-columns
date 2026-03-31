<script lang="ts">
    import AdminHeaderBar from "../components/AdminHeaderBar.svelte";
    import AcPanel from "ACUi/acui-panel/AcPanel.svelte";
    import AcPanelHeader from "ACUi/acui-panel/AcPanelHeader.svelte";
    import IntegrationCardRich from "./component/IntegrationCardRich.svelte";
    import IntegrationCardCompact from "./component/IntegrationCardCompact.svelte";
    import IntegrationGridItem from "./component/IntegrationGridItem.svelte";
    import {fetchIntegrations, IntegrationItem} from "./ajax/requests";
    import {getAddonsTranslation} from "./global";

    const RICH_CARD_SLUGS = ['ac-addon-acf', 'ac-addon-woocommerce'];

    let rich: IntegrationItem[] = [];
    let compact: IntegrationItem[] = [];
    let grid: IntegrationItem[] = [];
    let detectedCount: number = 0;
    let loaded: boolean = false;

    const i18n = getAddonsTranslation();

    fetchIntegrations().then(r => {
        const integrations = r.data.data.integrations;

        rich = integrations
            .filter(i => i.plugin_active && RICH_CARD_SLUGS.includes(i.slug))
            .sort((a, b) => a.priority - b.priority);

        compact = integrations
            .filter(i => i.plugin_active && !RICH_CARD_SLUGS.includes(i.slug))
            .sort((a, b) => a.priority - b.priority);

        grid = integrations
            .filter(i => !i.plugin_active)
            .sort((a, b) => a.priority - b.priority);

        detectedCount = rich.length + compact.length;
        loaded = true;
    });

    function getEyebrow(): string {
        if (detectedCount === 0) {
            return i18n.no_plugins_detected;
        }
        if (detectedCount === 1) {
            return i18n.detected_eyebrow_one;
        }
        return i18n.detected_eyebrow.replace('%d', String(detectedCount));
    }

    function isRecommended(integration: IntegrationItem): boolean {
        if (rich.length === 0) {
            return false;
        }
        // ACF gets recommended if detected, otherwise WooCommerce
        const acf = rich.find(i => i.slug === 'ac-addon-acf');
        if (acf) {
            return integration.slug === 'ac-addon-acf';
        }
        return integration.slug === rich[0].slug;
    }

    function getSiteContextBanner(): string {
        const parts: string[] = [];

        for (const item of rich) {
            if (!item.site_context) continue;

            if (item.slug === 'ac-addon-acf' && item.site_context.field_group_count) {
                parts.push(
                    `Advanced Custom Fields with ${item.site_context.field_group_count} field groups across ${item.site_context.post_type_count} post types`
                );
            }
            if (item.slug === 'ac-addon-woocommerce' && item.site_context.product_count) {
                parts.push(
                    `WooCommerce with ${item.site_context.product_count} products`
                );
            }
        }

        return parts.join(' · ');
    }

    $: siteContextBanner = getSiteContextBanner();
</script>

<AdminHeaderBar title="Integrations"/>

<div class="acu-mx-[50px] acu-pt-[70px]">
    <div class="acu-hidden">
        <hr class="wp-header-end acu-hidden">
    </div>

    <main class="acu-flex acu-gap-4 acu-w-full">
        <AcPanel classNames={['acu-mb-3','acu-flex-grow', 'acu-max-w-[1520px]']}>
            <AcPanelHeader slot="header" type="h2" border>
                {#if loaded}
                <span class="acu-inline-block acu-mb-2 acu-px-3 acu-py-1.5 acu-rounded-full acu-bg-[#EEF4FF] acu-text-[#3559A8] acu-text-xs acu-font-bold">
                    {getEyebrow()}
                </span>
                {/if}
                <h2 class="acu-m-0 acu-text-2xl acu-font-bold">{i18n.page_heading}</h2>
                <p class="acu-m-0 acu-mt-2 acu-text-[#5F6B7A] acu-text-base acu-max-w-[760px]">{i18n.page_subtitle}</p>
            </AcPanelHeader>

            <div class="acu-p-6 acu-mb-8" slot="body">
                {#if loaded}
                    <!-- Site context banner -->
                    {#if siteContextBanner}
                    <div class="acu-flex acu-items-center acu-gap-3.5 acu-bg-[#F8FAFC] acu-border acu-border-solid acu-border-ui-border acu-rounded-[14px] acu-px-5 acu-py-4 acu-mb-6 acu-text-sm acu-text-[#5F6B7A]">
                        <span class="acu-w-9 acu-h-9 acu-rounded-[10px] acu-bg-[#EEF4FF] acu-text-[#3559A8] acu-flex acu-items-center acu-justify-center acu-flex-shrink-0 acu-font-bold">i</span>
                        <div>
                            <strong class="acu-text-[#1F2937]">{i18n.your_site}</strong>
                            {siteContextBanner}
                        </div>
                    </div>
                    {/if}

                    <!-- Detected section -->
                    {#if rich.length > 0 || compact.length > 0}
                    <div class="acu-mb-6">
                        <div class="acu-flex acu-items-center acu-gap-3 acu-mb-5">
                            <span class="acu-text-xs acu-font-bold acu-text-[#5F6B7A] acu-uppercase acu-tracking-wider">{i18n.detected_on_site}</span>
                            <div class="acu-flex-1 acu-h-px acu-bg-ui-border"></div>
                        </div>

                        <!-- Rich cards -->
                        {#if rich.length > 0}
                        <div class="acu-grid acu-gap-5 {rich.length > 1 ? 'acu-grid-cols-2' : 'acu-grid-cols-1 acu-max-w-[580px]'}">
                            {#each rich as integration}
                                <IntegrationCardRich {integration} isRecommended={isRecommended(integration)}/>
                            {/each}
                        </div>
                        {/if}

                        <!-- Compact rows -->
                        {#if compact.length > 0}
                        <div class="acu-mt-5 acu-flex acu-flex-col acu-gap-2.5">
                            {#each compact as integration}
                                <IntegrationCardCompact {integration}/>
                            {/each}
                        </div>
                        {/if}
                    </div>
                    {/if}

                    <!-- More integrations grid -->
                    {#if grid.length > 0}
                    <div class="acu-pt-6 acu-border-0 acu-border-t acu-border-solid acu-border-ui-border">
                        <div class="acu-flex acu-items-center acu-justify-between acu-mb-4">
                            <h3 class="acu-m-0 acu-text-base acu-font-semibold">{i18n.more_integrations}</h3>
                            <span class="acu-text-xs acu-text-[#5F6B7A]">{i18n.install_hint}</span>
                        </div>
                        <div class="acu-grid acu-grid-cols-[repeat(auto-fill,minmax(200px,1fr))] acu-gap-2.5">
                            {#each grid as integration}
                                <IntegrationGridItem {integration}/>
                            {/each}
                        </div>
                    </div>
                    {/if}
                {/if}
            </div>
        </AcPanel>
    </main>
</div>
