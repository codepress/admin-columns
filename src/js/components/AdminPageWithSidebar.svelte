<script lang="ts">
    export let contentClass = "";
</script>

<div class="ac-admin-page acu-flex acu-flex-col acu-min-h-[calc(100vh_-_70px)] acu-w-full acu-transform 2xl:acu-flex-row">
	<aside class="ac-admin-page-menu acu-relative acu-shrink-0 acu-pl-4 acu-pr-[30px] acu-py-8 2xl:acu-w-[250px] 2xl:acu-pt-[30px] 2xl:acu-bg-[var(--ac-admin-menu-bg)]">
		<slot name="sidebar"/>
	</aside>
	<div class="acu-flex acu-flex-col acu-flex-grow {contentClass}">
		<slot/>
	</div>
</div>
