<script lang="ts">
    import {tick} from "svelte";
    import {fade} from 'svelte/transition';

    export let label: string;
    export let active: boolean = false;
    export let appendToBody: boolean = false;
    export let border: boolean = false;
    export let delay: number = 0;
    export let closeDelay: number = 0;
    export let position: 'bottom' | 'top' | 'left' | 'right' = 'bottom';
    export let multiline: boolean = false;
    export let size: 'small' | 'medium' | 'large' = 'medium';
    export let maxWidth: string | null = '250px';

    let contentEl: HTMLElement;
    let triggerEl: HTMLElement;
    let timeoutIn: ReturnType<typeof setTimeout>;
    let timeoutOut: ReturnType<typeof setTimeout>;

    const toggleOn = async () => {
        active = true;
        await tick();

        if (appendToBody && contentEl) {
            document.body.append(contentEl);
            attachBodyPosition();
        }
    }

    const toggleOff = async () => {
        active = false;
    }

    const handleMouseEnter = async () => {
        clearTimeout(timeoutOut);
        timeoutIn = setTimeout(toggleOn, delay);
    }

    const handleMouseOut = () => {
        clearTimeout(timeoutIn);
        timeoutIn = setTimeout(toggleOff, closeDelay);
    }

    const attachBodyPosition = () => {
        const bodyOffset = document.body.getBoundingClientRect();
        const viewportOffset = triggerEl.getBoundingClientRect();

        if (position === 'bottom') {
            contentEl.style.left = ((viewportOffset.left - bodyOffset.left) + triggerEl.offsetWidth / 2) + 'px';
            contentEl.style.top = ((viewportOffset.top) + triggerEl.offsetHeight) + 'px';
        }

        if (position === 'top') {
            contentEl.style.left = ((viewportOffset.left - bodyOffset.left) + triggerEl.offsetWidth / 2) + 'px';
            contentEl.style.top = ((viewportOffset.top) - contentEl.offsetHeight) + 'px';
        }

        if (position === 'left') {
            contentEl.style.left = (viewportOffset.left - viewportOffset.width) + 'px';
            contentEl.style.top = ((viewportOffset.top) + (triggerEl.offsetHeight / 2)) + 'px';
        }

        if (position === 'right') {
            contentEl.style.left = (viewportOffset.left + triggerEl.offsetWidth) + 'px';
            contentEl.style.top = ((viewportOffset.top) + (triggerEl.offsetHeight / 2)) + 'px';
        }
    }


</script>
{#if label}
	<div class="acui-tooltip">
		<div class="acui-tooltip-trigger"
			class:has-border={border}
			on:mouseenter={handleMouseEnter}
			on:mouseleave={handleMouseOut} bind:this={triggerEl} role="none">
			<slot></slot>
		</div>
		{#if active || 1 === 1 }
			<div
				class="acui-tooltip-content"
				class:is-multiline={multiline}
				class:is-top={ position === 'top'}
				class:is-bottom={ position === 'bottom'}
				class:is-right={ position === 'right'}
				class:is-left={ position === 'left'}
				class:is-small={ size === 'small'}
				class:is-medium={ size === 'medium'}
				class:is-large={ size === 'large'}
				style:display={ active === true ? 'block' : 'none' }
				style:max-width={maxWidth}
				bind:this={contentEl}
				in:fade={{duration:200}}
				out:fade={{duration:200}}>

				{@html label}


			</div>
		{/if}
	</div>
{/if}