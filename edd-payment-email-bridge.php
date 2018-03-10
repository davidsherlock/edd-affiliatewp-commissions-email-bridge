<?php
/**
 * Plugin Name:     Easy Digital Downloads - AffiliateWP Commissions Email Bridge
 * Plugin URI:      https://sellcomet.com/
 * Description:     Syncs the AffiliateWP affiliate payment email with Commissions.
 * Version:         1.0.0
 * Author:          Sell Comet
 * Author URI:      https://sellcomet.com
 * Text Domain:     edd-affiliatewp-commissions-email-bridge
 *
 * @package         EDD\AffiliateWP_Commissions_Email_Bridge
 * @author          Sell Comet
 * @copyright       Copyright (c) 2018, Sell Comet
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Update the AffiliateWP Affiliate Payment Email from the Frontend Profile Editor
 *
 * @since       1.0.0
 * @param       int $user_id  The User ID being edited
 * @param       array $userdata The array of user info
 * @return      void
 */
function edd_update_affiliatewp_payment_email_frontend( $user_id, $userdata ) {
	if ( ! empty( $_POST['eddc_paypal_email'] ) && is_email( $_POST['eddc_paypal_email'] ) ) {

		// Verify current user is a registered affiliate
		if ( affwp_is_affiliate( $user_id ) ) {

			// Get the Affiliate ID
			$affiliate_id = affwp_get_affiliate_id( $user_id );

			// Update the affiliate payment_email field
			affiliate_wp()->affiliates->update( $affiliate_id, array( 'payment_email' => sanitize_text_field( $_POST['eddc_paypal_email'] ) ), '', 'affiliate' );

		}
	}
}
add_action( 'edd_pre_update_user_profile', 'edd_update_affiliatewp_payment_email_frontend', 10, 2 );

/**
 * Update the AffiliateWP Affiliate Payment Email from the admin profile edit screen
 *
 * @since       1.0.0
 * @param       int $user_id  The User ID being edited
 * @return      void
 */
function edd_update_affiliatewp_payment_email_admin( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	if ( ! empty( $_POST['eddc_user_paypal'] ) && is_email( $_POST['eddc_user_paypal'] ) ) {

		// Verify current user is a registered affiliate
		if ( affwp_is_affiliate( $user_id ) ) {

			// Get the Affiliate ID
			$affiliate_id = affwp_get_affiliate_id( $user_id );

			// Update the affiliate payment_email field
			affiliate_wp()->affiliates->update( $affiliate_id, array( 'payment_email' => sanitize_text_field( $_POST['eddc_user_paypal'] ) ), '', 'affiliate' );
		}
	}
}
add_action( 'personal_options_update', 'edd_update_affiliatewp_payment_email_admin' );
add_action( 'edit_user_profile_update', 'edd_update_affiliatewp_payment_email_admin' );
