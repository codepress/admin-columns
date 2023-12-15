<script lang="ts">
    import {currentListKey} from "../store/current-list-screen";
    import {createEventDispatcher, onMount} from "svelte";
    import GroupIcon from "./GroupIcon.svelte";
    import {SvelteSelectItem} from "../../types/select";
    import Select from "svelte-select";

    export let menu: AC.Vars.Admin.Columns.MenuItems;

    const dispatch = createEventDispatcher();

    let openedGroups: string[] = [];
    let options: SvelteSelectItem[];
    let selectValue = '';

    const handleMenuSelect = (key: string) => {
        dispatch('itemSelect', key)
    }

    const handleSelect = (e: CustomEvent<SvelteSelectItem>) => {
        handleMenuSelect(e.detail.value.toString());
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


    const mapMenutoSelect = (menu: AC.Vars.Admin.Columns.MenuItems): SvelteSelectItem[] => {
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

        selectValue = $currentListKey;

        return result;
    }

    const groupBy = (item: SvelteSelectItem) => item.group;

    onMount(() => {

        for (const [key, group] of Object.entries(menu)) {
            if (group.options.hasOwnProperty($currentListKey)) {
                showGroup(key);
            }
        }
        options = mapMenutoSelect(menu);
    })
</script>
<nav class="ac-table-screen-nav">
	<div class="ac-table-screen-nav__select">
		<Select
				bind:value={selectValue}
				items={options}
				{groupBy}
				class="-acui"
				placeholder="Select"
				clearable={false}
				showChevron
				on:input={handleSelect }>

		</Select>
	</div>
	<div class="ac-table-screen-nav__list">
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
							<li class="ac-menu-group-list__item" class:active={$currentListKey === key}>
								<a
										class:active={$currentListKey === key}
										class="ac-menu-group-list__link" href={'#'}
										on:click|preventDefault={ () => selectValue = key }>{label}</a>
							</li>
						{/each}
					</ul>
				{/if}
			</div>
		{/each}
	</div>
</nav>