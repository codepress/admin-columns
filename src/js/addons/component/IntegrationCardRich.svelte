<script lang="ts">
    import {IntegrationItem} from "../ajax/requests";
    import {getAddonsConfig, getAddonsTranslation} from "../global";
    import AcButton from "ACUi/element/AcButton.svelte";
    import AcIcon from "ACUi/AcIcon.svelte";

    export let integration: IntegrationItem;
    export let isRecommended: boolean = false;

    const addons = getAddonsConfig();
    const i18n = getAddonsTranslation();

    interface RichContent {
        subhead: string;
        benefits: string[];
        contextLine: string;
        contextFallback: string;
    }

    const richContent: Record<string, RichContent> = {
        'ac-addon-acf': {
            subhead: 'Edit, filter, and bulk update ACF fields — without opening each post.',
            benefits: [
                'Inline edit any ACF field directly from the list table',
                'Bulk edit the same field across hundreds of posts at once',
                'Filter and sort by any ACF field value',
                'Export ACF data to CSV for reporting',
            ],
            contextLine: 'You have {field_group_count} field groups across {post_type_count} post types. Pro lets you sort, filter, and edit all of them from the list table.',
            contextFallback: 'ACF is active on your site. Pro lets you sort, filter, and edit all your custom fields from the list table.',
        },
        'ac-addon-woocommerce': {
            subhead: 'Search, filter, and bulk edit products and orders from one screen.',
            benefits: [
                'Filter products by any field and save views as reusable segments',
                'Bulk edit prices, stock, and SKUs — no record limits',
                'Update prices site-wide with the Price Wizard',
                'Schedule sales in bulk with the Sale Wizard',
            ],
            contextLine: 'Your store has {product_count} products. Pro removes the limits on bulk editing and adds search, filtering, and saved segments.',
            contextFallback: 'WooCommerce is active on your site. Pro adds sorting, filtering, and bulk editing to your product and order screens.',
        },
    };

    const content = richContent[integration.slug];

    function getContextLine(): string {
        if ( ! content) {
            return '';
        }

        if ( ! integration.site_context) {
            return content.contextFallback;
        }

        let line = content.contextLine;
        const ctx = integration.site_context;

        if (ctx.field_group_count !== undefined) {
            line = line.replace('{field_group_count}', String(ctx.field_group_count));
        }
        if (ctx.post_type_count !== undefined) {
            line = line.replace('{post_type_count}', String(ctx.post_type_count));
        }
        if (ctx.product_count !== undefined) {
            line = line.replace('{product_count}', String(ctx.product_count));
        }

        return line;
    }

    const contextLine = getContextLine();
</script>

{#if content}
<article class="acu-border acu-border-solid acu-rounded-[16px] acu-bg-white acu-flex acu-flex-col {isRecommended ? 'acu-border-[#B8C8E6] acu-shadow-lg' : 'acu-border-ui-border'}">
    <div class="acu-px-5 acu-pt-4 acu-pb-3 acu-rounded-t-[15px] acu-border-0 acu-border-b acu-border-solid acu-border-ui-border acu-flex acu-items-start acu-gap-4 {isRecommended ? 'acu-bg-gradient-to-br acu-from-[#F0F4FF] acu-to-[#F8F5FF]' : 'acu-bg-[#F4F7FB]'}">
        <img
            src="{addons.asset_location}/{integration.plugin_logo}"
            alt="{integration.title}"
            class="acu-w-[52px] acu-h-[52px] acu-rounded-[14px] acu-object-contain acu-flex-shrink-0"
        />
        <div class="acu-flex acu-flex-col acu-gap-1.5">
            <h3 class="acu-m-0 acu-text-lg acu-font-semibold">{integration.title}</h3>
            <div class="acu-flex acu-gap-1.5 acu-flex-wrap">
                <span class="acu-inline-flex acu-items-center acu-gap-1 acu-bg-[#E6F9F1] acu-text-[#216E39] acu-text-xs acu-font-bold acu-rounded-full acu-px-2.5 acu-py-1 acu-border acu-border-solid acu-border-[#B7E4CF]">
                    <AcIcon icon="yes" pack="dashicons" customClass="acu-text-[#00875A]"/>
                    {i18n.detected_on_site}
                </span>
                {#if isRecommended}
                <span class="acu-inline-flex acu-items-center acu-bg-[#FFF1D6] acu-text-[#8A5A00] acu-text-xs acu-font-bold acu-rounded-full acu-px-2.5 acu-py-1">
                    {i18n.recommended}
                </span>
                {/if}
            </div>
        </div>
    </div>

    <div class="acu-p-5 acu-flex acu-flex-col acu-gap-3.5 acu-flex-1">
        <p class="acu-m-0 acu-font-semibold acu-text-[15px] acu-leading-relaxed">{content.subhead}</p>

        <ul class="acu-m-0 acu-p-0 acu-list-none acu-text-[14px] acu-overflow-visible">
            {#each content.benefits as benefit}
            <li class="acu-mb-2 acu-pl-7 acu-relative">
                <span class="acu-absolute acu-left-0 acu-top-[2px] acu-w-5 acu-h-5 acu-rounded-full acu-bg-[#E6F9F1] acu-flex acu-items-center acu-justify-center">
                    <AcIcon icon="yes" pack="dashicons" size="sm" customClass="acu-text-[#216E39]"/>
                </span>
                {benefit}
            </li>
            {/each}
        </ul>

        {#if contextLine}
        <p class="acu-m-0 acu-pt-3 acu-border-0 acu-border-t acu-border-solid acu-border-ui-border acu-text-[13px] acu-text-[#5F6B7A] acu-italic">
            {contextLine}
        </p>
        {/if}

        <div class="acu-mt-auto acu-flex acu-gap-2.5 acu-pt-1">
            <AcButton type="pink" label="{i18n.buy_now}" href={addons.buy_url} target="_blank"/>
            <AcButton type="default" label="{i18n.see_what_you_get}" href={integration.external_link} target="_blank"/>
        </div>
    </div>
</article>
{/if}
