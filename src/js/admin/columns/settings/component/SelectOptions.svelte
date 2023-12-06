<script lang="ts">
    import {onMount} from "svelte";
    import {uniqid} from "../../../../helpers/string";
    import {getSettingsTranslations} from "../../helper/translation";

    export let input: HTMLInputElement;
    export let options = [];

    const i18n = getSettingsTranslations();

    let sortEl;
    let activeOptions = [];

    const createRow = () => {
        return {
            value: '',
            label: '',
            id: uniqid()
        }
    }

    const addRow = () => {
        activeOptions.push(createRow());
        activeOptions = activeOptions;
    }

    const removeRow = (id) => {
        activeOptions = activeOptions.filter(f => f.id !== id);

        if (!activeOptions.length) {
            activeOptions.push(createRow());
        }
    }

    const addAfter = (id) => {
        const afterIndex = activeOptions.findIndex(d => d.id === id) + 1;

        activeOptions.splice(afterIndex, 0, createRow());
        activeOptions = activeOptions;

    }

    const dispatchValue = () => {
        input.value = JSON.stringify(getMappedValue());
    }

    const getMappedValue = () => {
        return activeOptions.map(ao => {
            return {
                value: ao.value,
                label: ao.label
            }
        });
    }

    onMount(() => {
        activeOptions = options.map(o => {
            return Object.assign(o, {id: uniqid()})
        });

        if (!activeOptions.length) {
            activeOptions.push(createRow());
        }


        jQuery(sortEl).sortable({
            axis: 'y',
            handle: '.-drag',
            stop: () => {
                let newIndex = [];
                let newItems = [];

                sortEl.childNodes.forEach(el => newIndex.push(el.dataset.id))

                newIndex.forEach(id => {
                    newItems.push(activeOptions.find(i => i.id === id));
                });

                activeOptions = newItems;
            }
        });

    })

    $: activeOptions && dispatchValue();
</script>

<div class="ac-setting-selectoptions" bind:this={sortEl}>
	{#each activeOptions as option, index(option.id)}
		<div class="ac-setting-selectoptions-row" data-id={option.id}>
			<div class="ac-setting-selectoptions-row__drag">
				<span class="cpacicon-move -drag"></span>
			</div>
			<div class="ac-setting-selectoptions-row__input">
				<input type="text" bind:value={option.value} placeholder={i18n.value}>
			</div>
			<div class="ac-setting-selectoptions-row__input">
				<input type="text" bind:value={option.label} placeholder={i18n.label}>
			</div>
			<div class="ac-setting-selectoptions-row__actions">
				<button class="ac-setting-selectoptions-row__remove" on:click|preventDefault={() => removeRow(option.id)}>
					<span class="dashicons dashicons-remove acp-cf-delete-btn"></span>
				</button>
				<button class="ac-setting-selectoptions-row__add" on:click|preventDefault={() => addAfter(option.id)}>
					<span class="dashicons dashicons-insert"></span>
				</button>
			</div>
		</div>
	{/each}
</div>

<style lang="scss">
	.ac-setting-selectoptions {
		position: relative;
	}

	.ac-setting-selectoptions-row {
		display: flex;
		gap: 8px;
		margin-bottom: 8px;
		width: 100%;
		background: #fff;
	}

	.ac-setting-selectoptions-row__input {
		flex-grow: 1;
	}

	.ac-setting-selectoptions-row__actions {
		button {
			background: none;
			border: none;
			padding: 0;
			cursor: pointer;
			color: #B4B4B4;

			&.ac-setting-selectoptions-row__remove {
				&:hover {
					color: var(--ac-notification-red);
				}
			}

			&.ac-setting-selectoptions-row__add {
				&:hover {
					color: #2271b1;
				}
			}
		}
	}

	.ac-setting-selectoptions-row__drag {
		display: flex;
		align-items: center;

		.-drag {
			padding: 3px 3px;
			cursor: move;
		}
	}

</style>