<script lang="ts">
    import Integration from "./Integration.svelte";
    import {IntegrationItem} from "../ajax/requests";

    export let integrations: IntegrationItem[];
    export let pro: boolean;
    export let title: string;
</script>
<h3 class="acu-text-xl acu-font-light">{title}</h3>
<div class="acu-flex acu-gap-8 acu-flex-wrap acu-mb-8">
	{#each integrations as integration }
		<Integration {integration} isPro={pro}/>
	{/each}
</div>