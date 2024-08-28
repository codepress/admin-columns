<script lang="ts">
	import {onMount} from "svelte";

    export let title: string;
    let element: HTMLElement | null = null;

    onMount( () => {
        const menuBar = document.querySelector('#acMenu');
        if( menuBar && element ) {
            menuBar.after( element );
		}
	})
</script>
<div
	class="ac-header-bar acu-bg-[white] acu-px-6 acu-py-2 acu-top-[32px] acu-z-10 acu-sticky acu-shadow-[0px_1px_2px_rgba(16,24,40,0.1)]"
	bind:this={element}>
	<div class="acu-flex acu-gap-4 acu-items-center">
		<h1 class="ac-header-bar__title acu-text-[22px] acu-font-medium">{title}</h1>
		<div class="acu-flex-grow">
		<slot></slot>
		</div>
	</div>

</div>