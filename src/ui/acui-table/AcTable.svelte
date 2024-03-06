<script lang="ts">

    export let striped: boolean = false;

</script>

<table class="acui-table acu-table-fixed" class:ac-is-striped={striped}>
	<slot></slot>
</table>