<script>
	import ColumnSetting from "../ColumnSetting.svelte";
	import {onMount} from "svelte";
	import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";

	export let data;
	export let config;

	const changeUnit = () => {
		switch ( data[ 'width_unit' ] ) {
			case 'px':
				data[ 'width_unit' ] = '%';
				break;
			case '%':
				data[ 'width_unit' ] = 'px';
				break;
		}
	}

	onMount( () => {
		if ( typeof data.width === 'undefined' ) {
			data[ 'width' ] = '';
		}

		if ( typeof data.width_unit === 'undefined' ) {
			data[ 'width_unit' ] = 'px';
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
<ColumnSetting label={config.label}>
	<div class="width-setting">
		<div style="width: 120px;">
			<AcInputGroup>
				<input type="text" bind:value={data.width} placeholder="Auto">
				<div class="acui-input-group-text" on:click={changeUnit}>{data.width_unit}</div>
			</AcInputGroup>
		</div>
		<div class="width-setting__slider">
			<input type="range" bind:value={data.width} on:input={()=>console.log('update')}>
		</div>
	</div>
</ColumnSetting>
