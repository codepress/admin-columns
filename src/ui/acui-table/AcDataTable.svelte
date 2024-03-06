<script lang="ts">

    import AcTableCell from "./AcTableCell.svelte";
    import AcTableHead from "./AcTableHead.svelte";
    import AcTableRow from "./AcTableRow.svelte";
    import AcTable from "./AcTable.svelte";
    import AcTableBody from "./AcTableBody.svelte";

    type columnDefinitionType = {
        field: string,
        label: string,
        width?: number,
        numeric?: boolean
    }

    export let striped: boolean = false;
    export let data: Array<any> = [];
    export let columns: Array<any> = []


</script>


<AcTable>
	<AcTableHead>
		<AcTableRow>
			{#each columns as column}
				<AcTableCell>{column.label}</AcTableCell>
			{/each}
		</AcTableRow>
	</AcTableHead>
	<AcTableBody>
		{#each data as itemRow}
			<AcTableRow>
				{#each columns as column}
					{#if column.type === 'custom' && $$slots.column }
						<slot name="column" item={itemRow} {column}></slot>
					{:else}
						<AcTableCell>{itemRow[ column.field ]}</AcTableCell>
					{/if}
				{/each}
			</AcTableRow>
		{/each}
	</AcTableBody>
</AcTable>
