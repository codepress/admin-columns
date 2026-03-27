<script lang="ts">

	import {onMount} from "svelte";

	export let autostart: boolean = false;
	let seconds: number = 0;
	let interVal: ReturnType<typeof setInterval> | null = null;
	let displayMinutes: string = '00';
	let displaySeconds: string = '00';

	export const start = () => {
		interVal = setInterval( () => {
			seconds++;
			updateTimer();
		}, 1000 );
	}

	export const reset = () => {
		seconds = 0;
		updateTimer();
	}

	export const stop = () => {
		clearInterval( interVal ?? undefined );
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
	});

</script>
<div class="acui-timer">{displayMinutes}:{displaySeconds}</div>