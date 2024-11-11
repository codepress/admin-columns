<script lang="ts">
    import AcToggle from "ACUi/element/AcToggle.svelte";
    import {afterUpdate, createEventDispatcher, onMount} from "svelte";

    export let value: string;
    export let config: AC.Column.Settings.ToggleSetting;
    export let disabled: boolean = false;

    const dispatch = createEventDispatcher();

    let checked = false;

    const check = (e: CustomEvent<string>) => {
        value = e.detail ? config.input.options[0].value : config.input.options[1].value;
        if (mustRefresh()) {
            dispatch('refresh');
        }
    }

    const mustRefresh = () => {
        return config.input.attributes?.refresh === 'config';
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
<div class="acu-pt-1">
<AcToggle checked={checked} on:input={check} {disabled}></AcToggle>
</div>