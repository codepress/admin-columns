<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";
    import {createEventDispatcher, onDestroy, onMount} from "svelte";
    import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";
    import { uniqid } from "../../../helpers/string";
    import { getColumnSettingsTranslation } from "../../utils/global";


    type selectOptionType = {
        value: string
        label: string
        id: string
    }

    export let config: AC.Column.Settings.NumberSettings;
    export let value: any = [];


    const dispatch = createEventDispatcher();
    const i18n = getColumnSettingsTranslation();

    let sortEl: HTMLElement|null;
    let activeOptions: selectOptionType[] = [];

    const createRow = () => {
        return {
            value: '',
            label: '',
            id: uniqid()
        }
    }

    const addRow = () => {
        activeOptions.push(createRow());
        activeOptions = activeOptions;
    }

    const removeRow = (id: string) => {
        activeOptions = activeOptions.filter(f => f.id !== id);

        if (!activeOptions.length) {
            activeOptions.push(createRow());
        }
    }

    const addAfter = (id: string) => {
        const afterIndex = activeOptions.findIndex(d => d.id === id) + 1;

        activeOptions.splice(afterIndex, 0, createRow());
        activeOptions = activeOptions;

    }


    const getMappedValue = () => {
        return activeOptions.map(ao => {
            return {
                value: ao.value,
                label: ao.label
            }
        });
    }


    onMount(() => {
        if( typeof value === undefined )

        activeOptions = value.map(o => {
            return Object.assign(o, {id: uniqid()})
        });

        if (!activeOptions.length) {
            activeOptions.push(createRow());
        }
    });

    onDestroy(() => {
        dispatch('destroy', config);
    })

</script>

<ColumnSetting label={config.label} name="number" description={config.description}>
	


    <div class="ac-setting-selectoptions" bind:this={sortEl}>
        {#each activeOptions as option, index(option.id)}
            <div class="ac-setting-selectoptions-row" data-id={option.id}>
                <div class="ac-setting-selectoptions-row__drag">
                    <span class="cpacicon-move -drag"></span>
                </div>
                <div class="ac-setting-selectoptions-row__input">
                    <input type="text" bind:value={option.value} placeholder="Value">
                </div>
                <div class="ac-setting-selectoptions-row__input">
                    <input type="text" bind:value={option.label} placeholder="Label">
                </div>
                <div class="ac-setting-selectoptions-row__actions">
                    <button class="ac-setting-selectoptions-row__remove" on:click|preventDefault={() => removeRow(option.id)}>
                        <span class="dashicons dashicons-remove acp-cf-delete-btn"></span>
                    </button>
                    <button class="ac-setting-selectoptions-row__add" on:click|preventDefault={() => addAfter(option.id)}>
                        <span class="dashicons dashicons-insert"></span>
                    </button>
                </div>
            </div>
        {/each}
    </div>


</ColumnSetting>