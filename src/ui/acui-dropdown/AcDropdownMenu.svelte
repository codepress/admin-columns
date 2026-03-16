<script lang="ts">
    import {fade} from 'svelte/transition';
    import {onDestroy, onMount} from "svelte";
    import {AcDropdownMenuPosition} from "./index";

    export let menuClass: string = ''
    export let trigger: HTMLElement;
    export let appendToBody: boolean = false;
    export let maxHeight: string | null = null;
    export let position: AcDropdownMenuPosition | null = 'bottom-right';
    export let zIndex: number | null = null;
    export let style: string | undefined = '';

    let rootElement: HTMLElement;
    let menuElement: HTMLElement;
    let pos: any = {};

    const positionBodyElement = () => {
        rootElement.append(menuElement);
        document.body.append(rootElement);
        let triggerBox = trigger.getBoundingClientRect();

        pos = {
            position: 'absolute',
        }

        if (position === 'bottom-right') {
            pos.top = (Math.round(triggerBox.top + window.scrollY + triggerBox.height)).toString() + 'px';
            pos.left = Math.round(triggerBox.left) + 'px'
        }

        if (position === 'top-right') {
            pos.top = (Math.round(triggerBox.top + window.scrollY)).toString() + 'px';
            pos.left = Math.round(triggerBox.left) + 'px'
        }

        if (position === 'bottom-left') {
            pos.top = (Math.round(triggerBox.top + window.scrollY + triggerBox.height)).toString() + 'px';
            pos.left = Math.round((triggerBox.left - menuElement.getBoundingClientRect().width + triggerBox.width)) +
                'px';
        }
    }

    const handleResize = () => {
        positionBodyElement();
    };

    const handleScroll = () => {
        positionBodyElement();
    };

    onMount(() => {
        if (appendToBody && rootElement) {
            positionBodyElement();

            window.addEventListener('resize', handleResize);
            window.addEventListener('scroll', handleScroll);
        }
    });

    onDestroy(() => {
        window.removeEventListener('resize', handleResize);
        window.removeEventListener('scroll', handleScroll);
    })

    $:rootElementStyle = Object.entries(pos)
        .map(([key, value]) => `${key}:${value}`)
        .join(';') + style;

</script>

{#if appendToBody}
	<div style="position: absolute; left:0; top:0;" style:max-height={maxHeight} bind:this={rootElement}>

	</div>
{/if}

<div class={menuClass || 'acui-dropdown-menu' }
	class:-append-to-body={appendToBody}
	class:-bottom-left={!appendToBody && position === 'bottom-left'}
	style={rootElementStyle}
	style:max-height={maxHeight}
	style:z-index={zIndex}
	data-position={position}
	data-dropdown-menu="true"
	in:fade={{ duration: 100}}
	out:fade={{ duration: 100}}
	bind:this={menuElement}
>
	<div class="acui-dropdown-content" role="list">
		<slot/>
	</div>
</div>



