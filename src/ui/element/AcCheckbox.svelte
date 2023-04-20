<script type="ts">
    import {createEventDispatcher, onMount} from "svelte";

    export let value: boolean | Array<string>;
    export let trueValue: string = '';
    export let falseValue: string = '';
    export let nativeValue: null | string = null;
    export let indeterminate: boolean = false;
    export let disabled: boolean = false;

    let input: HTMLInputElement;
    let checked = false;

    let dispatch = createEventDispatcher();

    let onClick = () => {
        indeterminate = false;
        if (input.checked) {
            setChecked();
        } else {
            setUnchecked();
        }


    }

    const setChecked = () => {
        if (nativeValue && Array.isArray(value)) {
            value.push(nativeValue);
            value = value;
        } else {
            value = true;
        }

        dispatch('input');
    }

    const setUnchecked = () => {
        if (nativeValue && Array.isArray(value)) {
            value = value.filter(v => v !== nativeValue);
            value = value;
        } else {
            value = false;
        }

        dispatch('input');
    }

    const updateCheckedOnValue = (new_value) => {
        if (Array.isArray(new_value)) {
            let found = new_value.find(v => v === nativeValue);
            checked = typeof found !== 'undefined';
        } else {
            checked = new_value;
        }
    }

    onMount(() => {
        updateCheckedOnValue(value);
    });

    $: {
        updateCheckedOnValue(value);
    }

    $:finalFalseLabel = falseValue ?? trueValue;
    $:checkedLabel = checked ? trueValue : finalFalseLabel;
</script>
<style>
	.acui-checkbox {
		display: flex;
	}

	.acui-checkbox input {
		position: absolute;
		left: 0;
		opacity: 0;
		outline: none;
		z-index: -1;
	}

	.acui-checkbox__check {
		position: relative;
		display: inline-block;
		align-items: center;
		justify-content: center;
		border-radius: 1px;
		width: 20px;
		height: 20px;
		background: #fff;
		border: 1px solid #8c8f94;
		transform: scale(80%) translateY(-2px);
		margin-right: 5px;
		text-align: center;
	}

	.acui-checkbox__check svg {
		position: absolute;
		left: -2px;
		top: -2px;
		fill: white;
		display: none;
	}

	.acui-checkbox input[type=checkbox]:checked + .acui-checkbox__check {
		border-color: var(--ac-primary-color);
		background: var(--ac-primary-color);
	}

	.acui-checkbox input[type=checkbox]:checked + .acui-checkbox__check svg {
		display: block;
	}

	.acui-checkbox.indeterminate input[type=checkbox] + .acui-checkbox__check {
		border-color: var(--ac-primary-color);
		background: var(--ac-primary-color) url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3Cpath fill='%23fff' d='M.15.4h.7v.2h-.7z'/%3E%3C/svg%3E") no-repeat 50%;
	}

	.acui-checkbox input[type=checkbox]:focus + .acui-checkbox__check {
		box-shadow: 0 0 0 2px #fff,0 0 0 4px var(--ac-primary-color);

	}

	.acui-checkbox input[disabled] + .acui-checkbox__check {
		cursor: not-allowed;
		filter: grayscale(100%);
		opacity: .3;
	}

</style>

<label class="acui-checkbox" class:indeterminate={indeterminate} class:disabled={disabled}>
	<input type="checkbox" bind:checked={checked} value={nativeValue} on:input={onClick} bind:this={input} {disabled}>
	<span class="acui-checkbox__check">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" role="presentation" class="components-checkbox-control__checked" aria-hidden="true" focusable="false"><path d="M16.7 7.1l-6.3 8.5-3.3-2.5-.9 1.2 4.5 3.4L17.9 8z"></path></svg>
	</span>
	<span class="acui-checkbox__label">
		{#if checkedLabel}
			{checkedLabel}
		{:else }
			<slot></slot>
		{/if}
	</span>
</label>


