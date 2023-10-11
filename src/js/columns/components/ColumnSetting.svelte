<script lang="ts">
	export let label: string;
</script>
<style>
	.setting {
		border-bottom: 1px solid #000;
		display: flex;
	}
	.label {
		width: 200px;
		font-weight: bold;
		background: #eee;
	}
	.value {
		padding: 5px 10px;
	}
</style>
<div class="setting">
	<div class="label">{label}</div>
	<div class="value">
	<slot>

	</slot>
	</div>
</div>