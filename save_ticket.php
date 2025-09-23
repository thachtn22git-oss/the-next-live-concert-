<?php
// save_ticket.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/db.php';

// Lấy dữ liệu từ form (đúng với ticket.php ở trên)
$name   = $_POST['ticket-form-name']   ?? '';
$email  = $_POST['ticket-form-email']  ?? '';
$phone  = $_POST['ticket-form-phone']  ?? '';
$qty    = (int)($_POST['ticket-form-number'] ?? 0);
$type   = $_POST['ticket_type'] ?? 'GA'; // 'GA' | 'NECH_AB'

// Validate
if ($name==='' || $email==='' || $phone==='' || $qty<=0) {
  echo json_encode(["status"=>"fail","message"=>"Vui lòng nhập đầy đủ thông tin."]);
  exit;
}

// CHỐT GIÁ SERVER-SIDE (đồng bộ với client)
$PRICE_GA   = 735000;   // GA
$PRICE_NECH = 1450000;  // Nếch A - B

// Ràng buộc type hợp lệ
if (!in_array($type, ['GA','NECH_AB'], true)) $type = 'GA';

$unit   = ($type === 'NECH_AB') ? $PRICE_NECH : $PRICE_GA;
$amount = $unit * $qty;

// Combo
if ($qty >= 5 && $qty <= 9) $amount = (int)round($amount * 0.95);
if ($qty >= 10)            $amount = (int)round($amount * 0.90);

// Tạo mã đơn
function uid($prefix){ return $prefix . strtoupper(bin2hex(random_bytes(4))) . time(); }
$order_id = uid('ORDER_');

// Lưu DB
$stmt = $conn->prepare("INSERT INTO tickets (order_id, full_name, email, phone, ticket_type, quantity, amount)
                        VALUES (?,?,?,?,?,?,?)");
$stmt->bind_param("sssssid", $order_id, $name, $email, $phone, $type, $qty, $amount);

if ($stmt->execute()) {
  echo json_encode([
    "status"      => "ok",
    "order_id"    => $order_id,
    "ticket_type" => $type,
    "quantity"    => $qty,
    "amount"      => $amount,                                   // số nguyên
    "amount_text" => number_format($amount, 0, ',', '.') . " VND" // chuỗi hiển thị
  ]);
} else {
  echo json_encode([
    "status"  => "fail",
    "message" => "DB error: ".$conn->error
  ]);
}

$stmt->close();
$conn->close();
