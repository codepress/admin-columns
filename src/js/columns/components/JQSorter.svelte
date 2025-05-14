<script lang="ts">
    import {onMount, tick} from "svelte";

    export let items: any[] = [];
    export let onSort: (from: number, to: number) => void;
    export let itemKey: (item: any) => string;

    let container: HTMLElement;

    const makeSortable = () => {
        const JQ: any = jQuery as any;
        JQ(container).sortable({
            axis: 'y',
            containment: JQ(container),
            handle: '.ac-column-header__move',
            start: (e: Event, ui: any) => {
                ui.item.data('start-index', ui.item.index());
            },
            stop: async (e: Event, ui: any) => {
                const from = ui.item.data('start-index');
                const to = ui.item.index();
                if (from !== undefined && to !== undefined && from !== to) {
                    await tick();
                    onSort(from, to);
                }
            }
        });
    };

    onMount(() => {
        setTimeout(makeSortable, 100);
    });
</script>

<div bind:this={container}>
	{#each items as item (itemKey( item ))}
		<slot name="item" item={item}/>
	{/each}
</div>