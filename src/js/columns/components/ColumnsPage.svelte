<svelte:options accessors={true}/>

<script lang="ts">
    import ListScreenForm from "./ListScreenForm.svelte";
    import {onMount} from "svelte";
    import {getListScreenSettings, getListScreenSettingsByListKey} from "../ajax/ajax";
    import {currentListId, currentListKey} from "../store/current-list-screen";
    import {ListScreenData} from "../../types/requests";
    import {MappedListScreenData} from "../../types/admin-columns";
    import ListScreenSections from "../store/list-screen-sections";
    import {columnSettingsStore} from "../store/settings";
    import HtmlSection from "./HtmlSection.svelte";
    import ListScreenMenu from "./ListScreenMenu.svelte";

    export let menu: AC.Vars.Admin.Columns.MenuItems;

    let listScreenData: MappedListScreenData | null = null;

    const mapListScreenData = (d: ListScreenData): MappedListScreenData => {
        return Object.assign(d, {
            columns: Object.values(d.columns)
        });
    }

    const handleMenuSelect = (e) => {
        listScreenData = null;
        getListScreenSettingsByListKey(e.detail).then(response => {
            listScreenData = mapListScreenData(response.data.data.list_screen_data.list_screen);
            $currentListKey = e.detail;
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

		{#if listScreenData !== null}
			<ListScreenForm bind:data={listScreenData}></ListScreenForm>
		{:else}
			LOADING
		{/if}


	</div>
</main>