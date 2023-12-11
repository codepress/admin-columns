<script lang="ts">
    import ListScreenForm from "./ListScreenForm.svelte";
    import {onMount} from "svelte";
    import {getListScreenSettings, getListScreenSettingsByListKey} from "../ajax/ajax";
    import {currentListId, currentListKey} from "../store/current-list-screen";
    import ListScreenSections from "../store/list-screen-sections";
    import HtmlSection from "./HtmlSection.svelte";
    import ListScreenMenu from "./ListScreenMenu.svelte";
    import {listScreenDataStore} from "../store/list-screen-data";
    import ColumnSetting = AC.Vars.Settings.ColumnSetting;

    export let menu: AC.Vars.Admin.Columns.MenuItems;

    let config: {[key:string] : ColumnSetting[]};
    let tableUrl: string;

    const handleMenuSelect = (e: CustomEvent<string>) => {
		if( $currentListKey === e.detail ){
            return;
		}

        getListScreenSettingsByListKey(e.detail).then(response => {
            config = response.data.data.settings
            tableUrl = response.data.data.table_url;
            $currentListKey = e.detail;
            $currentListId = response.data.data.list_screen_data.list_screen.id;

            listScreenDataStore.update(d => {
                return response.data.data.list_screen_data.list_screen;
            })
        });
    }

    const handleListIdChange = (listId: string) => {
        getListScreenSettings(listId).then(response => {
            config = response.data.data.settings;
            tableUrl = response.data.data.table_url;
            listScreenDataStore.update(d => {
                return response.data.data.list_screen_data.list_screen;
            })
        })
    }

    onMount(() => {
        currentListId.subscribe((d) => {
            handleListIdChange(d);
        });
    });
</script>
<style>
	main {
		display: flex;
		gap: 25px;
	}

	main .left {
		width: 250px;
		flex-shrink: 0;
	}

	.right {
		flex-grow: 1;
	}


</style>
<main>
	<div class="left">
		<ListScreenMenu menu={menu} on:itemSelect={handleMenuSelect}>

		</ListScreenMenu>
	</div>
	<div class="right">
		{#each ListScreenSections.getSections( 'before_columns' ) as component}
			<HtmlSection component={component}></HtmlSection>
		{/each}

		{#if $listScreenDataStore !== null}
			<ListScreenForm bind:config={config} bind:data={$listScreenDataStore} tableUrl={tableUrl}></ListScreenForm>
		{/if}
	</div>
</main>