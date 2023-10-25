<script>
	import {createEventDispatcher} from "svelte";

	export let test;

	let d = createEventDispatcher();

	const hoihoi = () => {
		d('A', test );
	};
	$: test, hoihoi();


</script>
<strong>CUSTOM ELEMENT - { test } d</strong>
<input type="" bind:value={test}>