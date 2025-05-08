<?php
/*
Plugin Name: ERPNext Stock Request Form
Description: A form to submit stock requests to ERPNext via API
Version: 1.0
Author: Karani Geofrey
*/

add_shortcode('erpnext_stock_form', 'erpnext_stock_form_handler');

function erpnext_stock_form_handler() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['erp_form_submitted'])) {
        $item_code = sanitize_text_field($_POST['item_code']);
        $qty = floatval($_POST['qty']);
        $user = sanitize_text_field($_POST['user']);

        $payload = array(
            'user' => $user,
            'items' => array(
                array(
                    'item_code' => $item_code,
                    'qty' => $qty
                )
            )
        );

        $response = wp_remote_post('https://965e-105-163-2-90.ngrok-free.app/api/method/bootcamp25.services.wp_stock_request.create_stock_request', array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'token e7a205fa99880fb:f25a11907bbb816'
            ),
            'body' => json_encode($payload),
        ));

        if (is_wp_error($response)) {
            echo '<p style="color:red;"> Error: Failed to send request.</p>';
        } else {
            echo '<p style="color:green;"> Stock Request submitted successfully.</p>';
        }
    }

    ob_start();
    ?>
    <style>
        .erpnext-stock-form {
            max-width: 400px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
        }

        .erpnext-stock-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .erpnext-stock-form input[type="text"],
        .erpnext-stock-form input[type="email"],
        .erpnext-stock-form input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .erpnext-stock-form input[type="submit"] {
            background-color: #0073aa;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .erpnext-stock-form input[type="submit"]:hover {
            background-color: #005f8d;
        }
    </style>

    <form method="post" class="erpnext-stock-form">
        <label>Item Code:</label>
        <input type="text" name="item_code" required>

        <label>Quantity:</label>
        <input type="number" name="qty" step="0.01" required>

        <label>User Email:</label>
        <input type="email" name="user" required>

        <input type="hidden" name="erp_form_submitted" value="1">
        <input type="submit" value="Submit Request">
    </form>
    <?php
    return ob_get_clean();
}
