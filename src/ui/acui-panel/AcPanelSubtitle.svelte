<script lang="ts">
    export let type: 'h3' | 'h2' = 'h2'
	export let subtitle: string;
</script>


<span class={type==='h2'? 'acu-pt-2 acu-block' : 'acu-pt-1 acu-block'}>{subtitle}</span>
