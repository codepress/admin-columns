<script lang="ts">
    export let label: string = '';
</script>

<div class="ac-listscreen-prefs-item md:acu-flex acu-pb-4 acu-mb-4 acu-border-0 acu-border-solid lg::acu-border-b acu-border-ui-border">
	<div class="acu-w-1/3 acu-font-bold acu-mb-2">{label}
		<slot name="label"></slot>
	</div>
	<div class="acu-flex-grow">
		<slot></slot>
	</div>
</div>