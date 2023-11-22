<script lang="ts">
	import ColumnSetting from "../ColumnSetting.svelte";
    import {createEventDispatcher, onDestroy, onMount} from "svelte";
	import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";

	export let config: AC.Column.Settings.NumberSettings;
	export let value: any;

    const dispatch = createEventDispatcher();

	onMount( () => {
		if ( typeof value === 'undefined' ) {
			value = config.input.default ? config.input.default : '';
		}
	} );

    onDestroy( () => {
        dispatch('destroy', config );
	})

</script>

<ColumnSetting label={config.label} name="number">
	<AcInputGroup>
		<input type="number" bind:value={value} step={config.input.step} min={config.input.min} max={config.input.max}>
	</AcInputGroup>
</ColumnSetting>