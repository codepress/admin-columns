<script lang="ts">
    import {currentListKey} from "../store/current-list-screen";
    import {createEventDispatcher, onMount} from "svelte";
    import GroupIcon from "./GroupIcon.svelte";
    import {SvelteSelectItem} from "../../types/select";
    import {favoriteListKeysStore} from "../store/favorite-listkeys";
    import ListScreenMenuItem from "./ListScreenMenuItem.svelte";
    import {getColumnSettingsTranslation} from "../utils/global";
    import {persistMenuStatus} from "../ajax/menu";

    export let menu: AC.Vars.Admin.Columns.MenuItems;
    export let openedGroups: string[] = [];

    const dispatch = createEventDispatcher();
    const i18n = getColumnSettingsTranslation();

    let options: SvelteSelectItem[];
    let favoriteItems: { [key: string]: string } = {}
    let groups: string[] = [];

    const handleMenuSelect = (key: string) => {
        dispatch('itemSelect', key)
    }

    const toggleGroup = (group: string) => {
        openedGroups.includes(group)
            ? closeGroup(group)
            : showGroup(group);
    }

    const showGroup = (group: string) => {
        if (!openedGroups.includes(group)) {
            openedGroups.push(group);
            openedGroups = openedGroups;

            persistMenuStatus(group, true);
        }
    }

    const closeGroup = (group: string) => {
        if (openedGroups.includes(group)) {
            openedGroups = openedGroups.filter(d => d !== group);

            persistMenuStatus(group, false);
        }
    }

    const mapMenuToSelect = (menu: AC.Vars.Admin.Columns.MenuItems): SvelteSelectItem[] => {
        let result: SvelteSelectItem[] = [];

        Object.values(menu).forEach(group => {
            for (const [value, label] of Object.entries(group.options)) {
                result.push({
                    value,
                    label,
                    group: group.title
                });
            }
        });

        return result;
    }

    const refreshFavoriteItems = () => {
        let _favoriteItems: { [key: string]: string } = {};

        Object.values(menu).forEach(group => {
            for (const [value, label] of Object.entries(group.options)) {
                if ($favoriteListKeysStore.includes(value)) {
                    _favoriteItems[value] = label;
                }
            }
        });

        favoriteItems = _favoriteItems;
    }

    favoriteListKeysStore.subscribe(() => {
        refreshFavoriteItems()
    })

    onMount(() => {
        for (const [key, group] of Object.entries(menu)) {
            if (group.options.hasOwnProperty($currentListKey) && !favoriteItems.hasOwnProperty($currentListKey)) {
                showGroup(key);
            }
        }

        options = mapMenuToSelect(menu);
        groups = [...new Set(options.map(o => typeof o.group === 'string' ? o.group : '') ?? [])];
    })
</script>
<nav class="ac-table-screen-nav">

	<div class="ac-table-screen-nav__select lg:acu-hidden acu-mb-3">
		{#if options }
			<select bind:value={$currentListKey} class="acu-w-[100%]">
				{#each groups as group}
					<optgroup label={group}>
						{#each options.filter( o => o.group === group ) as option}
							<option value={option.value}>{option.label}</option>
						{/each}
					</optgroup>
				{/each}
			</select>
		{/if}
	</div>
	<div class="ac-table-screen-nav__list acu-hidden lg:acu-block acu-flex-grow">

		{#if Object.keys( favoriteItems ).length > 0}
			<div class="ac-menu-group">
				<button class="ac-menu-group__header">
					<GroupIcon icon="dashicons-star-empty" defaultIcon="cpacicon-gf-article"></GroupIcon>
					{i18n.menu.favorites}
				</button>
				<ul class="ac-menu-group-list">
					{#each Object.entries( favoriteItems ) as [ key, label ]}
						<ListScreenMenuItem
							{key}
							{label}
							on:selectItem={ () => handleMenuSelect(key)}
						/>
					{/each}
				</ul>
			</div>
		{/if}

		{#each Object.entries( menu ) as [ key, group ]}
			<div class="ac-menu-group">
				<button on:click={()=>toggleGroup(key)} class="ac-menu-group__header"
					class:closed={!openedGroups.includes( key )}>
					<GroupIcon icon={group.icon} defaultIcon="cpacicon-gf-article"></GroupIcon>
					{group.title}
					<span class="ac-menu-group__header__indicator dashicons dashicons-arrow-up-alt2"></span>
				</button>
				{#if openedGroups.includes( key )}
					<ul class="ac-menu-group-list">
						{#each Object.entries( group.options ) as [ key, label ]}
							<ListScreenMenuItem
								{key}
								{label}
								on:selectItem={ () => handleMenuSelect(key) }
							/>
						{/each}
					</ul>
				{/if}
			</div>
		{/each}
	</div>
</nav>