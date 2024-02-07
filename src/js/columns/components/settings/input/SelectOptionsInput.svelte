<script lang="ts">
    import ColumnSetting from "../../ColumnSetting.svelte";
    import {createEventDispatcher, onDestroy, onMount} from "svelte";
    import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";
    import { uniqid } from "../../../../helpers/string";
    import { getColumnSettingsTranslation } from "../../../utils/global";

    type selectOptionType = {
        value: string
        label: string
        id: string
    }

    export let config: AC.Column.Settings.NumberSettings;
    export let value: string = '';


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

    const dispatchValue = () => {
        if( activeOptions.length ){
            value = JSON.stringify(getMappedValue());
        }
        
    }

    onMount(() => {
        let data:selectOptionType[] = [];
        
        if( value.length> 0 ){
            data = JSON.parse( value );
        }

        activeOptions = data.map(o => {
            return Object.assign(o, {id: uniqid()})
        });

        if (!activeOptions.length) {
            activeOptions.push(createRow());
        }

        activeOptions = activeOptions;


        jQuery(sortEl).sortable({
            axis: 'y',
            handle: '.-drag',
            stop: () => {
                let newIndex = [];
                let newItems = [];

                sortEl.childNodes.forEach(el => newIndex.push(el.dataset.id))

                newIndex.forEach(id => {
                    newItems.push(activeOptions.find(i => i.id === id));
                });

                activeOptions = newItems;
            }
        });

    });

    onDestroy(() => {
        dispatch('destroy', config);
    })

    $: activeOptions && dispatchValue();

</script>


    <div class="ac-setting-selectoptions" bind:this={sortEl}>
        {#each activeOptions as option, index(option.id)}
            <div class="ac-setting-selectoptions-row acu-flex acu-gap-2 acu-items-center acu-py-1" data-id={option.id}>
                <div class="ac-setting-selectoptions-row__drag acu-cursor-pointer">
                    <span class="cpacicon-move -drag"></span>
                </div>
                <div class="ac-setting-selectoptions-row__input acu-flex-grow">
                    <input type="text" bind:value={option.value} placeholder="Value" class="acu-w-full">
                </div>
                <div class="ac-setting-selectoptions-row__input acu-flex-grow">
                    <input type="text" bind:value={option.label} placeholder="Label" class="acu-w-full">
                </div>
                <div class="ac-setting-selectoptions-row__actions">
                    <button class="ac-setting-selectoptions-row__remove acu-border-none acu-cursor-pointer acu-p-[0] acu-bg-[transparent] acu-text-[#B4B4B4] hover:acu-text-notification-red" on:click|preventDefault={() => removeRow(option.id)}>
                        <span class="dashicons dashicons-remove acp-cf-delete-btn"></span>
                    </button>
                    <button class="ac-setting-selectoptions-row__add acu-border-none acu-cursor-pointer acu-p-[0] acu-bg-[transparent] acu-text-[#B4B4B4] hover:acu-text-notification-blue" on:click|preventDefault={() => addAfter(option.id)}>
                        <span class="dashicons dashicons-insert"></span>
                    </button>
                </div>
            </div>
        {/each}
    </div>
