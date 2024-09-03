<script lang="ts">
    import {onMount} from "svelte";

    export let value: string;
    export let fallback: string = '';

    let label: string = '';
    let element: HTMLElement;


    const determineBoundingBox = () => {
        if (element) {
            let width = element.getBoundingClientRect().width;

            if (width > 4) {
                return;
            }
            label = element.innerText;

            if (label.length === 0) {
                label = fallback;
            }
        }
    }

    onMount(() => {
        setTimeout(() => {
            determineBoundingBox();
        }, 50);
    })

    $: {
        label = value;
        setTimeout(() => {
            determineBoundingBox();
        }, 50);

    }

</script>
<span bind:this={element}>{@html label}</span>