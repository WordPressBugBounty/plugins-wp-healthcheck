<?php
/**
 * Admin notices handler.
 *
 * Handles displaying system notices in the admin interface.
 *
 * @package wp-healthcheck
 * @since 1.4.1
 */

namespace THSCD\WPHC\Admin;

use THSCD\WPHC\Core\Hookable;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Notices.
 *
 * Manages admin notices for health checks.
 *
 * @since 1.4.1
 */
class Notices implements Hookable {

	/**
	 * Option to disable admin notices.
	 *
	 * @since 1.4.1
	 *
	 * @var string
	 */
	const DISABLE_NOTICES_OPTION = 'wphc_disable_admin_notices';

	/**
	 * Transient to store if an admin notice should be displayed or not.
	 *
	 * @since 1.4.1
	 *
	 * @var string
	 */
	const HIDE_NOTICES_TRANSIENT = 'wphc_hide_admin_notices';

	/**
	 * Register the WordPress hooks.
	 *
	 * @since 1.4.1
	 */
	public function hooks() {

		add_action( 'admin_notices', [ $this, 'display_notices' ] );
		add_action( 'in_admin_header', [ $this, 'hide_unrelated_notices' ] );
	}

	/**
	 * Display admin notices.
	 *
	 * @since 1.4.1
	 */
	public function display_notices() {

		if ( get_option( self::DISABLE_NOTICES_OPTION ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( ! $screen ) {
			return;
		}

		// Get the admin page hookname.
		$hookname = wphc( 'admin.dashboard' )->get_hookname();

		// Only show on dashboard or plugin admin page.
		if ( ! in_array( $screen->id, [ $hookname, 'dashboard' ], true ) ) {
			return;
		}

		$notices = [ 'php', 'database', 'wordpress', 'web', 'ssl', 'https', 'plugins' ];

		$notices_transient = get_transient( self::HIDE_NOTICES_TRANSIENT );

		foreach ( $notices as $notice ) {
			if ( ! isset( $notices_transient[ $notice ] ) ) {
				wphc( 'util.view' )->render( 'notices/' . $notice );
			}
		}
	}

	/**
	 * Hide the admin notices registered by other plugins or themes on the plugin page.
	 *
	 * @since 1.5.0
	 */
	public function hide_unrelated_notices() {

		$screen = get_current_screen();

		if ( ! $screen || $screen->id !== wphc( 'admin.dashboard' )->get_hookname() ) {
			return;
		}

		remove_all_actions( 'network_admin_notices' );
		remove_all_actions( 'user_admin_notices' );
		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'all_admin_notices' );

		// Restore the plugin's own notices.
		add_action( 'admin_notices', [ $this, 'display_notices' ] );
	}
}
