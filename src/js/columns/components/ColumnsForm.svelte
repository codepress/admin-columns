<script>
	import ColumnItem from "./ColumnItem.svelte";
	import {columnSettingsStore} from "../store/settings";
	import {getColumnSettings} from "../ajax";
	import {openedColumnsStore} from "../store/opened-columns";
	import ColumnUtils from "../utils/column";

	export let listScreenData = null;

	const clearColumns = () => {
		listScreenData[ 'columns' ] = [];
	}

	const addColumn = () => {
		const name = ColumnUtils.generateId();
		const column_type = 'column-meta';

		getColumnSettings( 'post', column_type ).then( d => {
			columnSettingsStore.changeSettings( name, d.data.data.columns.settings );
			listScreenData[ 'columns' ].push( {
				name : name,
				type : column_type
			} );
			openedColumnsStore.open( name );
		} );
	}

	const saveSettings = () => {
		console.log( listScreenData );

	}

</script>


{#if listScreenData }
	<div class="ac-columns-form">
		<header>
			<div>
				<h1>{listScreenData.type}</h1>
			</div>
			<input bind:value={listScreenData.title}/>
		</header>
		<div class="ac-columns">
			{#each listScreenData.columns as column_data}
				<ColumnItem
						bind:config={ $columnSettingsStore[column_data.name] }
						bind:data={ column_data }>

				</ColumnItem>
			{/each}
		</div>
		<footer>
			<div>
				<button on:click={clearColumns}>Clear Columns</button>
				<button on:click={addColumn}>+ Add Column</button>
			</div>
		</footer>
	</div>
	<br><br><br>
	<div>
		<button class="save" on:click={saveSettings}>Save</button>
	</div>
{/if}

<style>
	.ac-columns-form {
		background: #fff;
		border: 1px solid #CBD5E1;
		max-width: 1200px;
		border-radius: 10px;
	}

	header {
		display: flex;
		align-items: center;
		padding: 20px 30px;
		border-bottom: 1px solid #CBD5E1;
	}

	header h1 {
		margin: 0;
		padding: 0;
		margin-right: 20px;
		align-items: center;
	}

	header input {
		height: 40px;
		border-radius: 5px;
		border: 1px solid #bbb;
		padding: 5px 10px;
	}

	footer {
		display: flex;
		justify-content: right;
		align-items: center;
		padding: 20px 30px;
	}

</style>