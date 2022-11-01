<script type="ts">

    export let percentage = 0;
    export let customStyles = '';
	export let finished = false;
    export let error = false;
</script>
<style>
	.acui-progress {
		--containerWidth: 100%;
		--baseBackground: #ddd;
		--defaultBarBackground: #0073aa;
		--errorBarBackground: #FFB900;
		--finishedBarBackground: #7ED321;

		display: block;
		width: var(--containerWidth);
	}

	.acui-progress__bar {
		background: var(--baseBackground);
		width: 100%;
		height: 10px;
		border-radius: 2px;
		position: relative;
		overflow: hidden;
		margin: 15px 0;
	}

	.acui-progress__progress {
		height: 100%;
		background: var(--defaultBarBackground);
	}

	.-finished {
		background-color: var(--finishedBarBackground, #7ED321);
	}

	.-error {
		background-color: var(--errorBarBackground, #FFB900);
	}

</style>
<div class="acui-progress" style={customStyles}>
	<div class="acui-progress__bar">
		<div class="acui-progress__progress " class:-finished={finished} class:-error={error} style="width: {percentage}%;"></div>
	</div>
</div>