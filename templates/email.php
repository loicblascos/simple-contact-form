<?php
/**
 * Email template
 *
 * @package Simple Contact Form
 * @author  Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<h3><?php esc_html_e( 'You have a new contact', 'simple-contact-form' ); ?></h3><br>

<p>
	<strong><?php esc_html_e( 'Date', 'simple-contact-form' ); ?>: </strong><?php echo esc_html( $this->fields['date'] ); ?>
</p>

<p>
	<strong><?php esc_html_e( 'Name', 'simple-contact-form' ); ?>: </strong><?php echo esc_html( $this->fields['name'] ); ?>
</p>

<p>
	<strong><?php esc_html_e( 'Email', 'simple-contact-form' ); ?>: </strong>
	<a href="<?php echo esc_url( 'mailto:' . $this->fields['email'] ); ?>">
		<?php echo esc_html( $this->fields['email'] ); ?>
	</a>
</p>

<p>
	<strong><?php esc_html_e( 'Subject', 'simple-contact-form' ); ?>: </strong><?php echo esc_html( $this->fields['subject'] ); ?>
</p>

<p>
	<strong><?php esc_html_e( 'Message', 'simple-contact-form' ); ?>:</strong><br>
	<?php echo wp_kses_post( wpautop( $this->fields['message'] ) ); ?>
</p>
<?php
