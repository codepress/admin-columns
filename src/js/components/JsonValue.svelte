<script>
	import JSONTree from 'svelte-json-tree';

	export let value;
	export let level;
</script>

<JSONTree
		{value}
		defaultExpandedLevel={level}
		--json-tree-number-color="#2e7d24"
/>

