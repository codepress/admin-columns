<script lang="ts">
    export let title: string;
    export let type: 'h3' | 'h2' | 'h3-alt' = 'h2'
</script>


{#if type === 'h2' }
    <h2 class="acu-my-[0] acu-text-[22px] acu-font-medium">{title}</h2>
{:else if type === 'h3-alt'}
    <h3 class="acu-my-[0] acu-text-[18px] acu-font-bold">{title}</h3>
{:else}
    <h3 class="acu-my-[0]">{title}</h3>
{/if}
