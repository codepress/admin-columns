<script lang="ts">

    import {getSettingComponent} from "../helper";

    export let data: any;
    export let opened: boolean = false;

    const toggle = () => {
        opened = !opened;
    }


    let defaultConfig = [
        {type: 'type', key: 'type', label: 'Type'},
        {type: 'label', key: 'label', label: 'Label'}
    ];

    let config = defaultConfig;

    let titleConfig = [
        {type: 'type', key: 'type', label: 'Type'},
        {type: 'label', key: 'label', label: 'Label'},
        {type: 'toggle', key: 'editing', default: true, label: 'Editing'},
        {type: 'toggle', key: 'filtering', default: true, label: 'Filtering'},
        {type: 'toggle', key: 'search', default: true, label: 'Smart Filtering'},
    ]

    let actionConfig = [
        {type: 'type', key: 'type', label: 'Type'},
        {type: 'label', key: 'label', label: 'Label'},
        {type: 'toggle', key: 'use_icons', default: true, label: 'Editing'},
    ]

    const getComponent = (type: string) => {
        return getSettingComponent(type);
    }

    const determineConfig = (data) => {
        switch (data.type) {
            case 'title':
                config = titleConfig;
                break;
            case 'column-actions':
                config = actionConfig
                break;
            default:
                config = defaultConfig
        }
    }

    $: determineConfig(data);

</script>
<style>
	header {
		display: flex;
		padding: 10px 20px;
	}

	.label {
		flex-grow: 1;
	}

	.actions {
		justify-content: right;
	}

	.opened header {
		background: #00A0D2;
	}
</style>
<div class:opened={opened}>
	<header>
		<div class="label">
			<strong>{data.label}</strong>
		</div>
		<div class="actions">
			<button>a</button>
			<button>S</button>
			<button on:click={toggle} on:keydown>F</button>
		</div>
	</header>
	{#if opened}
		<div class="settings">
			<div style="border-bottom: 1px solid #000; padding: 10px 20px;">
				{JSON.stringify( data )}
			</div>

			{#each config as setting}
				<svelte:component this={getComponent(setting.type)} bind:value={data[setting.key]}
						config={setting}></svelte:component>
			{/each}
		</div>
	{/if}
</div>