<script context="module">
	// this block only runs once, when the module is loaded (same as
	// if it was code in the root of a .js file)

	// this variable will be visible in all App instances
	const appModules = {}

	console.log( 'AppModule Set' )
	// make the appModules variable visible to the plugins
	window.appModules = appModules

	// you can also have static function here
	export function registerPlugin( name, plugin ) {
		appModules[ name ] = plugin
	}
</script>

<script>
	import DynamicComponent from "./DynamicComponent.svelte";

	let ready
	let t = [];
	let variable = 's';

	document.onreadystatechange = function() {
		if ( document.readyState === 'complete' ) {
			Object.values( appModules ).forEach( d => {
				t.push( d )
			} )
			ready = true
		}
	}
</script>

{#if ready}
	READY - {variable} -
	{#each t as d}
		<DynamicComponent this={d} bind:test={variable}></DynamicComponent>
	{/each}
{/if}