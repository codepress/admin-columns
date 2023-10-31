<script lang="ts">
    import {currentListKey} from "../store/current-list-screen";
    import {createEventDispatcher, onMount} from "svelte";

    export let menu: AC.Vars.Admin.Columns.MenuItems;

    const dispatch = createEventDispatcher();

    let openedGroups: string[] = [];

    const handleMenuSelect = (key: string) => {
        dispatch('itemSelect', key)
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
        Object.keys(menu).forEach(g => showGroup(g));
    })
</script>
<style>
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
{#each Object.entries( menu ) as [ key, group ]}
	<div class="ac-menu-group">
		<div role="none" on:click={() => toggleGroup( key )}>
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