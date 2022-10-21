<script>

	import {onMount} from "svelte";

	export let autostart = false;
	let seconds = 0;
	let interVal = null;
	let displayMinutes = '00';
	let displaySeconds = '00';

	export const start = () => {
		interVal = window.setInterval( () => {
			seconds++;
			updateTimer();
		}, 1000 );
	}

	export const reset = () => {
		seconds = 0;
		updateTimer();
	}

	export const stop = () => {
		clearInterval( interVal );
		updateTimer();
	}

	const updateTimer = () => {
		let tempMinutes = Math.floor( seconds / 60 );
		let tempSeconds = seconds % 60;

		displayMinutes = tempMinutes > 9 ? tempMinutes.toString() : `0${tempMinutes.toString()}`;
		displaySeconds = tempSeconds > 9 ? tempSeconds.toString() : `0${tempSeconds.toString()}`;
	}

	onMount( () => {
		if( autostart ){
			start();
		}
	})

</script>
<div class="acui-timer">{displayMinutes}:{displaySeconds}</div>