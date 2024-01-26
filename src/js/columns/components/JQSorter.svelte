<script lang="ts">
    import {createEventDispatcher, onMount} from "svelte";

    declare const jQuery: any;

    export let items: any[] = [];
    export let key: string;
    export let axis: string | null = null;

    const dispatch = createEventDispatcher();

    let start: number | null = null;
    let end: number | null = null;

    const moveArrayElement = (from: number, to: number) => {
        let tempItems = items;
        const item = tempItems[from];
        tempItems.splice(from, 1);
        tempItems.splice(to, 0, item);

        items = tempItems;
    }

    function create(node: Node) {
        const defaultOptions: any = {
            start: (e: Event, ui: any) => {
                start = parseInt(ui.item.index());
            },
            stop: (e: Event, ui: any) => {
                end = ui.item.index();

                if (start !== null && end !== null) {
                    moveArrayElement(start, end);
                    start = null;
                    end = null;
                    dispatch('change', items)
                }
            }
        };

        if (axis) {
            defaultOptions['axis'] = axis
        }

        jQuery(node).sortable(defaultOptions);

        return {};
    }

    onMount(() => {

    })

</script>

<div use:create>
	{#each items as item, i(item[ key ])}
		<slot {item}/>
	{/each}
</div>