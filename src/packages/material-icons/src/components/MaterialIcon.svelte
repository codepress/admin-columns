<script lang="ts">

    import {IconName} from "@ac/material-icons/src/icons";

    export let icon: IconName;
	export let className: string = '';
    export let size: 'default'|'large'|'small'|'custom' = 'default';

    const sizeClasses = {
        default: 'acu-text-[20px]',
        large: 'acu-text-[24px]',
        small: 'acu-text-[18px]',
        custom: '' // Define with class
    };

    $: sizeClass = sizeClasses[size];
</script>
<span class="ac-material-symbols {className} {sizeClass}">{icon}</span>