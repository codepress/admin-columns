<script lang="ts">
    import {createEventDispatcher, onDestroy, onMount} from "svelte";

    export let config: AC.Column.Settings.TextSetting;
    export let value: any;

    const dispatch = createEventDispatcher();

    onMount(() => {
        if (typeof value === 'undefined') {
            value = config?.input?.default ? config.input.default : '';
        }
    });

    onDestroy(() => {
        dispatch('destroy', config);
    });

</script>

<input type="hidden" bind:value={value}>

