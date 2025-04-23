<script lang="ts">
    import { onMount } from "svelte";
    export let id: string;

    let targetEl: HTMLDivElement;

    onMount(() => {
        const tpl = document.getElementById(id) as HTMLTemplateElement | null;

        if (tpl && tpl.content) {
            targetEl.appendChild(tpl.content.cloneNode(true));
        } else {
            console.warn(`Template with ID "${id}" not found.`);
        }
    });
</script>

<div bind:this={targetEl}></div>