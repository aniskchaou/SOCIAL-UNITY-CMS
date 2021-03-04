<?php if ( ! defined( 'FW' ) ) die( 'Forbidden' );
/**
 * @var FW_Ext_Backups_Demo[] $demos
 */

/**
 * @var FW_Extension_Backups $backups
 */
$backups = fw_ext('backups');

if ($backups->is_disabled()) {
	$confirm = '';
} else {
	$confirm = esc_html__(
		'IMPORTANT: Installing this demo content will delete the content you currently have on your website. However, we create a backup of your current content in (Tools > Backup) You can restore the backup from there at any time in the future.',
		'olympus'
	);
}

global $olympus_license; // License object

?>
<h2><?php esc_html_e('Demo Content Install', 'olympus') ?></h2>

<?php 
$post_license = false;
if ( isset($_POST['license_key']) && !empty( $_POST['license_key'] ) ) {
	$olympus_license->license_form_submit([
		'_nonce'      => wp_create_nonce( 'Olympus - Social Network' ), // create a nonce with name
		'_action'     => 'active', // active, deactive
		'license_key' => $_POST['license_key'], // no need to provide if you want to deactive
	]);
	if ( ! $olympus_license->error ) {
		$post_license = true;
	}
}
?>

<?php if ( ( $olympus_license->is_valid() || $post_license ) || olympus_env_api_check() ) { ?>
<div>
	<?php if ( !class_exists('ZipArchive') ): ?>
		<div class="error below-h2">
			<p>
				<strong><?php _e( 'Important', 'olympus' ); ?></strong>:
				<?php printf(
					__( 'You need to activate %s.', 'olympus' ),
					'<a href="http://php.net/manual/en/book.zip.php" target="_blank">'. __('zip extension', 'olympus') .'</a>'
				); ?>
			</p>
		</div>
	<?php endif; ?>

	<?php if ($http_loopback_warning = fw_ext_backups_loopback_test()) : ?>
		<div class="error">
			<p><strong><?php _e( 'Important', 'olympus' ); ?>:</strong> <?php echo '' . $http_loopback_warning; ?></p>
		</div>
		<script type="text/javascript">var fw_ext_backups_loopback_failed = true;</script>
	<?php endif; ?>
</div>

<p></p>
<div class="theme-browser rendered" id="fw-ext-backups-demo-list">
<?php foreach ($demos as $demo): ?>
	<div class="theme fw-ext-backups-demo-item" id="demo-<?php echo esc_attr($demo->get_id()) ?>">
		<div class="theme-screenshot">
			<img src="<?php echo esc_attr($demo->get_screenshot()); ?>" alt="Screenshot" />
		</div>
		<?php if ($demo->get_preview_link()): ?>
			<a class="more-details" target="_blank" href="<?php echo esc_attr($demo->get_preview_link()) ?>">
				<?php esc_html_e('Live Preview', 'olympus') ?>
			</a>
		<?php endif; ?>
		<h3 class="theme-name"><?php echo esc_html($demo->get_title()); ?></h3>
		<div class="theme-actions">
			<a class="button button-primary"
			   href="#" onclick="return false;"
			   data-confirm="<?php echo esc_attr($confirm); ?>"
			   data-install="<?php echo esc_attr($demo->get_id()) ?>"><?php esc_html_e('Install', 'olympus'); ?></a>
		</div>
	</div>
<?php endforeach; ?>
</div>
<?php } else { 
	$olympus_license->menu_output(' ');
} ?>