<script lang="ts">
    export let classNames: string[] = [];


    let classes = ['acu-p-[20px]', ...classNames];
</script>
<div class={classes.join(' ')}>
	<slot></slot>
</div>