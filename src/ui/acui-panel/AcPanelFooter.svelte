<script lang="ts">
    export let border: boolean = false;
    export let classNames: string[] = [];
</script>

<div
    class={'acu-px-[20px] acu-pt-[20px] acu-pb-[20px] ' + classNames.join(' ')}
    class:acu-border-0={!border}
    class:acu-border-t={!border}
    class:acu-border-solid={!border}
    class:acu-border-ui-border={!border}
>
    <slot></slot>
</div>