<script>
	import {onDestroy, onMount} from 'svelte'

	let component
	export {component as this}

	export let data;
	export let config;
	let target
	let cmp


	const create = () => {
		cmp = new component( {
			target,
			props : {
				data, config
			}
		} );

		cmp.$on( 'change', ( d ) => {
			data = d.detail
		} )
	}

	const cleanup = () => {
		if ( !cmp ) return
		cmp.$destroy()
		cmp = null
	}

	onMount( () => {
		cleanup()
		create()
	} )

	$: if ( cmp ) {
		console.log( 'restPropes' );
		//cmp.$set( $$restProps )
	}

	onDestroy( cleanup )
</script>

<div bind:this={target}/>
