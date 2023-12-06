<script lang="ts">

    import {createEventDispatcher, onMount} from "svelte";
    import {crossfade} from 'svelte/transition';
    import {flip} from "svelte/animate";
    import AcDateMonths from "./AcDateMonths.svelte";

    export let value: string = null;

    const dispatch = createEventDispatcher();

    let displayYear: number = new Date().getFullYear();
    let valueYear: number = new Date().getFullYear();
    let valueMonth: number = new Date().getMonth();

    const shortMonth = (month: number): string => {
        let padded = month.toString().length === 1 ? '0' + month.toString() : month.toString();

        return new Date('2022-' + padded).toLocaleString('default', {month: 'short'})
    }

    const padMonth = (month: number): string => {
        return month.toString().length === 1 ? '0' + month.toString() : month.toString();
    }

    const handleNextYear = () => {
        displayYear++;
    }

    const handlePreviousYear = () => {
        displayYear--;
    }

    const handleSetMonth = (e) => {
        let value: { year: number, month: number } = e.detail;
        valueMonth = value.month;
        valueYear = value.year;
        updateValue();
    }

    const updateValue = () => {
        let month = valueMonth.toString().length === 1 ? '0' + valueMonth.toString() : valueMonth.toString()
        value = valueYear.toString() + '-' + month;
        dispatch('change', value);
    }

    const isActive = (month: number) => {
        return month === valueMonth;
    }

    onMount(() => {
        if (value === null) {
            value = '';
            //let newDate = new Date();
            //value = newDate.getFullYear() + '-' + padMonth(newDate.getMonth() + 1);
        }
        if (value.length === 7) {
            let date = new Date(value);

            valueYear = date.getFullYear();
            valueMonth = date.getMonth() + 1;
            displayYear = valueYear;
        }
    })

    const [send, receive] = crossfade({
        duration: d => Math.sqrt(d * 400)
    });

    $: prevYear = displayYear - 1;
    $: nextYear = displayYear + 1;
    $: readyYears = [prevYear, displayYear, nextYear];
    $: modelValue = {year: valueYear, month: valueMonth}

</script>

<div class="acui-datepicker">
	<div class="acui-datepicker-header">
		<button on:click|preventDefault={handlePreviousYear} class="acui-datepicker-button -prev">
			<span class="dashicons dashicons-arrow-left-alt2"></span>
		</button>
		<div class="acui-datepicker-header__controls">
			<select bind:value={displayYear} on:change|stopPropagation={()=>{}}>
				{#each Array( 100 ) as _, index (index)}
					<option value={1970+ index}>{1970 + index}</option>
				{/each}
			</select>
		</div>
		<button on:click|preventDefault={handleNextYear} class="acui-datepicker-button -next">
			<span class="dashicons dashicons-arrow-right-alt2"></span>
		</button>
	</div>
	<div class="acui-datepicker-body" style="position: relative;">
		<div class="acui-datepicker-positioning">

			{#each readyYears.filter( y => y === displayYear - 1 ) as years (years)}
				<div class="acui-datepicker-month-container -prev" in:receive="{{key: years}}" out:send="{{key: years}}" animate:flip>
					<AcDateMonths year={years} currentValue={modelValue}/>
				</div>
			{/each}

			{#each readyYears.filter( y => y === displayYear ) as years (years)}
				<div class="acui-datepicker-month-container" in:receive="{{key: years}}" out:send="{{key: years}}" animate:flip>
					<AcDateMonths year={years} currentValue={ modelValue } on:setMonth={handleSetMonth}/>
				</div>
			{/each}

			{#each readyYears.filter( y => y === displayYear + 1 ) as years (years)}
				<div class="acui-datepicker-month-container -next" in:receive="{{key: years}}" out:send="{{key: years}}" animate:flip>
					<AcDateMonths year={years} currentValue={ modelValue }/>
				</div>
			{/each}

		</div>
	</div>
</div>
