<script lang="ts">
    export let count: number = 1;
    export let size: string = 'small';
    export let height: string = null;
    export let width: string = null;
    export let circle: boolean = false;
</script>
<div
		class="acui-skeleton"
		class:-circle={circle}
		class:-large={size==='large'}
		class:-medium={size==='medium'}

>
	{#each Array( count ) as i}
		<div class="acui-skeleton-item" style:height={height} style:width={width}>
		</div>
	{/each}
</div>