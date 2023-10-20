<script>
	import ColumnSetting from "../ColumnSetting.svelte";
	import {onMount} from "svelte";

	export let data;
	export let config;

	onMount( () => {
		if ( typeof data.width === 'undefined' ) {
			data[ 'width' ] = '0';
		}

		if ( typeof data.width_unit === 'undefined' ) {
			data[ 'width_unit' ] = 'px';
		}
	} )
</script>

<style>
	.width-setting {
		display: flex;
	}
</style>
<ColumnSetting label={config.label}>
	<div class="width-setting">
		<div class="width-setting__value">
			[{data.width} {data.width_unit}]
		</div>
		<div class="width-setting__slider">
			<input type="range" bind:value={data.width}>
		</div>
		<div class="width-setting__type">
			<input type="radio" value="%" bind:group={data.width_unit}>%
			<input type="radio" value="px" bind:group={data.width_unit}>px
		</div>
	</div>
</ColumnSetting>
