<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";
    import {createEventDispatcher, onDestroy, onMount} from "svelte";
    import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";

    export let config: AC.Column.Settings.TextSetting;
    export let value: any;

    const dispatch = createEventDispatcher();

    onMount(() => {
        if (typeof value === 'undefined') {
            value = config.input.default ? config.input.default : '';
        }
    });

    onDestroy(() => {
        dispatch('destroy', config);
    });

</script>

<ColumnSetting label={config.label}>
	<AcInputGroup>
		<input type="text" bind:value={value}>
	</AcInputGroup>
</ColumnSetting>