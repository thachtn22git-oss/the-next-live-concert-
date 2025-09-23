<?php
// purchase_process.php

// Headers chống cache
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Nhận dữ liệu từ form
$fullname = $_POST['fullname'] ?? '';
$email    = $_POST['email'] ?? '';
$phone    = $_POST['phone'] ?? '';
$quantity = (int)($_POST['quantity'] ?? 0);
$ticketType = $_POST['ticket_type'] ?? '';

// Xác định giá vé (tạm ví dụ)
$pricePerTicket = 0;
if ($ticketType === 'GA') $pricePerTicket = 735000;      
if ($ticketType === 'NEA') $pricePerTicket = 1450000;  // vé thật

$amount = $pricePerTicket * $quantity;

// Áp dụng combo
if ($quantity >= 5 && $quantity < 10) {
    $amount *= 0.95; // giảm 5%
} else if ($quantity >= 10) {
    $amount *= 0.90; // giảm 10%
}

// Nếu muốn test tối thiểu, có thể ép amount = 10000
// $amount = 10000;

// Hàm tạo ID duy nhất
function uid($prefix){ return $prefix . strtoupper(bin2hex(random_bytes(6))) . time(); }
$orderId   = uid('ORDER_');
$requestId = uid('REQ_');

// Thông tin MoMo SANDBOX
$partnerCode = "MOMO";
$accessKey   = "F8BBA842ECF85";
$secretKey   = "K951B6PE1waDMi640xX08PD3vg6EkVlz";

$orderInfo = "The Next Live Concert";
$redirectUrl = "http://localhost/templatemo_583_festava_live/return_momo.php";
$ipnUrl     = "http://localhost/templatemo_583_festava_live/ipn_momo.php";
$extraData  = "";

// Tạo rawHash
$rawHash = "accessKey=" . $accessKey .
           "&amount=" . $amount .
           "&extraData=" . $extraData .
           "&ipnUrl=" . $ipnUrl .
           "&orderId=" . $orderId .
           "&orderInfo=" . $orderInfo .
           "&partnerCode=" . $partnerCode .
           "&redirectUrl=" . $redirectUrl .
           "&requestId=" . $requestId .
           "&requestType=captureWallet";

$signature = hash_hmac("sha256", $rawHash, $secretKey);

$data = [
    'partnerCode' => $partnerCode,
    'partnerName' => "Test",
    'storeId'     => "MomoTestStore",
    'requestId'   => $requestId,
    'amount'      => (string)$amount,
    'orderId'     => $orderId,
    'orderInfo'   => $orderInfo,
    'redirectUrl' => $redirectUrl,
    'ipnUrl'      => $ipnUrl,
    'lang'        => 'vi',
    'extraData'   => $extraData,
    'requestType' => 'captureWallet',
    'signature'   => $signature
];

// Gửi request đến MoMo
$ch = curl_init('https://test-payment.momo.vn/v2/gateway/api/create');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$result = curl_exec($ch);
curl_close($ch);

$res = json_decode($result, true);

if (isset($res['resultCode']) && (int)$res['resultCode'] === 0) {
    echo json_encode([
        "status"   => "ok",
        "payUrl"   => $res['payUrl'] ?? "",
        "deeplink" => $res['deeplink'] ?? ""
    ]);
} else {
    echo json_encode([
        "status"  => "fail",
        "message" => $res['message'] ?? "Create payment failed",
        "debug"   => $res
    ]);
}
