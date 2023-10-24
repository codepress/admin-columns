<script lang="ts">
    import ListScreenForm from "./ListScreenForm.svelte";
    import {onMount} from "svelte";
    import {getListScreenSettings, getListScreenSettingsByListKey} from "../ajax";
    import {currentListId, currentListKey} from "../store/current-list-screen";
    import {ListScreenData} from "../../types/requests";
    import {MappedListScreenData} from "../../types/admin-columns";
    import ListScreenSections from "../store/list-screen-sections";
    import MenuItems = AC.Vars.Admin.Columns.MenuItems;
    import {columnSettingsStore} from "../store/settings";

    export let menu: MenuItems;

    let listScreenData: MappedListScreenData | null = null;

    const mapListScreenData = (d: ListScreenData): MappedListScreenData => {
        return Object.assign(d, {
            columns: Object.values(d.columns)
        });
    }

    const handleMenuSelect = (key: string) => {
        listScreenData = null;
        getListScreenSettingsByListKey(key).then(response => {
            listScreenData = mapListScreenData(response.data.data.list_screen_data.list_screen);
            $currentListKey = key;
            columnSettingsStore.set(response.data.data.settings);
        });
    }

    const handleListIdChange = (listId: string) => {
        getListScreenSettings(listId).then(response => {
            listScreenData = mapListScreenData(response.data.data.list_screen_data.list_screen);
            columnSettingsStore.set(response.data.data.settings);
        })
    }

    onMount(() => {
        handleListIdChange($currentListId);
    });
</script>
<style>
	main {
		display: flex;
		gap: 25px;
	}

	main .left {
		width: 250px;
		border-right: 1px solid #000;
	}

	.right {
		flex-grow: 1;
	}

	li a {
		display: block;
		padding: 5px 10px;
		cursor: pointer;
		text-decoration: none;
	}

	li.active a {
		background: #E2E8F0;
		color: #FE3D6C;
	}
</style>
<main>
	<div class="left">
		{#each Object.values( menu ) as group}
			<strong>{group.title}</strong>
			<ul>
				{#each Object.entries( group.options ) as [ key, label ]}
					<li class:active={$currentListKey === key}>
						<a href={'#'}
								on:click|preventDefault={ () => handleMenuSelect( key ) }>{label}</a>
					</li>
				{/each}
			</ul>
		{/each}

	</div>
	<div class="right">
		{#each ListScreenSections.getSections( 'before_columns' ) as component}
			<svelte:component
					this={component}
					bind:data={listScreenData}
			></svelte:component>
		{/each}


		[ { $currentListId } - {$currentListKey} ]
		{#if listScreenData !== null}
			<ListScreenForm bind:data={listScreenData}></ListScreenForm>
		{:else}
			LOADING
		{/if}


	</div>
</main>