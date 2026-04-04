<script lang="ts">
    import {onMount} from "svelte";
    import {MaterialIcon} from "@ac/material-icons/src";
    import type {IconName} from "@ac/material-icons/src/icons";

    export let icon: string | null;
    export let defaultIcon: string;

    let type: 'dashicon' | 'svg' | 'cpacicon' | 'material';
    let materialIconName: IconName;

    onMount(() => {
        if( ! icon ){
            icon = defaultIcon;
		}

        if (icon.startsWith('dashicons')) {
            type = 'dashicon';
        } else if (icon.startsWith('data:image')) {
            type = 'svg';
        } else if (icon.startsWith('cpac')) {
            type = 'cpacicon';
        } else if (icon.startsWith('material')) {
            type = 'material';
            materialIconName = icon.replace('material-', '') as IconName;
        }
    });
</script>

{#if type === 'dashicon' }
	<div class="ac-menu-image">
	<span class="dashicons {icon}"></span>
	</div>
{/if}

{#if type === 'svg' }
	<div class="ac-menu-image svg"
			style="background-image: url(&quot;{icon}&quot;) !important;"
			aria-hidden="true">

	</div>
{/if}

{#if type === 'cpacicon' }
	<div class="ac-menu-image acu-text-[18px]">
		<span class="{icon}"></span>
	</div>
{/if}

{#if type === 'material' }
	<div class="ac-menu-image acu-text-[18px]">
		<MaterialIcon icon={materialIconName}></MaterialIcon>
	</div>
{/if}
