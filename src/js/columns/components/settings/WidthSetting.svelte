<script lang="ts">
	import ColumnSetting from "../ColumnSetting.svelte";
	import {onMount} from "svelte";
	import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";

	export let data:any;
	export let config: AC.Column.Settings.WidthSetting;
    export let disabled: boolean = false;

    const keyWidth = config.name;
    const keyUnit = config.children[1].name;

	const changeUnit = () => {
		switch ( data[ keyUnit ] ) {
			case 'px':
				data[ keyUnit ] = '%';
				break;
			case '%':
				data[ keyUnit ] = 'px';
				break;
		}
	}
	onMount( () => {
		if ( typeof data[keyWidth] === 'undefined' ) {
			data[ keyWidth ] = '';
		}

		if ( typeof data[keyUnit] === 'undefined' ) {
			data[ keyUnit ] = 'px';
		}
	} );
</script>

<style>
	.width-setting {
		display: flex;
		align-items: center;
		gap: 30px;
	}

	.width-setting__slider {
		flex-grow: 1;
	}

	.width-setting__slider input[type=range] {
		width: 100%;
		accent-color: var(--ac-link);
	}
</style>
<ColumnSetting label={config.label} description={config.description} name="width">
	<div class="width-setting">
		<div style="width: 120px;">
			<AcInputGroup>
				<input type="text" bind:value={data[keyWidth]} placeholder="Auto" {disabled}>
				<div role="none" class="acui-input-group-text" on:click={changeUnit} on:keypress>{data[keyUnit]}</div>
			</AcInputGroup>
		</div>
		<div class="width-setting__slider">
			<input type="range" bind:value={data[keyWidth]} {disabled}>
		</div>
	</div>
</ColumnSetting>
