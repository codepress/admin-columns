<script lang="ts">
    export let classNames: string[] = [];


    let classes = ['acu-p-4', ...classNames];
</script>
<div class={classes.join(' ')}>
    <slot></slot>
</div>