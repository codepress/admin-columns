<script lang="ts">

    import {getSettingComponent} from "../helper";
    import {openedColumnsStore} from "../store/opened-columns";
    import { slide } from 'svelte/transition';

    export let data: any;
    export let config;

    const toggle = () => {
        openedColumnsStore.toggle(data.name);
    }


    const getComponent = (type: string) => {
        return getSettingComponent(type);
    }


    $: opened = $openedColumnsStore.includes(data.name);
</script>
<style>
	header {
		display: flex;
		padding: 10px 30px;
		align-items: center;
	}

	.ac-column {
		border-bottom: 1px solid #CBD5E1;
	}

	.ac-column.opened header {
		border-bottom: 2px solid #CBD5E1;
		border-top: 1px solid #CBD5E1;
	}

	.ac-column-header__label {
		flex-grow: 1;
	}

	.ac-column-header__actions {
		justify-content: right;
		display: flex;
		align-items: center;
		gap: 5px;
	}

	.settings {
		padding: 20px 0;
	}

	.opened header {
		background: #EAF2FA;
	}


	.ac-column-header__open-indicator {
		width: 30px;
		display: flex;
		justify-content: right;
	}

	.ac-open-indicator {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 20px;
		height: 20px;
		transition: all .4s;
		cursor: pointer;
	}

	.ac-open-indicator.-open {
		transform: rotate(180deg);
	}

	.ac-header-toggle {
		border: 1px solid #C3CBD7;
		cursor: pointer;
		width: 34px;
		height: 34px;
		background: transparent;
		color: #C3CBD7;
		border-radius: 5px;
	}
	.ac-header-toggle.-active {
		background: #E2E8F0;
		border-color: #E2E8F0;
		color: #475569;
	}
</style>
<div class="ac-column" class:opened={opened}>
	<header class="ac-column-header">
		<div class="ac-column-header__label">
			<strong on:click={toggle} on:keydown role="button" tabindex="0">{data.label}</strong>
		</div>
		<div class="ac-column-header__actions">
			<button class="ac-header-toggle">
				<span class="dashicons dashicons-filter on" title="Enable Filtering"></span>
			</button>
			<button class="ac-header-toggle -active">
				<span class="dashicons dashicons-filter on" title="Enable Filtering"></span>
			</button>

		</div>
		<div class="ac-column-header__open-indicator">
			<div class="ac-open-indicator" class:-open={opened} on:click={toggle} role="button" tabindex="0" on:keydown>
				<span class="dashicons dashicons-arrow-down-alt2"></span>
			</div>
		</div>
	</header>
	{#if opened && config !== null}
		<div class="settings" transition:slide>
			{#each config as setting}
				<svelte:component this={getComponent(setting.type)} bind:value={data[setting.key]} bind:data={data}
						config={setting}></svelte:component>
			{/each}
		</div>
	{/if}
</div>