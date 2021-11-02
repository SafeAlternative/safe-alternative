<?php

define('WP_USE_THEMES', false);
include '../../../../../../wp-load.php';

$dir = plugin_dir_path(__FILE__);
include_once($dir . 'courier.class.php');

$parameters = $_POST['awb'];
$trimite_mail = get_option('express_trimite_mail');

if($parameters['retur'] == 'false'){
    unset($parameters['retur_type']);
}

$courier = new SafealternativeExpressClass();
$response = $courier->callMethod("generateAwb", $parameters, 'POST');
$message = json_decode($response['message'], true);

if ($response['status'] == 200) {
    if (!empty($message['error'])) wp_die($message['error']['message']);

    $awb = $message['awb'];

    if ($trimite_mail == 'da') {
        ExpressAWB::send_mails($_GET['order_id'], $awb, $parameters['recipient_email']);
    }
    update_post_meta($_GET['order_id'], 'awb_express', $awb);
    update_post_meta($_GET['order_id'], 'awb_express_status', 'Inregistrat');

    do_action('safealternative_awb_generated', 'Express', $awb);

    $account_status_response = $courier->callMethod("newAccountStatus", [], 'POST');
    $account_status = json_decode($account_status_response['message']);

    if ($account_status->show_message) {
        set_transient('express_account_status', $account_status->message, MONTH_IN_SECONDS);
    } else {
        delete_transient('express_account_status');
    }

    header('Location: ' . safealternative_redirect_url() . 'post.php?post=' . $_GET['order_id'] . '&action=edit');
    exit;
} else {
    header('Location: ' . safealternative_redirect_url() . 'post.php?post=' . $_GET['order_id'] . '&action=edit');
    exit;
}
