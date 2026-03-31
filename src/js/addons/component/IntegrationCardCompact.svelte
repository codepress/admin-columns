<script lang="ts">
    import {IntegrationItem} from "../ajax/requests";
    import {getAddonsConfig, getAddonsTranslation} from "../global";
    import AcButton from "ACUi/element/AcButton.svelte";
    import AcBadge from "ACUi/element/AcBadge.svelte";
    import AcIcon from "ACUi/AcIcon.svelte";

    export let integration: IntegrationItem;

    const addons = getAddonsConfig();
    const i18n = getAddonsTranslation();
</script>

<div class="acu-flex acu-items-center acu-gap-4 acu-border acu-border-solid acu-border-ui-border acu-rounded-[14px] acu-bg-white acu-px-5 acu-py-4">
    <img
        src="{addons.asset_location}/{integration.plugin_logo}"
        alt="{integration.title}"
        class="acu-w-10 acu-h-10 acu-rounded-[10px] acu-object-contain acu-flex-shrink-0"
    />
    <div class="acu-flex-1 acu-min-w-0">
        <h4 class="acu-m-0 acu-text-[15px] acu-font-semibold">{integration.title}</h4>
        <p class="acu-m-0 acu-text-[13px] acu-text-[#5F6B7A] acu-line-clamp-1">{integration.description}</p>
    </div>
    <AcBadge type="success">
        <AcIcon icon="yes" pack="dashicons" customClass="acu-text-[#00875A]"/>
        {i18n.detected_on_site}
    </AcBadge>
    <AcButton type="pink" label="{i18n.buy_now}" href={addons.buy_url} target="_blank" size="small"/>
</div>
