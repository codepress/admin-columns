<script lang="ts">
    import {IntegrationItem} from "../ajax/requests";
    import {getAddonsConfig} from "../global";

    export let integration: IntegrationItem;

    const addons = getAddonsConfig();
</script>

<div class="acu-flex acu-items-center acu-gap-3 acu-px-4 acu-py-3 acu-border acu-border-solid acu-border-ui-border acu-rounded-[10px] acu-bg-[#F8FAFC]">
    <img
        src="{addons.asset_location}/{integration.plugin_logo}"
        alt="{integration.title}"
        class="acu-w-7 acu-h-7 acu-rounded-[7px] acu-object-contain"
    />
    <span class="acu-text-sm acu-font-semibold">{integration.title}</span>
</div>
