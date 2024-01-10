<script lang="ts">
    import {onMount} from "svelte";

    export let customClass: string|null = '';
    export let icon: string|null = '';
    export let size: string|null = 'md';
    export let pack: string|null = 'ac'; // ac|dashicons

    let iconName = '';
    let sizeClass = `acui-icon--${size}`;
    let customClasses = [sizeClass];

    onMount( () => {
		if( pack === 'ac' ){
            iconName = `cpacicon-${icon}`;
		}

        if( pack === 'dashicons' ){
            iconName = `dashicons dashicons-${icon}`;
        }

        if ( customClass ){
            customClasses.push( customClass)
		}
	});
</script>
<style>
	.acui-icon {
		display: inline-flex;
		width: 20px;
		height: 20px;
		justify-content: center;
		align-items: center;
		font-size: 18px;
		vertical-align: middle;
	}

	.acui-icon.acui-icon--sm {
		width: 16px;
		height: 16px;
		font-size: 14px;
	}

	.acui-icon.acui-icon--lg {
		width: 25px;
		height: 25px;
		font-size: 23px;
	}

	.acui-icon.acui-icon--xl {
		width: 30px;
		height: 30px;
		font-size: 28px;
	}

	.acui-icon.acui-icon--sm .dashicons {
		width: 16px;
		height: 16px;
		font-size: 16px;
	}

	.acui-icon.acui-icon--lg .dashicons {
		width: 25px;
		height: 25px;
		font-size: 25px;
	}

	.acui-icon.acui-icon--xl .dashicons {
		width: 30px;
		height: 30px;
		font-size: 25px;
	}

</style>
<span class="acui-icon {sizeClass} {customClass}">
	<span class="{iconName}"></span>
</span>