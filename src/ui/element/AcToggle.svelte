<script type="ts">
    import {generateGuid} from "../helpers/input";
    import {createEventDispatcher} from "svelte";

    export let customStyles = '';
    export let checked: boolean;
    export let trueValue: string = '';
    export let falseValue: string = '';

    let name: string = generateGuid();
    let dispatch = createEventDispatcher();

    const dispatchInput = () => {
		setTimeout( () => dispatch('input') );
	}

    $:finalFalseLabel = falseValue ?? trueValue;
    $:checkedLabel = checked ? trueValue : finalFalseLabel;
</script>

<style>
	.ac-toggle-v2__toggle__track {
		background: var(--baseBackground, #d8d8d8);
	}

	.ac-toggle-v2__toggle input[type=checkbox]:checked ~ .ac-toggle-v2__toggle__track {
		background: var(--activeBackground, var(--ac-primary-color));
	}

	.ac-toggle-v2 input[type=checkbox]:focus + .ac-toggle-v2__toggle__track {
		box-shadow: 0 0 0.3em rgba(0, 0, 0, .5);
	}
</style>


<div class="ac-toggle-v2" style={customStyles}>
	<span class="ac-toggle-v2__toggle">
		<input class="ac-toggle-v2__toggle__input" type="checkbox" value="off" bind:checked id={name} on:input={()=> dispatchInput() }>
		<span class="ac-toggle-v2__toggle__track"></span>
		<span class="ac-toggle-v2__toggle__thumb"></span>
	</span>

	<label class="ac-toggle-v2__label" for={name}>
		{#if checkedLabel}
			{checkedLabel}
		{:else }
			<slot></slot>
		{/if}
	</label>
</div>


