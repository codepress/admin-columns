<script lang="ts">

    export let size: 'small' | 'medium' | 'large' = 'small';


    export let calcSizeClass = (size: string) => {
        switch (size) {
            case'medium':
                return 'acu-size-5';
            case 'large':
                return 'acu-size-6';
            case'small':
            default:
                return 'acu-size-4';
        }
    }

    $: sizeClass = calcSizeClass(size);

</script>

<svg class="{sizeClass} acu-animate-spin acu-text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
	viewBox="0 0 24 24">
	<circle class="acu-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
	<path class="acu-opacity-75" fill="currentColor"
		d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>