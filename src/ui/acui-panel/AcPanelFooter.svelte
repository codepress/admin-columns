<script lang="ts">
    export let border: boolean = false;
    export let classNames: string[] = [];

    let classes = ['acu-px-[20px] acu-pt-[20px] acu-pb-[20px] ', ...classNames];

    if( border ){
        classes.push('acu-border-0 acu-border-t acu-border-solid acu-border-ui-border')
    }

</script>

<div class={classes.join(' ')}>
	<slot></slot>
</div>