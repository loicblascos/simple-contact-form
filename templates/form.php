<?php
/**
 * Form template
 *
 * @package Simple Contact Form
 * @author  Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get form action url.
$form = admin_url( 'admin-post.php' );

// Process class names.
$class = [
	'align' . $attributes['align'],
	$attributes['className'],
];
$class = array_map( 'sanitize_html_class', $class );
$class = array_filter( $class );
$class = implode( ' ', $class );

// Generate captcha numbers.
$rand_one = rand( 1, 9 );
$rand_two = rand( 1, 9 );
$captcha  = $rand_one . ' + ' . $rand_two;

// Get response message if exists.
$response = ! empty( $_GET['scf_response'] ) ? sanitize_key( wp_unslash( $_GET['scf_response'] ) ) : false;
$message  = $this->response( $response );

?>
<form id="simple-contact-form" class="<?php echo esc_attr( $class ); ?>" action="<?php echo esc_url( $form ); ?>" method="post">

	<?php wp_nonce_field( 'scf_submit', 'scf_submit_nonce' ); ?>
	<input type="hidden" name="action" value="scf_submit">

	<fieldset>

		<label for="scf-name"><?php echo esc_html( $attributes['name_label'] ); ?> <span aria-hidden="true">*</span></label>
		<input type="text" id="scf-name" name="name" placeholder="<?php echo esc_attr( $attributes['name_placeholder'] ); ?>" required>

		<label for="scf-email"><?php echo esc_html( $attributes['email_label'] ); ?> <span aria-hidden="true">*</span></label>
		<input type="email" id="scf-email" name="email" pattern="[^]+@[^]+[.][a-z]{2,63}$" placeholder="<?php echo esc_attr( $attributes['email_placeholder'] ); ?>" required>

		<label for="scf-subject"><?php echo esc_html( $attributes['subject_label'] ); ?> <span aria-hidden="true">*</span></label>
		<input type="text" id="scf-subject" name="subject" placeholder="<?php echo esc_attr( $attributes['subject_placeholder'] ); ?>" required>

		<label for="scf-message"><?php echo esc_html( $attributes['message_label'] ); ?> <span aria-hidden="true">*</span></label>
		<textarea id="scf-message" name="message" rows="25" cols="25" placeholder="<?php echo esc_attr( $attributes['message_placeholder'] ); ?>" required></textarea>

		<?php if ( ! empty( $attributes['attachment'] ) ) { ?>

			<label for="scf-attachment"><?php echo esc_html( $attributes['attachment_label'] ); ?></label>
			<div class="scf-attachment-holder">
				<input type="button" name="upload" value="<?php echo esc_attr( $attributes['upload_text'] ); ?>">
				<input type="file" id="scf-attachment" name="attachment">
			</div>

		<?php } ?>

		<label for="scf-captcha"><?php echo esc_html( $attributes['captcha_label'] ); ?> <span aria-hidden="true">*</span></label>
		<div class="scf-captcha-holder">
			<span id="scf-code" hidden><?php esc_html_e( 'Captcha Code', 'simple-contact-form' ); ?></span>
			<input type="text" id="scf-result" name="captcha" value="<?php echo esc_attr( $captcha ); ?>" aria-labelledby="scf-code" readonly>
			<span> = </span>
			<input type="number" id="scf-captcha" name="result" min="0" autocomplete="off" required>
		</div>

		<?php if ( ! empty( $attributes['register'] ) ) { ?>

			<label class="scf-checkbox-holder" for="scf-register">
				<input type="checkbox" id="scf-register" name="register">
				<span class="scf-checkbox-control"></span>
				<span class="scf-checkbox-label"><?php echo esc_html( $attributes['register_text'] ); ?></span>
			</label>

		<?php } ?>

	</fieldset>

	<div class="scf-submit-holder">
		<input type="submit" name="submit" value="<?php echo esc_attr( $attributes['submit_text'] ); ?>">
	</div>

	<?php
	if ( ! empty( $message ) ) {

		?>
		<div class="scf-response <?php echo sanitize_html_class( 'scf-' . $response ); ?>">
			<?php echo wp_kses_post( wpautop( $message ) ); ?>
		</div>
		<?php

	}
	?>

</form>
<?php
