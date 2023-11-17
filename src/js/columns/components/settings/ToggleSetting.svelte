<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";
    import AcToggle from "ACUi/element/AcToggle.svelte";
    import {afterUpdate, onMount} from "svelte";

    export let value: string;
    export let config: AC.Column.Settings.ToggleSetting;

    let label = config.label ?? '';
    let checked = false;

    const check = (e:CustomEvent<string>) => {
        value = e.detail ? config.input.options[0].value : config.input.options[1].value;
    }

    afterUpdate(() => {
        checked = value === config.input.options[0].value;
    })

    onMount(() => {
        if (typeof value === 'undefined') {
            value = config.input.options[0].value
        }

        checked = value === config.input.options[0].value;
    });

</script>

<ColumnSetting {label}>
	<AcToggle checked={checked} on:input={check}></AcToggle>
</ColumnSetting>