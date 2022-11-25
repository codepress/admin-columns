<script>
	import JSONTree from 'svelte-json-tree';

	export let value;
	export let level;
</script>

<JSONTree
		{value}
		defaultExpandedLevel={level}
/>

