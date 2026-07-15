<script lang="ts">
    import {createEventDispatcher} from "svelte";

    export let contentClass = "";
    export let collapsible = false;
    export let collapsed = false;

    let peeking = false;
    let suppressPeek = false;

    const dispatch = createEventDispatcher();

    const toggleCollapsed = () => {
        collapsed = !collapsed;
        peeking = false;
        // collapsed while the cursor is still over the menu: keep it closed
        // until the cursor actually leaves and returns
        suppressPeek = collapsed;
        dispatch('toggleCollapse', collapsed);
    };

    const handleEnter = () => {
        if (collapsible && collapsed && !suppressPeek) {
            peeking = true;
        }
    };

    const handleLeave = () => {
        peeking = false;
        suppressPeek = false;
    };
</script>

<div class="ac-admin-page acu-flex acu-flex-col acu-min-h-[calc(100vh_-_70px)] acu-w-full acu-transform 2xl:acu-flex-row">
	<aside class="ac-admin-page-menu acu-relative acu-shrink-0 acu-pl-4 acu-pr-[30px] acu-py-8 2xl:acu-w-[250px] 2xl:acu-pt-[30px] 2xl:acu-bg-[var(--ac-admin-menu-bg)]"
		   class:is-collapsed={collapsible && collapsed}
		   class:is-peeking={collapsible && collapsed && peeking}
		   on:mouseenter={handleEnter}
		   on:mouseleave={handleLeave}>
		<slot name="sidebar"/>
		{#if collapsible}
			<button
				type="button"
				class="ac-admin-page-menu__toggle"
				on:click={toggleCollapsed}
				aria-label="Toggle menu"
			>
				<span class="dashicons dashicons-arrow-left-alt2"></span>
			</button>
		{/if}
	</aside>
	<div class="acu-flex acu-flex-col acu-flex-grow 2xl:acu-pt-8 {contentClass}">
		<slot/>
	</div>
</div>
