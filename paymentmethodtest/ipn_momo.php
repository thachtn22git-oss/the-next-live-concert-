<?php
// ipn_momo.php
require __DIR__.'/db.php';

// Lấy JSON từ MoMo (server -> server)
$input = file_get_contents("php://input");
$data  = json_decode($input, true);

// SANDBOX key
$accessKey = "F8BBA842ECF85";
$secretKey = "K951B6PE1waDMi640xX08PD3vg6EkVlz";

// Build raw hash để verify signature
$required = ['amount','extraData','message','orderId','orderInfo','orderType','partnerCode','payType','requestId','responseTime','resultCode','transId','signature'];
foreach ($required as $k) { if (!isset($data[$k])) { http_response_code(400); echo "bad request"; exit; } }

$rawHash = "accessKey=$accessKey&amount={$data['amount']}&extraData={$data['extraData']}&message={$data['message']}&orderId={$data['orderId']}&orderInfo={$data['orderInfo']}&orderType={$data['orderType']}&partnerCode={$data['partnerCode']}&payType={$data['payType']}&requestId={$data['requestId']}&responseTime={$data['responseTime']}&resultCode={$data['resultCode']}&transId={$data['transId']}";
$signature = hash_hmac("sha256", $rawHash, $secretKey);

if ($signature !== $data['signature']) {
  http_response_code(400);
  echo "signature not match";
  exit;
}

if ((int)$data['resultCode'] === 0) {
  // Giải extraData để lấy thông tin khách
  $extra = json_decode(base64_decode($data['extraData']), true);

  $order_id   = $data['orderId'];
  $full_name  = $extra['full_name'] ?? '';
  $email      = $extra['email'] ?? '';
  $phone      = $extra['phone'] ?? '';
  $ticketType = $extra['ticket_type'] ?? 'GA';
  $quantity   = (int)($extra['quantity'] ?? 1);
  $amount     = (int)($extra['amount'] ?? (int)$data['amount']);
  $transId    = $data['transId'];

  // Tạo bảng nếu bạn chưa có:
  // CREATE TABLE tickets (
  //   id INT AUTO_INCREMENT PRIMARY KEY,
  //   order_id VARCHAR(64) NOT NULL UNIQUE,
  //   full_name VARCHAR(100) NOT NULL,
  //   email VARCHAR(100) NOT NULL,
  //   phone VARCHAR(30) NOT NULL,
  //   ticket_type ENUM('GA','NECH_AB') NOT NULL,
  //   quantity INT NOT NULL,
  //   amount BIGINT NOT NULL,
  //   gateway VARCHAR(20) NOT NULL,
  //   momo_trans_id BIGINT NULL,
  //   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  // );

  // Tránh chèn trùng (idempotent)
  $check = $conn->prepare("SELECT id FROM tickets WHERE order_id=?");
  $check->bind_param("s", $order_id);
  $check->execute();
  $check->store_result();

  if ($check->num_rows === 0) {
    $check->close();
    $ins = $conn->prepare("INSERT INTO tickets (order_id, full_name, email, phone, ticket_type, quantity, amount, gateway, momo_trans_id)
                           VALUES (?,?,?,?,?,?,?,?,?)");
    $gateway = 'MoMo';
    $ins->bind_param("sssssiisi", $order_id, $full_name, $email, $phone, $ticketType, $quantity, $amount, $gateway, $transId);
    $ins->execute();
    $ins->close();
  } else {
    $check->close();
  }

  echo "success"; // MoMo cần response "success"
  exit;
}

echo "fail";
