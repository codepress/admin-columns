<script lang="ts">
	export let label: string;
</script>
<style>
	.setting {
		display: flex;
		align-items: center;
	}
	.label {
		width: 200px;
		font-weight: bold;
		padding: 10px 30px;
	}
	.value {
		padding: 10px 10px;
		flex-grow: 1;
		max-width: 500px;
	}
</style>
<div class="setting">
	<div class="label">{label}</div>
	<div class="value">
	<slot>

	</slot>
	</div>
</div>