<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";
    import {createEventDispatcher, onDestroy, onMount} from "svelte";
    import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";

    export let config: AC.Column.Settings.NumberSettings;
    export let value: any;
    export let disabled: boolean = false;

    const dispatch = createEventDispatcher();

    onMount(() => {
        if (typeof value === 'undefined') {
            value = config.input.default ? config.input.default : '3';
        }
        console.log( config.input );
    });

    onDestroy(() => {
        dispatch('destroy', config);
    })

</script>

<ColumnSetting label={config.label} name="number" description={config.description}>
	<AcInputGroup containerClasses="-numeric" after={config.input.append}>
		<input type="number" bind:value={value} {disabled} step={config.input.step} min={config.input.min} max={config.input.max}>
	</AcInputGroup>
</ColumnSetting>