<script lang="ts">
	export let type: 'success' | 'warning' | 'error' | 'notify' | 'grey' | 'default' = 'default';
	export let size: 'default' | 'small' = 'default';
    export let classList: string[] = [];

	let classes = [ 'acui-badge', `-${type}`, ...(size === 'small' ? ['-small'] : []), ...classList ];
</script>
<span class={classes.join(' ')}>
	<slot></slot>
</span>