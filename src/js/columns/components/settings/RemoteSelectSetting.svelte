<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";
    import Select from "svelte-select"
    import {createEventDispatcher, onDestroy, onMount} from "svelte";
    import {SvelteSelectItem} from "../../../types/select";
    import {getRemoteSelectOptions} from "../../ajax/settings";
    import {currentListKey} from "../../store/current-list-screen";

    export let config: AC.Column.Settings.SelectSetting;
    export let data: any;
    export let disabled: boolean = false;
    export let value: string | undefined | number;

    const dispatch = createEventDispatcher();

    let listOpen: boolean;
    let selectValue: SvelteSelectItem | null;
    let searchTerm: string = '';
    let options: SvelteSelectItem[] = [];
    let originalOptions: SvelteSelectItem[] = [];
    let allowCreation: boolean = true;
    let emptyElement: HTMLElement | null;

    const getValue = (value: string): SvelteSelectItem => {
        console.log(options);
        const found = options.find(o => o.value === value);

        return found ? found : {value: value, label: value};
    }

    onMount(() => {
        getRemoteSelectOptions(config.input.data.ajax_handler, $currentListKey).then((response) => {
            if (response.data.success) {
                originalOptions = response.data.data.options;
                options = originalOptions;
            }
        });

        if (typeof value === 'undefined') {
            if (config.input.default) {
                selectValue = getValue(config.input.default);
            }

            value = config.input.default ?? '';
        } else {
            selectValue = getValue(value.toString());
        }
    })

    const selectEnter = (e) => {
        if (e.key === 'Enter' && allowCreation && emptyElement !== null) {
            selectNewItem();
        }
    }

    const selectNewItem = () => {
        options = originalOptions;
        options.push({value: searchTerm, label: searchTerm})
        selectValue = {value: searchTerm, label: searchTerm}
        value = searchTerm;
        listOpen = false;
    }

    onDestroy(() => {
        dispatch('destroy', config);
    });

    const changeValue = (e: CustomEvent<SvelteSelectItem>) => {
        value = e.detail.value;
    }

    const groupBy = (item: SvelteSelectItem) => item.group;
</script>


<ColumnSetting
	label={config.label}
	description={config.description}
	config={config}
	children={config.children ?? []}
	bind:data={data}
	name="select">
	<div on:keyup={selectEnter} role="none">
		<Select
			--list-max-height="400px"
			class="-acui"
			clearable={false}
			items={options}
			showChevron
			value={selectValue}
			{listOpen}
			{groupBy}
			{disabled}
			bind:filterText={searchTerm}
			on:change={ changeValue }
		>


			<div slot="empty">
				{#if allowCreation}
					<div class="list-item svelte-empty" bind:this={emptyElement}>
						Press enter to add option
					</div>
				{:else}
					<div class="list-item svelte-empty">No results found</div>
				{/if}
			</div>

		</Select>
	</div>
</ColumnSetting>