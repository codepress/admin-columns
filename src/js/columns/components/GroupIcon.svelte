<script lang="ts">
    import {onMount} from "svelte";

    export let icon: string|null;
    export let defaultIcon: string;

    let type: 'dashicon' | 'svg' | 'cpacicon';

    onMount(() => {
        if( ! icon && defaultIcon ){
            icon = defaultIcon;
		}

        if (icon.startsWith('dashicons')) {
            type = 'dashicon';
        } else if (icon.startsWith('data:image')) {
            type = 'svg';
        } else if (icon.startsWith('cpac')) {
            type = 'cpacicon';
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
	<div class="ac-menu-image">
		<span class="{icon}"></span>
	</div>
{/if}
