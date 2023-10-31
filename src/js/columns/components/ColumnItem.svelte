<script lang="ts">

    import {getSettingComponent} from "../helper";
    import {openedColumnsStore} from "../store/opened-columns";
    import {slide} from 'svelte/transition';
    import {onMount} from "svelte";

    export let data: any;
    export let config = [];

    const toggle = () => {
        openedColumnsStore.toggle(data.name);
    }

    const getComponent = (type: string) => {
        return getSettingComponent(type);
    }

    $: opened = $openedColumnsStore.includes(data.name);
</script>

<div class="ac-column" class:opened={opened}>
	<header class="ac-column-header">
		<div class="ac-column-header__label">
			<strong on:click={toggle} on:keydown role="none">{@html data.label}</strong>
		</div>
		<div class="ac-column-header__actions">
			[ {data.width} {data.width_unit} ]
			<button class="ac-header-toggle">
				<span class="dashicons dashicons-filter on" title="Enable Filtering"></span>
			</button>
			<button class="ac-header-toggle -active">
				<span class="dashicons dashicons-filter on" title="Enable Filtering"></span>
			</button>
		</div>
		<div class="ac-column-header__open-indicator">
			<button class="ac-open-indicator" class:-open={opened} on:click={toggle}>
				<span class="dashicons dashicons-arrow-down-alt2"></span>
			</button>
		</div>
	</header>

	{#if opened && config !== null && typeof config !== 'undefined' }
		<div class="ac-column-settings" transition:slide>

			{#each config as setting}
				<svelte:component
						this={getComponent(setting.type)}
						bind:data={data}
						config={setting}>

				</svelte:component>
			{/each}
		</div>
	{/if}
</div>