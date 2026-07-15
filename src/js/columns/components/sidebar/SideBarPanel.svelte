<script lang="ts">
    import {AcPanel} from "ACUi/acui-panel";
    import SideBarPanelBody from "./SideBarPanelBody.svelte";

    export let title: string;
    export let type: 'h3' | 'h2' | 'h3-alt' = 'h3-alt';
</script>

<AcPanel>
    <svelte:fragment slot="body">
        <SideBarPanelBody title={title} type={type}>
            <slot></slot>
        </SideBarPanelBody>
    </svelte:fragment>
</AcPanel>