<script lang="ts">
    import {createEventDispatcher, onMount} from "svelte";

    export let value: string;
    export let group: string | number = '';
    export let disabled: boolean = false;

    let dispatch = createEventDispatcher();


    onMount(() => {

    });
</script>
<style>
	.acui-radio {
		display: flex;
	}

	.acui-radio input {
		position: absolute;
		left: 0;
		opacity: 0;
		outline: none;
		z-index: -1;
	}

	.acui-radio__check {
		position: relative;
		display: inline-block;
		align-items: center;
		justify-content: center;
		border-radius: 50%;
		width: 20px;
		height: 20px;
		background: #fff;
		border: 1px solid #8c8f94;
		transform: scale(80%) translateY(-2px);
		margin-right: 5px;
		text-align: center;
	}

	.acui-radio__check svg {
		position: absolute;
		left: -2px;
		top: -2px;
		fill: white;
		display: none;
	}

	.acui-radio input[type=radio]:checked + .acui-radio__check {
		border-color: var(--ac-primary-color);
		background: var(--ac-primary-color);
	}

	.acui-radio input[type=radio]:checked + .acui-radio__check {
		display: flex;
		align-items: center;
		justify-content: center;

		&:before {
			content: '';
			display: block;
			width: 8px;
			height: 8px;
			border-radius: 50%;
			background: #fff;
		}
	}

	.acui-radio input[type=radio]:focus + .acui-radio__check {
		box-shadow: 0 0 0 2px #fff, 0 0 0 4px var(--ac-primary-color);

	}

	.acui-radio input[disabled] + .acui-radio__check {
		cursor: not-allowed;
		filter: grayscale(100%);
		opacity: .3;
	}

</style>

<label class="acui-radio" class:disabled={disabled}>
	<input type="radio" bind:group={group} {value} {disabled}>
	<span class="acui-radio__check">

	</span>
	<span class="acui-radio__label">
			<slot></slot>
	</span>
</label>


