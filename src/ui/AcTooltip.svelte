<script lang="ts">
    import {onDestroy, tick} from "svelte";
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
    export let closeOnClick: boolean = false;

    const GAP = 4;

    let contentEl: HTMLElement;
    let triggerEl: HTMLElement;
    let timeoutIn: ReturnType<typeof setTimeout>;
    let timeoutOut: ReturnType<typeof setTimeout>;
    let listenersAttached = false;

    const toggleOn = async () => {
        active = true;
        await tick();

        if (appendToBody && contentEl) {
            document.body.append(contentEl);
            contentEl.style.position = 'fixed';
            contentEl.style.bottom = 'auto';
            contentEl.style.right = 'auto';
            // The .is-*/centering transforms are CSS-scoped under .acui-tooltip,
            // so they no longer apply after detaching. Re-apply inline.
            contentEl.style.transform = transformFor(position);
            updatePosition();
            attachViewportListeners();
        }
    }

    const transformFor = (pos: 'top' | 'bottom' | 'left' | 'right'): string => {
        switch (pos) {
            case 'top':
            case 'bottom':
                return 'translateX(-50%)';
            case 'left':
            case 'right':
                return 'translateY(-50%)';
        }
    }

    const toggleOff = async () => {
        active = false;
        detachViewportListeners();
    }

    const handleMouseEnter = async () => {
        clearTimeout(timeoutOut);
        timeoutIn = setTimeout(toggleOn, delay);
    }

    const handleMouseOut = () => {
        clearTimeout(timeoutIn);
        timeoutIn = setTimeout(toggleOff, closeDelay);
    }

    // Opt-in: close immediately on click. Useful for triggers that cause
    // layout changes (e.g. reparenting) where mouseleave may not fire.
    const handleClick = () => {
        if (!closeOnClick) return;
        clearTimeout(timeoutIn);
        toggleOff();
    }

    // Uses position: fixed + viewport coordinates so overflow:hidden / clipping
    // ancestors never clip the tooltip. The .is-* CSS classes still apply their
    // translateX/Y(-50%) transforms; we anchor to the center of the trigger on
    // the relevant axis so the transform-based centering lines up.
    const updatePosition = () => {
        if (!triggerEl || !contentEl) return;

        const rect = triggerEl.getBoundingClientRect();

        if (position === 'bottom') {
            contentEl.style.left = `${rect.left + rect.width / 2}px`;
            contentEl.style.top = `${rect.bottom + GAP}px`;
        }

        if (position === 'top') {
            contentEl.style.left = `${rect.left + rect.width / 2}px`;
            contentEl.style.top = `${rect.top - contentEl.offsetHeight - GAP}px`;
        }

        if (position === 'left') {
            contentEl.style.left = `${rect.left - contentEl.offsetWidth - GAP}px`;
            contentEl.style.top = `${rect.top + rect.height / 2}px`;
        }

        if (position === 'right') {
            contentEl.style.left = `${rect.right + GAP}px`;
            contentEl.style.top = `${rect.top + rect.height / 2}px`;
        }
    }

    const attachViewportListeners = () => {
        if (listenersAttached) return;
        // capture:true catches scroll events on nested scroll containers too
        // (scroll events don't bubble).
        window.addEventListener('scroll', updatePosition, {passive: true, capture: true});
        window.addEventListener('resize', updatePosition, {passive: true});
        listenersAttached = true;
    }

    const detachViewportListeners = () => {
        if (!listenersAttached) return;
        window.removeEventListener('scroll', updatePosition, {capture: true} as EventListenerOptions);
        window.removeEventListener('resize', updatePosition);
        listenersAttached = false;
    }

    onDestroy(() => {
        detachViewportListeners();
        if (appendToBody) {
            contentEl?.remove();
        }
    });
</script>
{#if label}
	<div class="acui-tooltip">
		<div class="acui-tooltip-trigger"
			class:has-border={border}
			on:mouseenter={handleMouseEnter}
			on:mouseleave={handleMouseOut}
			on:click={handleClick} bind:this={triggerEl} role="none">
			<slot></slot>
		</div>
		{#if active }
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
