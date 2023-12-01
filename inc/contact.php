<?php
/**
 * This function displays the validation messages, the success message, the container of the validation messages, and the
 * contact form.
 */

require get_theme_file_path('inc/email-templates/admin-email.php');

function display_contact_form() {

	$validation_messages = [];
	$success_message = '';

	// RECAPTCHA
	$public_key = </>;
	$private_key = "</>";
	$url = "https://www.google.com/recaptcha/api/siteverify";


	if(array_key_exists('submit-form', $_POST))
	{
		echo "<pre>";print_r($_POST);"</pre>";
		$response_key = $_POST['g-recaptcha-response'];
		$response = file_get_contents($url.'?secret='.$private_key.'&response='.$response_key.'&remoteip='.$_SERVER['REMOTE_ADDR']);
		$response = json_decode($response);
		echo "<pre>";print_r($response);"</pre>";

		if($response->success == 1) {
			echo "Your information was valid";
		} else {
			echo "No Robots, Pleez!";
		}
	}
	// END RECAPTCHA

	if ( isset( $_POST['contact_form'] ) ) {

		//Sanitize the data
		$full_name = isset( $_POST['full_name'] ) ? sanitize_text_field( $_POST['full_name'] ) : '';
		$email     = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
		$message   = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

		//Validate the data
		if ( strlen( $full_name ) === 0 ) {
			$validation_messages[] = esc_html__( 'Please enter a valid name.', 'twentytwentyone' );
		}

		if ( strlen( $email ) === 0 or
		     ! is_email( $email ) ) {
			$validation_messages[] = esc_html__( 'Please enter a valid email address.', 'twentytwentyone' );
		}

		if ( strlen( $message ) === 0 ) {
			$validation_messages[] = esc_html__( 'Please enter a valid message.', 'twentytwentyone' );
		}

		//Send an email to the WordPress administrator if there are no validation errors
		if ( empty( $validation_messages ) ) {

			$adminEmail    = get_option( 'admin_email' );
			$subject = 'New message from ' . $full_name;
			// $adminMessage = $message . ' - The email address of the customer is: ' . $mail;
			$customerSubject = 'Thank you for your message, ' . $full_name;
			$customerMessage = 'You sent the following message to Pinnacle Solar: ' . $message;



			// wp_mail( $adminEmail, $subject, $adminMessage );
			// wp_mail( $email, $customerSubject, $customerMessage );

      $total_success = 'Your message has been successfully sent. ';
			$success_message = esc_html__( $total_success, 'twentytwentyone' );

		}

	}

	//Display the validation errors
	if ( ! empty( $validation_messages ) ) {
		foreach ( $validation_messages as $validation_message ) {
			echo '<div class="validation-message">' . esc_html( $validation_message ) . '</div>';
		}
	}

	//Display the success message
	if ( strlen( $success_message ) > 0 ) {
		echo '<div class="success-message">' . esc_html( $success_message ) . '</div>';
	}

	?>

    <!-- Echo a container used that will be used for the JavaScript validation -->
    <div id="validation-messages-container"></div>

    <form class="contact-form" id="contact-form" action="<?php echo esc_url( get_permalink() ); ?>" method="post">
				<script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <input type="hidden" name="contact_form">

        <div class="form-section">
            <label for="full-name"><?php echo esc_html( 'Full Name', 'twentytwentyone' ); ?></label>
            <input type="text" id="full-name" name="full_name">
        </div>

        <div class="form-section">
            <label for="email"><?php echo esc_html( 'Email', 'twentytwentyone' ); ?></label>
            <input type="text" id="email" name="email">
        </div>

        <div class="form-section">
            <label for="message"><?php echo esc_html( 'Message', 'twentytwentyone' ); ?></label>
            <textarea id="message" name="message"></textarea>
        </div>

				<div class="g-recaptcha" data-sitekey="<?php echo $public_key; ?>"></div>

        <input type="submit" id="contact-form-submit" name="submit-form" value="<?php echo esc_attr( 'Submit', 'twentytwentyone' ); ?>">

    </form>

	<?php

}
