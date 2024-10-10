<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $owner_name = strip_tags(trim($_POST["owner-name"]));
    $rental_type = strip_tags(trim($_POST["rental-type"]));
    $num_airbnbs = intval($_POST["num-airbnbs"]);
    $building_address = strip_tags(trim($_POST["building-address"]));
    $airbnb_unit = strip_tags(trim($_POST["airbnb-unit"])); // Collect the new Airbnb unit name
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = strip_tags(trim($_POST["phone"]));
    $promo_code = strip_tags(trim($_POST["promo-code"]));
    $terms_accepted = isset($_POST["terms"]) ? "Yes" : "No";
    $updates_accepted = isset($_POST["updates"]) ? "Yes" : "No";
    $privacy_accepted = isset($_POST["privacy"]) ? "Yes" : "No";

    // Validate form fields
    if (empty($owner_name) || empty($building_address) || empty($email) || empty($phone) || empty($airbnb_unit) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Please complete the form and try again."]);
        exit;
    }

    // Set email parameters
    $to = "sales@inroomdining.co.ke";
    $subject = "New Partner Form Submission from $owner_name";
    $email_content = "Owner/Manager/Host Name: $owner_name\n";
    $email_content .= "Type of Airbnb Rental: $rental_type\n";
    $email_content .= "Number of Airbnb(s): $num_airbnbs\n";
    $email_content .= "Building Address: $building_address\n";
    $email_content .= "Name of the Airbnb Unit: $airbnb_unit\n"; // Add new field to email content
    $email_content .= "Email: $email\n";
    $email_content .= "Phone: $phone\n";
    $email_content .= "Promo Code: $promo_code\n";
    $email_content .= "Terms Accepted: $terms_accepted\n";
    $email_content .= "Updates Accepted: $updates_accepted\n";
    $email_content .= "Privacy Policy Accepted: $privacy_accepted\n";

    $email_headers = "From: $owner_name <$email>";

    // Send the email
    if (mail($to, $subject, $email_content, $email_headers)) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Thank you for your submission!"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Oops! Something went wrong, and we couldn't send your form."]);
    }
} else {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "There was a problem with your submission, please try again."]);
}
?>
