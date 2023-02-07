<script type="ts">

	import {onMount} from "svelte";

    export let value: string = null;
    let displayYear: number = new Date().getFullYear();
    let valueYear: number = new Date().getFullYear();
    let valueMonth: number = new Date().getMonth();
    const shortMonth = (month: number): string => {
        let padded = month.toString().length === 1 ? '0' + month.toString() : month.toString();

        return new Date('2022-' + padded).toLocaleString('default', {month: 'short'})
    }

    const handleNextYear = () => {
        displayYear++;
    }

    const handlePreviousYear = () => {
        displayYear--;
    }

    const setMonth = ( month: number, year: number  ) => {
		valueMonth = month;
        valueYear = year;
        updateValue();
	}

    const updateValue = () => {
        let month = valueMonth.toString().length === 1 ? '0' + valueMonth.toString() : valueMonth.toString()
        value = valueYear.toString() + '-' + month;
	}

    const isActive = ( month: number ) => {
        return month === valueMonth;
	}

    onMount( () => {
        if( value.length === 7){
            let date = new Date( value );

            valueYear = date.getFullYear();
            valueMonth = date.getMonth() + 1
            displayYear = valueYear;
		}
	})

</script>
<style>
	.acui-picker {
		background: #fff;
		width: 250px;
		position: relative;
		z-index: 10;
	}

	.acui-datepicker-header {
		display: flex;
		align-items: center;
	}

	.acui-datepicker-header__year {
		flex-grow: 1;
		text-align: center;
		font-weight: bold;
	}

	.acui-datepicker-table {
		padding: 10px;
	}

	.acui-datepicker-months {
		display: flex;
		flex-wrap: wrap;
		padding: 10px;
	}

	.acui-datepicker-months button {
		flex: 33%;
		background: none;
		border: 1px solid transparent;
		height: 40px;
		cursor: pointer;
	}

	.acui-datepicker-table table {
		width: 100%;
		text-transform: uppercase;
	}

	.acui-datepicker-table table td {
		text-align: center;
	}

	.acui-datepicker-month {
		display: flex;
		align-items: center;
		border-radius: 5px;
		padding: 5px 0;
		justify-content: center;
	}

	.acui-datepicker-month:hover {
		background: #eee;
	}
	.acui-datepicker-month.-active {
		border-color: var(--ac-primary-color);
		color: var(--ac-primary-color);
	}
</style>
<div class="acui-picker">
	<div>
		Value: {value}
	</div>
	<div class="acui-picker__header">

	</div>
	<div class="acui-picker__body">
		<div class="acui-datepicker-header">
			<button on:click|preventDefault={handlePreviousYear}>
				<span class="dashicons dashicons-arrow-left-alt2"></span></button>
			<div class="acui-datepicker-header__year">{displayYear}</div>
			<button on:click|preventDefault={handleNextYear}>
				<span class="dashicons dashicons-arrow-right-alt2"></span>
			</button>
		</div>
		<div class="acui-datepicker-months">

			{#each Array( 12 ) as _, index (index)}
				<button
						class="acui-datepicker-month"
						on:click|preventDefault={() => setMonth(index+1, displayYear)}
						class:-active={ (index+1) === valueMonth && displayYear === valueYear }

				>{shortMonth( index + 1 )}</button>
			{/each}

		</div>

	</div>
</div>