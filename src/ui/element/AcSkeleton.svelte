<script lang="ts">
    export let count: number = 1;
</script>
<style>
	.acui-skeleton {
		display: inline-flex;
		flex-direction: column;
		width: 100%;
		vertical-align: middle;
	}
	.acui-skeleton-item {
		background: #eee;
	}
</style>
<div class="acui-skeleton">
	{#each Array( count ) as i}
		<div class="acui-skeleton-ui">
		</div>
	{/each}
</div>