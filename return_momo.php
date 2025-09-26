<?php
// return_momo.php
$resultCode = $_GET['resultCode'] ?? '';
$orderId    = $_GET['orderId'] ?? '';

if ($resultCode !== '0') {
  // Hủy/thoát/không thành công -> quay lại ticket.php
  header("Location: /the-next-live-concert/ticket.php?cancel=1");
  exit;
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Payment Result</title>
  <meta http-equiv="refresh" content="10;url=/the-next-live-concert/ticket.php">
  <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="p-4">
  <div class="container">
    <div class="alert alert-success">
      ✅ Thanh toán thành công cho đơn <strong><?php echo htmlspecialchars($orderId); ?></strong>.
    </div>
    <a class="btn btn-primary" href="ticket.php">Về trang mua vé</a>
  </div>
</body>
</html>
