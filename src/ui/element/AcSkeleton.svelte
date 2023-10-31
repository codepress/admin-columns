<script lang="ts">
    export let count: number = 1;
    export let size: string = 'medium';
</script>
<style>
	.acui-skeleton {
		display: inline-flex;
		flex-direction: column;
		width: 100%;
		height: 30px;
		vertical-align: middle;
	}
	.acui-skeleton + .acui-skeleton {
		margin-top: .5rem;
	}
	.acui-skeleton-item {
		background: #eee;
		border-radius: 3px;
		&:before {
			content: "\A0";
			line-height: 1rem;
		}
	}
</style>
<div class="acui-skeleton">
	{#each Array( count ) as i}
		<div class="acui-skeleton-item">
		</div>
	{/each}
</div>