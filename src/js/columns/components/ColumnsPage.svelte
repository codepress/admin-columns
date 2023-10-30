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

    export let menu: AC.Vars.Admin.Columns.MenuItems;

    let listScreenData: MappedListScreenData | null = null;
    let openedGroups: string[] = [];

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

    const toggleGroup = (group: string) => {
        openedGroups.includes(group)
            ? closeGroup(group)
            : showGroup(group);
    }

    const showGroup = (group: string) => {
        openedGroups.push(group);
        openedGroups = openedGroups;
    }

    const closeGroup = (group: string) => {
        openedGroups = openedGroups.filter(d => d !== group);
    }

    onMount(() => {
        currentListId.subscribe((d) => {
            handleListIdChange(d);
        });

        Object.keys(menu).forEach(g => showGroup(g));
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

	.ac-menu-group {
		margin-bottom: 30px;
	}

	ul {
		margin-left: 13px;
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
		{#each Object.entries( menu ) as [ key, group ]}
			<div class="ac-menu-group">
				<div on:click={() => toggleGroup( key )}>
					<strong>
						<span class="dashicons dashicons-flag" style="color: #999;"></span>
						{group.title}
					</strong>
				</div>
				{#if openedGroups.includes( key )}
					<ul>
						{#each Object.entries( group.options ) as [ key, label ]}
							<li class:active={$currentListKey === key}>
								<a href={'#'}
										on:click|preventDefault={ () => handleMenuSelect( key ) }>{label}</a>
							</li>
						{/each}
					</ul>
				{/if}
			</div>
		{/each}

	</div>
	<div class="right">
		{#each ListScreenSections.getSections( 'before_columns' ) as component}
			<!--<DynamicSection this={component}></DynamicSection>-->
			<div bind:this={component}></div>
			<HtmlSection component={component}></HtmlSection>


		{/each}

		{#if listScreenData !== null}
			<ListScreenForm bind:data={listScreenData}></ListScreenForm>
		{:else}
			LOADING
		{/if}


	</div>
</main>