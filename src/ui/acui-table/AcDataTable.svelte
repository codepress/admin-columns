<script lang="ts">
    import {AcTableCell, AcTableHead, AcTableRow, AcTable, AcTableBody, AcTableHeaderCell } from "ACUi/acui-table";
    import {DataTableActionsDefinitionType, DataTableFieldDefinitionType} from "ACUi/acui-table/types";
    import AcButton from "ACUi/element/AcButton.svelte";


    export let fields: DataTableFieldDefinitionType[] = [];
    export let data: Array<any> = [];
    export let loading: boolean = false;
    export let actions: Array<any> = [];

    const getButtonType = (action: DataTableActionsDefinitionType) => {
        return action.primary ? 'primary' : 'default';
    }

    const getRowActions = (row) => actions.filter(action => !action.condition || action.condition(row));
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
								{@html itemRow[ field.id ]}
							{/if}
						</AcTableCell>
					{/each}

					{#if actions.length > 0}
						{#if getRowActions(itemRow).length > 0}
							<AcTableCell density="compact" right>
								<div class="acu-flex acu-gap-1 acu-justify-end">
									{#each getRowActions(itemRow) as action}
										{#if action.render }
											<svelte:component
												this={action.render}
												item={itemRow}
												on:callback={() => action.callback(itemRow)}
											/>
										{:else}
											<AcButton
												size="small"
												on:click={() => action.callback(itemRow)}
												type={getButtonType(action)}>
												{action.label}
											</AcButton>
										{/if}
									{/each}
								</div>
							</AcTableCell>
						{:else}
							<AcTableCell density="compact" right>&nbsp;</AcTableCell>
						{/if}

					{/if}
				</AcTableRow>
			{/each}
		{/if}
	</AcTableBody>
</AcTable>
