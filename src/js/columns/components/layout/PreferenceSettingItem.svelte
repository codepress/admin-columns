<script lang="ts">
    import {AcIcon, AcReferencedTooltip} from "ACUi/index";

    export let label: string = '';
    export let helpRef: string = '';
</script>

<div class="ac-listscreen-prefs-item @[500px]/setting:acu-flex acu-pb-4 acu-mb-4 acu-border-0 acu-border-solid lg::acu-border-b acu-border-ui-border">
	<div class="@[500px]/setting:acu-w-1/3 acu-font-bold acu-mb-2 acu-flex">{label}
		<span class="acu-flex-grow"><slot name="label"></slot></span>
		{#if helpRef }
		<span class="acu-pr-2">
			<AcReferencedTooltip reference={helpRef} position="right" closeDelay={300}>
				<span class="acu-cursor-pointer"><AcIcon icon="question" size="sm"/></span>
			</AcReferencedTooltip>
		</span>
		{/if}
	</div>
	<div class="acu-flex-grow">
		<slot></slot>
	</div>
</div>