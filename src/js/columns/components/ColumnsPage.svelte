<svelte:options accessors={true}/>

<script lang="ts">
    import ListScreenForm from "./ListScreenForm.svelte";
    import {onMount} from "svelte";
    import {getListScreenSettings, getListScreenSettingsByListKey} from "../ajax/ajax";
    import {currentListId, currentListKey} from "../store/current-list-screen";
    import ListScreenSections from "../store/list-screen-sections";
    import HtmlSection from "./HtmlSection.svelte";
    import ListScreenMenu from "./ListScreenMenu.svelte";
    import {listScreenDataStore} from "../store/list-screen-data";
    import AcSkeleton from "ACUi/element/AcSkeleton.svelte";

    export let menu: AC.Vars.Admin.Columns.MenuItems;

    let config;

    const handleMenuSelect = (e) => {
        getListScreenSettingsByListKey(e.detail).then(response => {
            config = response.data.data.settings
            $currentListKey = e.detail;
            listScreenDataStore.update(d => {
                return response.data.data.list_screen_data.list_screen;
            })
        });
    }

    const handleListIdChange = (listId: string) => {
        getListScreenSettings(listId).then(response => {
            config = response.data.data.settings;
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

		<div class="ac-columns">
			<header class="ac-columns__header">

					<AcSkeleton count={3}></AcSkeleton>

			</header>
			<div class="ac-columns__body">
				<div class="ac-column">
					<header class="ac-column-header">
						<div class="ac-column-header__label"><strong role="none">Slug</strong></div>
						<div class="ac-column-header__actions">[ 360 px ]
							<button class="ac-header-toggle">
								<span class="dashicons dashicons-filter on" title="Enable Filtering"></span>
							</button>
							<button class="ac-header-toggle -active">
								<span class="dashicons dashicons-filter on" title="Enable Filtering"></span>
							</button>
						</div>
						<div class="ac-column-header__open-indicator">
							<button class="ac-open-indicator">
								<span class="dashicons dashicons-arrow-down-alt2"></span></button>
						</div>
					</header>
				</div>
			</div>
			<footer class="ac-columns__footer">
				<div>
					<button class="acui-button button-text"> Clear Columns</button>
					<div class="acui-dropdown">
						<div class="acui-dropdown-trigger" aria-haspopup="true" role="button" tabindex="-1">
							<button class="acui-button button-null"> + Add Column</button>
						</div>
					</div>
				</div>
			</footer>
		</div>

		{#if $listScreenDataStore !== null}
			<ListScreenForm bind:config={config}></ListScreenForm>
		{:else}

		{/if}


	</div>
</main>