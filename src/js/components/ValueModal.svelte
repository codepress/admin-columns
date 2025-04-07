<script lang="ts">
    import {LocalizedAcTable} from "../types/table";
    import {onDestroy, onMount} from "svelte";
    import axios, {AxiosResponse} from "axios";
    import {ValueModalItem, ValueModalItemCollection} from "../types/admin-columns";
    import {getTableConfig, getTableTranslation} from "../table/utils/global";

    export let items: ValueModalItemCollection
    export let objectId: number;
    export let destroyHandler: Function;

    const ajaxurl: string = (window as any).ajaxurl;

    let modalClass: string = '';
    let columnTitle: string;
    let title: string;
    let content: string;
    let editLink: string;
    let downloadLink: string;
    let viewLink: string;
    let source;
    let translation = getTableTranslation();
    let index: number;
    let hasNext: boolean;
    let hasPrev: boolean;

    const CancelToken = axios.CancelToken;

    const close = () => {
        destroyHandler();
    }

    const initKeyPress = (e: KeyboardEvent) => {
        if (e.key === 'Escape') {
            destroyHandler();
        }
        if (e.key === 'ArrowLeft') {
            prevItem();
            e.preventDefault();
        }
        if (e.key === 'ArrowRight') {
            nextItem();
            e.preventDefault();
        }
    }


    const getTitle = (item: ValueModalItem) => {
        return item.title ?? `${columnTitle}`;
    }

    const updateData = (item: ValueModalItem) => {
        objectId = item.objectId;
        title = translation.value_loading;
        content = `<span class="loading">${translation.value_loading}</span>`;
        editLink = item.editLink;
        downloadLink = item.downloadLink;
        viewLink = item.viewLink;

        if (source) {
            source.cancel();
        }

        source = CancelToken.source();
        const tableConfig = getTableConfig();

        return axios({
            method: 'get',
            url: ajaxurl,
            cancelToken: source.token,
            params: {
                action: 'ac-extended-value',
                list_id: tableConfig.layout,
                column_name: item.columnName,
                object_id: item.objectId,
                view: item.view,
                params: item.params,
                _ajax_nonce: tableConfig.ajax_nonce
            }
        }).then((response: AxiosResponse<string>) => {
            content = response.data
            title = getTitle(item);
        }).catch(r => {
            content = 'Content could not be loaded.';
            title = 'Error loading content.';
        });
    }


    const determineSiblings = (): void => {
        hasNext = (index + 1) < items.length;
        hasPrev = index !== 0;
    }

    const updateItem = (index: number) => {
        updateData(items[index]);
    }

    const nextItem = () => {
        if (hasNext) {
            index = index + 1;
            updateItem(index);
            determineSiblings();
        }
    }

    const prevItem = () => {
        if (hasPrev) {
            index = index - 1;
            updateItem(index);
            determineSiblings();
        }
    }

    onMount(() => {
        let item = items.find(i => i.objectId === objectId);

        if (item) {
            index = items.findIndex(item => item.objectId === objectId);
            columnTitle = item.element.closest('td')!.dataset.colname as string;

            if (items.length > 1) {
                document.addEventListener('keydown', initKeyPress);
            }

            modalClass = item.element.dataset.modalClass ?? ''
            title = item.title ?? ``;

            updateData(item);
            determineSiblings();
        }

    });

    onDestroy(() => {
        document.removeEventListener('keydown', initKeyPress);
    });
</script>

<div class="ac-value-modal {modalClass}" on:click={close} on:keypress={()=>{}} role="none">
	<div class="ac-value-modal-background">
	</div>
	<div class="ac-value-modal-container">
		<div class="ac-value-modal-panel" on:click|stopPropagation on:keypress={()=>{}} role="none">
			<div class="ac-value-modal-panel__header">
				<div class="ac-value-modal-title">
					{#if title}
						<h2 title={title}>{title}</h2>
					{/if}
					<span class="ac-badge">#{objectId}</span>
				</div>
				<div class="ac-value-modal-actions">
					<button on:click={close}><span class="dashicons dashicons-no-alt"></span></button>
				</div>
			</div>

			<div class="ac-value-modal-panel__body">
				{@html content}
			</div>

			<div class="ac-value-modal-panel__footer">
				<div class="ac-value-modal__edit">
					{#if editLink }
						<a class="edit btn button" href="{editLink}">{translation.edit}</a>
					{/if}
					{#if viewLink }
						<a class="edit btn button" href="{viewLink}">{translation.view}</a>
					{/if}
					{#if downloadLink }
						<a class="edit btn button" href="{downloadLink}" download>{translation.download}</a>
					{/if}
				</div>
				{#if items.length > 1 }
					<div class="ac-value-modal__navigation">
						<button on:click|preventDefault={prevItem} title="Previous" class="btn" disabled='{!hasPrev}'>
							<span class="dashicons dashicons-arrow-left-alt2"></span></button>
						<button on:click|preventDefault={nextItem} title="Next" class="btn" disabled='{!hasNext}'>
							<span class="dashicons dashicons-arrow-right-alt2"></span></button>
					</div>
				{/if}
			</div>
		</div>
	</div>
</div>