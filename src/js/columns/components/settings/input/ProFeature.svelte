<script lang="ts">
import AcToggle from "ACUi/element/AcToggle.svelte";

const check = (event: Event) => {
    alert('Buy Pro!');
}
</script>

<div class="acu-pt-1 acu-flex acu-gap-2 acu-items-center acu-cursor-pointer" on:click={check}
	role="button"
	on:keydown
	tabindex="0">
	<AcToggle checked={false} on:click={check} disabled={true}></AcToggle>
	<span
		class="acu-bg-[#e9426e] acu-text-[#fff] acu-inline-block acu-px-2 acu-py-[2px] acu-rounded acu-font-bold">PRO
	</span>
</div>