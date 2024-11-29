<script>

	import {onMount} from "svelte";

	export let component;
	let container;

	onMount(()=>{
		container.replaceWith( component );
	});

</script>
<div bind:this={container}>

</div>