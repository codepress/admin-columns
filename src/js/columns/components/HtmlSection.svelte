<script>

	import {onMount} from "svelte";

	export let component;
	let container;

	onMount(()=>{
		container.append( component );
	});

</script>
<div bind:this={container}>

</div>