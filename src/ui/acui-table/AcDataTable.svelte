<script lang="ts">

    import AcTableCell from "./AcTableCell.svelte";
    import AcTableHead from "./AcTableHead.svelte";
    import AcTableRow from "./AcTableRow.svelte";
    import AcTable from "./AcTable.svelte";
    import AcTableBody from "./AcTableBody.svelte";
    import {DataTableActionsDefinitionType, DataTableFieldDefinitionType} from "ACUi/acui-table/types";
    import AcTableHeaderCell from "ACUi/acui-table/AcTableHeaderCell.svelte";
    import AcButton from "ACUi/element/AcButton.svelte";


    export let fields: DataTableFieldDefinitionType[] = [];
    export let data: Array<any> = [];
    export let loading: boolean = false;
    export let actions: Array<any> = [];

    const getButtonType = ( action: DataTableActionsDefinitionType ) => {
        return action.primary ? 'primary' : 'default';
	}

</script>


<AcTable>
	<AcTableHead>
		<AcTableRow>
			{#each fields as field}
				<AcTableHeaderCell>{field.label}</AcTableHeaderCell>
			{/each}
			{#if actions.length > 0}
				<AcTableHeaderCell></AcTableHeaderCell>
			{/if}
		</AcTableRow>
	</AcTableHead>
	<AcTableBody>
		{#if loading}
			<AcTableRow>
				<AcTableCell colspan={fields.length}>Loading...</AcTableCell>
			</AcTableRow>
		{:else}
			{#each data as itemRow}
				<AcTableRow>
					{#each fields as field}
						<AcTableCell>
							{#if field.getValue }
								{@html field.getValue( itemRow ) }
							{:else if field.render }
								<svelte:component this={field.render} item={itemRow}/>
							{:else}
								{itemRow[ field.id ]}
							{/if}
						</AcTableCell>
					{/each}

					{#if actions.length > 0}
						<AcTableCell density="compact" right>
							<div class="acu-flex acu-gap-1 acu-justify-end">
							{#each actions as action}
								<AcButton
									size="small"
									on:click={action.callback(itemRow)}
									type={getButtonType(action)}>{action.label}</AcButton>
							{/each}
							</div>
						</AcTableCell>
					{/if}
				</AcTableRow>
			{/each}
		{/if}
	</AcTableBody>
</AcTable>
