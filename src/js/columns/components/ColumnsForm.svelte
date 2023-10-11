<script>
	import ColumnItem from "./ColumnItem.svelte";

	let listScreen = {
		'title' : 'Super Duper Cool',
		'type' : 'post',
		'id' : '5e6e7c503c979',
		'columns' : [
			{
				type : 'title',
				label : 'Hoi',
				width : '403',
				width_unit : 'px',
				sort : 'on',
				edit : 'on',
				bulk_edit : 'on',
				search : 'on',
				name : 'title'
			},
			{
				type : 'column-actions',
				label : 'Actions',
				width : '200',
				width_unit : 'px',
				use_icons : '',
				name : '5e6ba4118c354'
			}
		]
	}

</script>

<div>
	{JSON.stringify(listScreen)}
</div>

<div class="ac-columns-form">
	<header>
		<strong>Products</strong><input bind:value={listScreen.title}/>
	</header>
	<div class="ac-columns">
		{#each listScreen.columns as column_data}
			<ColumnItem config={ {} } bind:data={ column_data }></ColumnItem>
		{/each}
	</div>
</div>


<style>
	.ac-columns-form {
		background: #fff;
		border-radius: 5px;
	}

	header {
		padding: 10px 20px;
		border-bottom: 1px solid #666;
	}
</style>