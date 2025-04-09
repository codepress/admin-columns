<script lang="ts">
    export let classNames: string[] = [];


    let classes = ['acu-p-5', ...classNames];
</script>
<div class={classes.join(' ')}>
	<slot></slot>
</div>