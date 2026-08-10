<?php
if ( ! defined( 'WPHC' ) ) {
	exit;
}

$server_data = wphc( 'module.server' )->get_data();
$server_ip   = wphc( 'module.server' )->get_ip();

$php_status  = wphc( 'module.server' )->is_updated( 'php' );
$php_tooltip = '';

if ( $php_status === 'need_update' ) {
	$php_update = wphc( 'module.server' )->get_php_update_version();

	if ( ! empty( $php_update ) ) {
		/* translators: %s is the latest PHP version available for the branch installed on the server. */
		$php_tooltip = sprintf( __( 'Version %s is available.', 'wp-healthcheck' ), $php_update );
	}
}

if ( ! empty( $server_data['web']['service'] ) ) {
	if ( preg_match( '/(?:nginx|apache|litespeed)/', $server_data['web']['service'] ) ) {
		$web_server = $server_data['web']['service'];

		if ( ! empty( $server_data['web']['version'] ) ) {
			$web_server .= '/' . $server_data['web']['version'];
		}
	} elseif ( ! empty( $server_data['web']['version'] ) ) {
		$web_server = $server_data['web']['version'];
	}
}
?>

<div class="wphc_system_info">
	<ul>
		<li><?php esc_html_e( 'WordPress', 'wp-healthcheck' ); ?></li>
		<li class="<?php echo esc_attr( wphc( 'module.server' )->is_updated( 'wp' ) ); ?>"><?php echo esc_html( $server_data['wp'] ); ?></li>
	</ul>
	<ul>
		<li><?php esc_html_e( 'PHP', 'wp-healthcheck' ); ?></li>
		<li class="<?php echo esc_attr( $php_status ); ?>"<?php echo $php_tooltip !== '' ? ' data-tooltip="' . esc_attr( $php_tooltip ) . '" aria-label="' . esc_attr( $server_data['php'] . ' (' . $php_tooltip . ')' ) . '"' : ''; ?>><?php echo esc_html( $server_data['php'] ); ?></li>
	</ul>
	<ul>
		<?php if ( $server_data['database']['service'] === 'MariaDB' ) : ?>
			<li><?php esc_html_e( 'MariaDB', 'wp-healthcheck' ); ?></li>
		<?php else : ?>
			<li><?php esc_html_e( 'MySQL', 'wp-healthcheck' ); ?></li>
		<?php endif; ?>

		<li class="<?php echo esc_attr( wphc( 'module.server' )->is_updated( strtolower( $server_data['database']['service'] ) ) ); ?>"><?php echo esc_html( $server_data['database']['version'] ); ?></li>
	</ul>

	<?php if ( ! empty( $web_server ) ) : ?>
		<ul>
			<li><?php esc_html_e( 'Web Server', 'wp-healthcheck' ); ?></li>

			<li class="<?php echo esc_attr( wphc( 'module.server' )->is_updated( $server_data['web']['service'] ) ); ?>"><?php echo esc_html( $web_server ); ?></li>
		</ul>
	<?php endif; ?>

	<?php if ( ! empty( $server_ip ) ) : ?>
		<ul>
			<li><?php esc_html_e( 'Server IP', 'wp-healthcheck' ); ?></li>

			<li><?php echo esc_html( $server_ip ); ?></li>
		</ul>
	<?php endif; ?>
</div>
