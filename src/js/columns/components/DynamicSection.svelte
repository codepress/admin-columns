<script>
	import {onDestroy, onMount} from 'svelte'

	export {component as this}
	export let data;

	let component
	let target
	let cmp

	const create = () => {
		cmp = new component( {
			target,
			props : {
				data : data
			},
		} );
	}

	const cleanup = () => {
		if ( !cmp ) return
		cmp.$destroy()
		cmp = null
	}

	$: if ( cmp ) {
		cmp.$set( $$restProps )
	}

	onMount( () => {
		cleanup()
		create()
	} )
	onDestroy( cleanup )
</script>

<div bind:this={target}/>
