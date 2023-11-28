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
        selectValue = key;
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
        Object.keys(menu).forEach(g => showGroup(g));
        options = mapMenutoSelect(menu);
    })
</script>
<style>
	.ac-menu-group {
		margin-bottom: 30px;
	}

	ul {
		margin-left: 20px;
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
<br><br>
{#each Object.entries( menu ) as [ key, group ]}
	<div class="ac-menu-group">
		<div role="none" on:click={() => toggleGroup( key )}>
			<strong>
				<GroupIcon icon={group.icon} defaultIcon="cpacicon-gf-article"></GroupIcon>
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