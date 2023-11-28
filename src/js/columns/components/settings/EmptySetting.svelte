<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";
    import {createEventDispatcher, onDestroy} from "svelte";

    export let config: AC.Column.Settings.TextSetting;
    export let data: any;

    const dispatch = createEventDispatcher();

    onDestroy(() => {
        dispatch('destroy', config);
    });

</script>

<ColumnSetting label={config.label} description={config.description} name="text"
		config={config}
		bind:data={data}>
</ColumnSetting>