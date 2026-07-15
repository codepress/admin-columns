<script lang="ts">
    import {createEventDispatcher} from "svelte";

    export let contentClass = "";
    export let collapsible = false;
    export let collapsed = false;

    const dispatch = createEventDispatcher();

    const toggleCollapsed = () => {
        collapsed = !collapsed;
        dispatch('toggleCollapse', collapsed);
    };
</script>

<div class="ac-admin-page acu-flex acu-flex-col acu-min-h-[calc(100vh_-_70px)] acu-w-full acu-transform 2xl:acu-flex-row">
	<aside class="ac-admin-page-menu acu-relative acu-shrink-0 acu-pl-4 acu-pr-[30px] acu-py-8 2xl:acu-w-[250px] 2xl:acu-pt-[30px] 2xl:acu-bg-[var(--ac-admin-menu-bg)]"
		   class:is-collapsed={collapsible && collapsed}>
		<slot name="sidebar"/>
		{#if collapsible}
			<button
				type="button"
				class="ac-admin-page-menu__toggle"
				class:is-collapsed={collapsed}
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
