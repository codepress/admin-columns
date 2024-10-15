<script lang="ts">


    import {getColumnSettingsConfig} from "../../utils/global";

    export let promo:P
    const config = getColumnSettingsConfig();
    const features = config.pro_banner!.features


    //features = config.pro_modal.features;

</script>

<div class="acu-bg-gray-dark acu-p-[20px] ac-probanner acu-rounded-t-[10px]">
	<h3 class="acu-text-[white] acu-text-[34px] acu-leading-snug acu-my-[0] ">Upgrade to
		<span class="acu-text-pink">Pro</span></h3>
	<div class="acu-text-[white]">
		<p>Take Admin Columns to the next level:</p>
		<ul class="acu-mb-4">
			{#each features as feature}
				<li><a href="{feature.url}">{feature.label}</a></li>
			{/each}
		</ul>
		<a target="_blank"
			href="" class="acui-button acui-button-pink acu-block acu-text-center acu-text-[15px]">
			Get Admin Columns Pro</a>
	</div>
</div>
{#if config.pro_banner!.promo}
	<div class="acu-bg-[white] acu-p-[20px] acu-rounded-b-[10px]">
		<h3>Get 10% Off!</h3>
		<p>
			Submit your email and we'll send you a discount for 10% off.
		</p>
	</div>
{/if}