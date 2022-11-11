<script type="ts">
    import {onMount} from "svelte";

    export let value: boolean | Array<string>;
    export let trueValue: string = '';
    export let falseValue: string = '';
    export let nativeValue: null | string = null;
    export let indeterminate: boolean = false;

    let input: HTMLInputElement;
    let checked = false;

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
    }

    const setUnchecked = () => {
        if (nativeValue && Array.isArray(value)) {
            value = value.filter(v => v !== nativeValue);
            value = value;
        } else {
            value = false;
        }
    }

    onMount(() => {
        if (Array.isArray(value)) {
            let found = value.find(v => v === nativeValue);
            checked = typeof found !== 'undefined';
        } else {
            checked = value;
        }
    });

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
		display: inline-block;
		border-radius: 4px;
		width: 1.25em;
		height: 1.25em;
		background: #fff;
		border: 2px solid #eee;
		transform: scale(80%);
		margin-right: 5px;
	}

	.acui-checkbox input[type=checkbox]:checked + .acui-checkbox__check {
		border-color: var(--ac-primary-color);
		background: var(--ac-primary-color) url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3Cpath d='M.04.627L.146.52.43.804.323.91zm.177.177L.854.167.96.273.323.91z' fill='%23fff'/%3E%3C/svg%3E") no-repeat 50%;
	}

	.acui-checkbox.indeterminate input[type=checkbox] + .acui-checkbox__check {
		border-color: var(--ac-primary-color);
		background: var(--ac-primary-color) url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3Cpath fill='%23fff' d='M.15.4h.7v.2h-.7z'/%3E%3C/svg%3E") no-repeat 50%;
	}

	.acui-checkbox input[type=checkbox]:focus + .acui-checkbox__check {
		box-shadow: 0 0 0.3em rgba(0, 0, 0, .8);
	}

</style>

<label class="acui-checkbox" class:indeterminate={indeterminate}>
	<input type="checkbox" bind:checked={checked} value={nativeValue} on:input={onClick} bind:this={input}>
	<span class="acui-checkbox__check"></span>
	<span class="acui-checkbox__label">
		{#if checkedLabel}
			{checkedLabel}
		{:else }
			<slot></slot>
		{/if}
	</span>
</label>


