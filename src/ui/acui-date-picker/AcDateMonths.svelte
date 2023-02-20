<script type="ts">

    import {createEventDispatcher} from "svelte";

    export let year: number;
    export let currentValue: { month: number, year: number };

    const dispatch = createEventDispatcher();

    const shortMonth = (month: number): string => {
        let padded = month.toString().length === 1 ? '0' + month.toString() : month.toString();

        return new Date('2022-' + padded).toLocaleString('default', {month: 'short'})
    }

    const setMonth = (month: number) => {
        dispatch('setMonth', {month: month, year: year});
    }
</script>
<style>

</style>
<div class="acui-datepicker-months">

	{#each Array( 12 ) as _, index (index)}
		<button
				class="acui-datepicker-month"
				class:-active={ currentValue.year === year && (index+1) === currentValue.month }
				on:click|preventDefault={() => setMonth(index+1)}
		>{shortMonth( index + 1 )}</button>
	{/each}

</div>
