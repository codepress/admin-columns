<script lang="ts">
    export let border: boolean = false;

    let classes = ['acu-px-[20px] acu-pt-[20px] acu-pb-[20px] '];

    if( border ){
        classes.push('acu-border-0 acu-border-t acu-border-solid acu-border-ui-border')
    }

</script>

<div class={classes.join(' ')}>
	<slot></slot>
</div>