<script lang="ts">
    import {createEventDispatcher, onDestroy, onMount} from "svelte";
    import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";

    export let config: AC.Column.Settings.TextSetting;
    export let value: any;
    export let disabled: boolean = false;

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


<AcInputGroup>
	<input type="text" bind:value={value} {disabled}>
</AcInputGroup>
