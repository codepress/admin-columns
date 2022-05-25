<script lang="ts">
    export let content = '';
    export let position = '';
    let onElement = false;

    export let closeHandler;

    export const isOnElement = (): boolean => {
        return onElement;
    }

    const checkLeave = () => {
        onElement = false;
        closeHandler();
    }

</script>

<div class="ac-pointer-modal -{position} " on:mouseenter={()=> onElement = true} on:mouseleave={() => checkLeave()}>
	<div class="ac-pointer-modal__arrow" data-pos={position}>
	</div>
	<div class="ac-pointer-modal__content">
		{@html content}
	</div>
</div>