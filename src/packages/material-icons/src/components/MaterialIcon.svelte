<script lang="ts">

    import {IconName} from "../iconList";

    export let icon: IconName
	export let className: string;
</script>
<span class="ac-material-symbols {className}">{icon}</span>