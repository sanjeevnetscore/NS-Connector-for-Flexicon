<?php

// Handle form submission
function custom_login_form_handler() {
    $confirmation_message = '';

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['custom_login_form_submit'])) {
        
        // Check nonce for security
        if (!isset($_POST['custom_login_nonce']) || !wp_verify_nonce($_POST['custom_login_nonce'], 'custom_login_nonce_action')) {
            $confirmation_message = '<p style="color: red;">Security check failed. Please try again.</p>';
        } else {
            // Sanitize and validate form inputs
            $name = sanitize_text_field($_POST['name']);
            $company_email = sanitize_email($_POST['company_email']);
            $mobile_number = sanitize_text_field($_POST['mobile_number']);
            $message = sanitize_textarea_field($_POST['message']);

            // Validate email
            if (!is_email($company_email)) {
                $confirmation_message = '<p style="color: red;">Please enter a valid email address.</p>';
            } else {
                // Email setup
                $to = 'smudela@netscoretech.com'; // Specific email address
                $subject = 'New Submission from Flexicon integration plugin';
                $headers = ['Content-Type: text/html; charset=UTF-8'];
                $body = "
                    <h2>New Form Submission</h2>
                    <p><strong>Name:</strong> {$name}</p>
                    <p><strong>Company Email:</strong> {$company_email}</p>
                    <p><strong>Mobile Number:</strong> {$mobile_number}</p>
                    <p><strong>Message:</strong><br>{$message}</p>
                ";

                // Send the email
                if (wp_mail($to, $subject, $body, $headers)) {
                    $confirmation_message = '<p style="color: green;">Thank you! Your message has been sent successfully.</p>';
                } else {
                    $confirmation_message = '<p style="color: red;">Oops! Something went wrong, and we couldn’t send your message. Please try again later.</p>';
                }
            }
        }
    }

    // Display the form and confirmation message
    ob_start();
    echo $confirmation_message;
    ?>
    <form method="POST" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
        <?php wp_nonce_field('custom_login_nonce_action', 'custom_login_nonce'); ?>
        <p>
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
        </p>
        <p>
            <label for="company_email">Company Email</label>
            <input type="email" id="company_email" name="company_email" required>
        </p>
        <p>
            <label for="mobile_number">Mobile Number</label>
            <input type="text" id="mobile_number" name="mobile_number" required>
        </p>
        <p>
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="5" required></textarea>
        </p>
        <p>
            <button type="submit" name="custom_login_form_submit">Submit</button>
        </p>
    </form>
    <?php
    return ob_get_clean();
}
