<script type="ts">

    export let percentage = 0;
    export let customStyles = '';
	export let finished = false;
    export let warning = false;
    export let error = false;
</script>
<style>

</style>
<div class="acui-progress" style={customStyles}>
	<div class="acui-progress__bar">
		<div class="acui-progress__progress " class:-finished={finished} class:-warning={warning} class:-error={error} style="width: {percentage}%;"></div>
	</div>
</div>