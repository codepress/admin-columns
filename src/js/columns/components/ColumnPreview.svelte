<script lang="ts">
    import {onDestroy} from "svelte";
    import {currentListKey} from "../store";
    import {columnPreviewPayload, getColumnPreview} from "../ajax/ajax";

    export let data: any;
    export let label: string = '';
    export let description: string = '';
    export let visible: boolean = true;

    let preview: columnPreviewPayload | null = null;
    let timer: ReturnType<typeof setTimeout> | undefined;
    let request: number = 0;

    const load = () => {
        const current = ++request;

        getColumnPreview(data, $currentListKey).then(response => {
            if (current !== request) return;
            preview = response.data.success ? response.data.data : null;
            visible = preview?.supported ?? false;
        }).catch(() => {
            if (current === request) visible = false;
        });
    }

    const schedule = (_data: string) => {
        clearTimeout(timer);
        timer = setTimeout(load, preview ? 400 : 0);
    }

    $: schedule(JSON.stringify(data));
    $: rows = preview && preview.items.length ? preview.items : [null];

    onDestroy(() => clearTimeout(timer));
</script>

{#if visible}
    <div class="acp-column-setting -type -preview @lg/columnrow:acu-flex acu-px-6 acu-mb-2 acu-items-center">
        <div class="acp-column-setting__label @lg/columnrow:acu-w-[200px] acu-flex-shrink-0 acu-mr-2 acu-text-[#757575]">
            Preview
        </div>
        <div class="acp-column-setting__value acu-flex-grow acu-flex acu-items-center acu-gap-4">
            <table class="ac-column-preview">
                <thead>
                    <tr>
                        <th class="-side"><span class="ac-column-preview__bar -w60"></span></th>
                        <th class="-column">{@html label}</th>
                        <th class="-side"><span class="ac-column-preview__bar -w60"></span></th>
                    </tr>
                </thead>
                <tbody>
                    {#each rows as item, i}
                        <tr>
                            <td class="-side">
                                <span class="ac-column-preview__bar -w75"></span>
                                {#if i === 0}<span class="ac-column-preview__bar -w45"></span>{/if}
                            </td>
                            <td class="-column" title={item?.label ?? ''}>
                                {#if item}
                                    <div class="ac-column-preview__value">{@html item.value}</div>
                                {:else if preview}
                                    <span class="acu-text-[#757575]">&ndash;</span>
                                {:else}
                                    <span class="ac-column-preview__bar -w60 acu-mx-auto"></span>
                                {/if}
                            </td>
                            <td class="-side">
                                <span class="ac-column-preview__bar -w75"></span>
                                {#if i === 0}<span class="ac-column-preview__bar -w45"></span>{/if}
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
            {#if description}
                <span class="acu-text-[#757575]">{description}</span>
            {:else if preview && !preview.items.length}
                <span class="acu-text-[#757575]">No value in the {preview.sampled} most recent items.</span>
            {/if}
        </div>
    </div>
{/if}

<style>
    .ac-column-preview {
        flex-shrink: 0;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #dcdcde;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
    }

    .ac-column-preview th,
    .ac-column-preview td {
        padding: 10px 14px;
        vertical-align: middle;
        text-align: center;
        border: 0;
        border-top: 1px solid #dcdcde;
    }

    .ac-column-preview thead th {
        border-top: 0;
        padding-top: 8px;
        padding-bottom: 8px;
        background: #f6f7f7;
    }

    .ac-column-preview .-side {
        width: 72px;
    }

    .ac-column-preview .-column {
        min-width: 110px;
        border-left: 1px solid #dcdcde;
        border-right: 1px solid #dcdcde;
        background: #f0f6fc;
    }

    .ac-column-preview thead .-column {
        background: #e5effa;
        color: var(--ac-link, #2271b1);
        font-weight: 600;
        white-space: nowrap;
    }

    .ac-column-preview__value {
        display: flex;
        align-items: center;
        justify-content: center;
        max-height: 60px;
        overflow: hidden;
        line-height: 1.4;
    }

    .ac-column-preview__value :global(img) {
        display: block;
        max-height: 60px;
        width: auto;
        margin: 0;
    }

    .ac-column-preview__bar {
        display: block;
        height: 6px;
        border-radius: 3px;
        background: #e2e4e7;
    }

    .ac-column-preview__bar + .ac-column-preview__bar {
        margin-top: 6px;
    }

    .ac-column-preview__bar.-w75 { width: 75%; }
    .ac-column-preview__bar.-w60 { width: 60%; }
    .ac-column-preview__bar.-w45 { width: 45%; }
</style>
