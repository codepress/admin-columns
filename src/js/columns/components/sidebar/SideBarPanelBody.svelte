<script lang="ts">
    import {AcPanelBody, AcPanelHeader} from "ACUi/acui-panel";

    export let title: string;
</script>


<AcPanelHeader title={title} type="h3-alt"/>
<AcPanelBody classNames={['acu-pt-1']}>
    <slot></slot>
</AcPanelBody>

