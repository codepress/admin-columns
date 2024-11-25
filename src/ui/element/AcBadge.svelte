<script lang="ts">
	export let type: 'success' | 'warning' | 'error' | 'notify' | 'default' = 'default';
    export let classList: string[] = [];

	let classes = [ 'acui-badge', `-${type}`, ...classList ];
</script>
<span class={classes.join(' ')}>
	<slot></slot>
</span>