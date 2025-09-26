<?php
// purchase_and_save.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Trả JSON + chống cache
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

require __DIR__ . '/db.php';

// ==== 1) NHẬN DỮ LIỆU TỪ FORM ====
$name   = $_POST['ticket-form-name']   ?? '';
$email  = $_POST['ticket-form-email']  ?? '';
$phone  = $_POST['ticket-form-phone']  ?? '';
$qty    = (int)($_POST['ticket-form-number'] ?? 0);
$type   = $_POST['ticket_type'] ?? 'GA'; // 'GA' | 'NECH_AB'

// Validate cơ bản
if ($name==='' || $email==='' || $phone==='' || $qty<=0 || !in_array($type, ['GA','NECH_AB'], true)) {
  echo json_encode(["status"=>"fail","message"=>"Vui lòng nhập đầy đủ thông tin hợp lệ."]);
  exit;
}

// ==== 2) TÍNH TIỀN (SERVER-SIDE) ====
$PRICE_GA   = 735000;
$PRICE_NECH = 1450000;

$unit   = ($type === 'NECH_AB') ? $PRICE_NECH : $PRICE_GA;
$amount = $unit * $qty;
if ($qty >= 5 && $qty <= 9) $amount = (int)round($amount * 0.95);
if ($qty >= 10)            $amount = (int)round($amount * 0.90);

// ==== 3) TẠO order_id VÀ LƯU VÀO DB NGAY ====
function uid($p){ return $p . strtoupper(bin2hex(random_bytes(4))) . time(); }
$order_id = uid('ORDER_');

$stmt = $conn->prepare("INSERT INTO tickets (order_id, full_name, email, phone, ticket_type, quantity, amount)
                        VALUES (?,?,?,?,?,?,?)");
$stmt->bind_param("sssssid", $order_id, $name, $email, $phone, $type, $qty, $amount);

if (!$stmt->execute()) {
  echo json_encode(["status"=>"fail","message"=>"DB error: ".$conn->error]);
  exit;
}
$stmt->close();
// -> TỚI ĐÂY ĐÃ LƯU DB THÀNH CÔNG

// ==== 4) GỌI MOMO TẠO payUrl (SANDBOX) ====
$partnerCode = "MOMO";
$accessKey   = "F8BBA842ECF85";
$secretKey   = "K951B6PE1waDMi640xX08PD3vg6EkVlz";
$requestId   = uid('REQ_');

$orderInfo   = "The Next Live Concert - ".$order_id;
$redirectUrl = "http://localhost/the-next-live-concert/return_momo.php"; // có thể giữ nguyên
$ipnUrl      = "http://localhost/the-next-live-concert/ipn_momo.php";    // nếu chưa dùng IPN thật cũng OK
$extraObj    = [
  "order_id"   => $order_id,
  "full_name"  => $name,
  "email"      => $email,
  "phone"      => $phone,
  "ticket_type"=> $type,
  "quantity"   => $qty,
  "amount"     => $amount
];
$extraData   = base64_encode(json_encode($extraObj, JSON_UNESCAPED_UNICODE));

// Tạo signature theo tài liệu MoMo v2
$rawHash = "accessKey=" . $accessKey .
           "&amount=" . $amount .
           "&extraData=" . $extraData .
           "&ipnUrl=" . $ipnUrl .
           "&orderId=" . $order_id .
           "&orderInfo=" . $orderInfo .
           "&partnerCode=" . $partnerCode .
           "&redirectUrl=" . $redirectUrl .
           "&requestId=" . $requestId .
           "&requestType=captureWallet";

$signature = hash_hmac("sha256", $rawHash, $secretKey);

// Payload
$payload = [
  'partnerCode' => $partnerCode,
  'partnerName' => "Test",
  'storeId'     => "MomoTestStore",
  'requestId'   => $requestId,
  'amount'      => (string)$amount, // MoMo nhận string số
  'orderId'     => $order_id,
  'orderInfo'   => $orderInfo,
  'redirectUrl' => $redirectUrl,
  'ipnUrl'      => $ipnUrl,
  'lang'        => 'vi',
  'extraData'   => $extraData,
  'requestType' => 'captureWallet',
  'signature'   => $signature
];

// Gọi MoMo
$ch = curl_init('https://test-payment.momo.vn/v2/gateway/api/create');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$result = curl_exec($ch);
if ($result === false) {
  // MoMo lỗi mạng: vẫn báo đã lưu thành công, chỉ không có payUrl
  echo json_encode([
    "status"      => "saved_only",
    "order_id"    => $order_id,
    "amount"      => $amount,
    "amount_text" => number_format($amount, 0, ',', '.') . " VND",
    "message"     => "Đã lưu đơn vào DB nhưng không tạo được payUrl (network)."
  ]);
  exit;
}
curl_close($ch);

$res = json_decode($result, true);

// ==== 5) TRẢ VỀ CHO FRONTEND ====
if (is_array($res) && isset($res['resultCode']) && (int)$res['resultCode'] === 0 && !empty($res['payUrl'])) {
  echo json_encode([
    "status"      => "ok",               // đã lưu DB + có payUrl
    "order_id"    => $order_id,
    "ticket_type" => $type,
    "quantity"    => $qty,
    "amount"      => $amount,
    "amount_text" => number_format($amount, 0, ',', '.') . " VND",
    "payUrl"      => $res['payUrl'],
    "deeplink"    => $res['deeplink'] ?? ""
  ]);
} else {
  // Tạo payUrl fail: vẫn coi như đã lưu DB (saved_only)
  echo json_encode([
    "status"      => "saved_only",
    "order_id"    => $order_id,
    "ticket_type" => $type,
    "quantity"    => $qty,
    "amount"      => $amount,
    "amount_text" => number_format($amount, 0, ',', '.') . " VND",
    "message"     => $res['message'] ?? "Đã lưu đơn vào DB, tạo payUrl thất bại.",
    "debug"       => $res
  ]);
}
