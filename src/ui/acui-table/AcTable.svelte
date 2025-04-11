<script lang="ts">
	export let fixed: boolean = true;
    export let striped: boolean = false;
    export let classList: string[] = [];

    let classes = [
        'acui-table',
        ...classList
    ]

	if( fixed){
        classes.push('acu-table-fixed');
	}
</script>

<table class={classes.join(' ')} class:ac-is-striped={striped}>
	<slot></slot>
</table>