<script lang="ts">
    import AcToggle from "ACUi/element/AcToggle.svelte";
    import {afterUpdate, onMount} from "svelte";

    export let value: string;
    export let config: AC.Column.Settings.ToggleSetting;
    export let disabled: boolean = false;

    let checked = false;

    const check = (e: CustomEvent<string>) => {
        value = e.detail ? config.input.options[0].value : config.input.options[1].value;
    }

    afterUpdate(() => {
        checked = value === config.input.options[0].value;
    })

    onMount(() => {
        if (typeof value === 'undefined') {
            value = config.input.default ?? config.input.options[1].value
        }

        checked = value === config.input.options[0].value;
    });

</script>

<AcToggle checked={checked} on:input={check} {disabled}></AcToggle>
