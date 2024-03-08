<script lang="ts">
    import {onMount} from "svelte";
    import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";

    export let data: any;
    export let config: AC.Column.Settings.WidthSetting;
    export let disabled: boolean = false;

    const keyWidth = 'width';
    const keyUnit = 'width_unit';

    let maxWidth = 100;

    const updateMaxWidth = () => {
        maxWidth = data[keyUnit] === '%' ? 100 : 800;

        if (data[keyUnit] === '%' && data[keyWidth] > 100) {
            data[keyWidth] = 100;
        }
    }

    const changeUnit = () => {
        switch (data[keyUnit]) {
            case 'px':
                data[keyUnit] = '%';
                maxWidth = 400;
                break;
            case '%':
                data[keyUnit] = 'px';
                maxWidth = 400;
                break;
        }
        updateMaxWidth();
    }
    onMount(() => {
        if (typeof data[keyWidth] === 'undefined') {
            data[keyWidth] = '';
        }

        if (typeof data[keyUnit] === 'undefined') {
            data[keyUnit] = 'px';
        }

        updateMaxWidth();
    });

</script>


<div class="acu-flex acu-items-center acu-gap-3">
	<div class="acu-w-[120px]">
		<AcInputGroup>
			<input type="text" bind:value={data[keyWidth]} placeholder="Auto" {disabled}>
			<div role="none" class="acui-input-group-text acu-cursor-pointer acu-text-link hover:acu-text-link-hover" on:click={changeUnit} on:keypress>{data[ keyUnit ]}</div>
		</AcInputGroup>
	</div>
	<div class="acu-flex-grow">
		<input type="range" min="0" max={maxWidth} step="1" bind:value={data[keyWidth]} {disabled} class="acu-w-full">
	</div>
</div>
