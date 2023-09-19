<script lang="ts">
    export let customStyles = '';
    export let value: any = '';
    export let loading: boolean = false;
    export let placeholder: string = '';
</script>

<style>
	.acui-select {
		display: inline-block;
		position: relative;
	}
	.acui-select select {
		margin: 0;
		background: #fff;
		outline: none;
		-webkit-appearance: none;
		-moz-appearance: none;
		border: 1px solid #bbb;
	}
	.acui-select select option[disabled] {
		opacity: .8;
	}
	.acui-select.-empty select {
		color: rgba(0,0,0,.5);
	}

	.acui-select:after {
		content: '';
		border-right: 2px solid #bbb;
		border-bottom: 2px solid #bbb;
		display: block;
		width: 5px;
		height: 5px;
		transform: rotate(45deg);
		position: absolute;
		right: 8px; top: 50%;
		margin-top: -5px;
	}

</style>

<div class="acui-select" class:-empty={value === ''} style={customStyles}>

	<select bind:value>
		{#if placeholder}
			<option hidden="hidden" disabled value="">{@html placeholder}</option>
		{/if}
		<slot></slot>
	</select>
</div>

