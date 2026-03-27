<script lang="ts">
    import {IntegrationItem} from "../ajax/requests";
    import Integration from "./Integration.svelte";

    export let integrations: IntegrationItem[];
    export let pro: boolean;
    export let title: string;
    export let recommended: boolean = false;
</script>
<h3 class="acu-text-xl acu-font-light acu-mb-4">{title}</h3>
<div class="integrations-grid acu-mb-8">
	{#each integrations as integration }
		<Integration {integration} isPro={pro} {recommended}/>
	{/each}
</div>

<style>
	.integrations-grid {
		display: grid;
		grid-template-columns: repeat(3, minmax(0, 1fr));
		gap: 24px;
	}

	@media (max-width: 1200px) {
		.integrations-grid {
			grid-template-columns: repeat(2, minmax(0, 1fr));
		}
	}

	@media (max-width: 768px) {
		.integrations-grid {
			grid-template-columns: 1fr;
		}
	}
</style>
