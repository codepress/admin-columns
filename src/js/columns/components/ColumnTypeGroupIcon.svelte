<script lang="ts">
    import {getColumnSettingsConfig} from "../utils/global";

    export let group_key: string;

    const column_groups = getColumnSettingsConfig().column_groups;

    let group = column_groups.find(c => c.slug === group_key);
    let icon: string = '';

    if (group) {
        icon = group.icon;
    }
</script>

{#if icon }
	<img src="{icon}" alt="" class="column-group-icon -{group_key}" style="position: absolute; right:
	calc(100% - 1px); width: 20px; height: 20px;" />
{/if}

