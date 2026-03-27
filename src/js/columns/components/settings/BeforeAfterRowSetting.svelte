<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";
    import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";
    type ColumnInputSetting = AC.Column.Settings.ColumnInputSetting;

    export let setting: AC.Column.Settings.AbstractColumnSetting;
    export let data: any;
    export let disabled: boolean = false;
    export let isSubComponent: boolean = false;

    $: children = (setting.children ?? []) as ColumnInputSetting[];
    $: beforeSetting = children[0];
    $: afterSetting = children[1];

    $: previewBefore = (beforeSetting?.input?.name ? data[beforeSetting.input.name] : '') ?? '';
    $: previewAfter = (afterSetting?.input?.name ? data[afterSetting.input.name] : '') ?? '';
    $: hasPreview = previewBefore !== '' || previewAfter !== '';
    $: previewText = previewBefore + 'value' + previewAfter;
</script>

<ColumnSetting label={setting.label} {isSubComponent} attributes={setting.attributes ?? {}}>
    <div class="acu-flex acu-flex-col acu-gap-2">
        <div class="acu-flex acu-items-center acu-gap-3">
        {#if beforeSetting?.input}
            <label class="acu-flex acu-items-center acu-gap-2 acu-flex-1">
                <span class="acu-text-sm acu-text-[#666] acu-whitespace-nowrap">
                    {beforeSetting.label ?? 'Before'}
                </span>
                <div class="acu-flex-1">
                    <AcInputGroup>
                        <input
                            type="text"
                            bind:value={data[beforeSetting.input.name]}
                            {disabled}
                            placeholder={beforeSetting.input.placeholder ?? ''}
                        />
                    </AcInputGroup>
                </div>
            </label>
        {/if}

        {#if afterSetting?.input}
            <label class="acu-flex acu-items-center acu-gap-2 acu-flex-1">
                <span class="acu-text-sm acu-text-[#666] acu-whitespace-nowrap">
                    {afterSetting.label ?? 'After'}
                </span>
                <div class="acu-flex-1">
                    <AcInputGroup>
                        <input
                            type="text"
                            bind:value={data[afterSetting.input.name]}
                            {disabled}
                            placeholder={afterSetting.input.placeholder ?? ''}
                        />
                    </AcInputGroup>
                </div>
            </label>
        {/if}
        </div>

        {#if hasPreview}
            <span class="acu-inline-flex acu-self-end acu-items-center acu-text-sm acu-text-[#888] acu-bg-[#f0f0f0] acu-rounded acu-px-2 acu-py-1" style="white-space: pre;">
                <span class="acu-text-[#444]">{previewBefore}</span><span class="acu-italic acu-text-[#2271b1]">value</span><span class="acu-text-[#444]">{previewAfter}</span>
            </span>
        {/if}
    </div>
</ColumnSetting>