<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @global \AC\Admin\Promo   $promo
 * @global \AC\Integration[] $integrations
 * @global int               $discount
 * @global string            $price
 */

$integrations = $this->integrations;
$promo = $this->promo;
$discount = $this->discount;
$price = $this->price;

?>

<div class="sidebox" id="ac-pro-version">
	<div class="padding-box">
		<h3>
			<a href="<?php echo esc_url( ac_get_site_utm_url( 'upgrade-to-admin-columns-pro', 'banner', 'title' ) ); ?>">
				<?php _e( 'Upgrade to', 'codepress-admin-columns' ); ?>&nbsp;<span><?php _e( 'Pro', 'codepress-admin-columns' ); ?></span>
			</a>
		</h3>

		<div class="inside">
			<p><?php _e( 'Take Admin Columns to the next level:', 'codepress-admin-columns' ); ?></p>
			<ul class="features">
				<?php

				$items = array(
					'sorting'       => __( 'Add sortable columns', 'codepress-admin-columns' ),
					'filtering'     => __( 'Add filterable columns', 'codepress-admin-columns' ),
					'editing'       => __( 'Edit your column content directly', 'codepress-admin-columns' ),
					'column-sets'   => __( 'Create multiple columns sets', 'codepress-admin-columns' ),
					'import-export' => __( 'Import &amp; Export settings', 'codepress-admin-columns' ),
				);

				foreach ( $items as $utm_content => $label ) : ?>
					<li>
						<a href="<?php echo esc_url( ac_get_site_utm_url( 'upgrade-to-admin-columns-pro', 'banner', 'usp-' . $utm_content ) ); ?>"><?php echo esc_html( $label ); ?></a>
					</li>
				<?php endforeach; ?>

			</ul>

			<?php
			if ( $integrations ) : ?>
				<strong><?php _e( 'Extra Columns for:', 'codepress-admin-columns' ); ?></strong>
				<ul>
					<?php foreach ( $integrations as $integration ) : ?>
						<li class="acp-integration">
							<a href="<?php echo esc_url( $integration->get_link() ); ?>" target="_blank">
								<strong><?php echo $integration->get_title(); ?></strong>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<p class="center">
				<?php echo ac_helper()->html->link( ac_get_site_utm_url( 'upgrade-to-admin-columns-pro', 'banner' ), sprintf( __( 'Prices starting from %s', 'codepress-admin-columns' ), '$' . $price ), array( 'class' => 'ac-pro-prices' ) ); ?>
			</p>
			<p class="center nopadding">
				<?php if ( ! $promo ) : ?>
					<a target="_blank" href="<?php echo esc_url( ac_get_site_utm_url( 'upgrade-to-admin-columns-pro', 'banner' ) ); ?>" class="more">
						<?php _e( 'Learn more about Pro', 'codepress-admin-columns' ); ?>
					</a>
				<?php endif; ?>
			</p>
		</div>
	</div>

	<?php if ( $promo ) : ?>

		<div class="padding-box ac-pro-deal">
			<?php $promo->display(); ?>
		</div>

	<?php else : ?>

		<div class="padding-box ac-pro-newsletter">
			<h3>
				<?php echo esc_html( sprintf( __( 'Get %s Off!', 'codepress-admin-columns' ), $discount . '%' ) ); ?>
			</h3>
			<div class="inside">
				<p>
					<?php echo esc_html( sprintf( __( "Submit your email and we'll send you a discount for %s off.", 'codepress-admin-columns' ), $discount . '%' ) ); ?>
				</p>
				<?php
				$user_data = get_userdata( get_current_user_id() );
				?>
				<form method="post" action="<?php echo esc_url( ac_get_site_utm_url( 'upgrade-to-admin-columns-pro', 'send-coupon' ) ); ?>" target="_blank">
					<input name="action" type="hidden" value="mc_upgrade_pro">
					<input name="EMAIL" placeholder="<?php esc_attr_e( "Your Email", 'codepress-admin-columns' ); ?>" value="<?php echo esc_attr( $user_data->user_email ); ?>" required>
					<input name="FNAME" placeholder="<?php esc_attr_e( "Your First Name", 'codepress-admin-columns' ); ?>" required>
					<input type="submit" value="<?php esc_attr_e( "Send me the discount", 'codepress-admin-columns' ); ?>" class="acp-button">
				</form>
			</div>
		</div>

	<?php endif; ?>

</div>