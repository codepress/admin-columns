<script lang="ts">

    import {createEventDispatcher, onMount} from "svelte";
    import AcIcon from "../AcIcon.svelte";

    export let type: 'info'|'warning' = 'info';
    export let message: string = '';
    export let styled: boolean = false;
    export let showIcon: boolean = false;

    let element: HTMLElement;

    const determineIcon = (type: string) => {
        switch (type) {
            case 'success':
                return 'yes-alt';
            case 'warning':
                return 'warning';
            case 'error':
                return 'dismiss';
            case 'notify':
                return 'bell';
            default:
                return 'info';
        }
    }

    let icon = determineIcon(type);
    let classNames =[ 'notice-' + type, 'ac-message', 'inline']

	if( styled ){
        classNames.push('ac-message--styled');
	}

    if( showIcon ){
        classNames.push('ac-message--icon');
    }

    onMount(() => {
        icon = determineIcon(type);
        let noticeContainer = document.querySelector('[data-ac-notices]');

        if( noticeContainer && element ) {
            noticeContainer.append( element );
        }
    });

</script>

<div class={classNames.join(' ')} bind:this={element}>
	{#if showIcon}
		<div class="ac-message__icon">
			<AcIcon icon={icon} pack="dashicons"/>
		</div>
	{/if}
	<p><slot></slot></p>
	{@html message}
</div>