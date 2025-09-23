<?php
$orderId    = $_GET['orderId']    ?? '';
$resultCode = $_GET['resultCode'] ?? '';
?>
<!doctype html>
<html lang="vi">
<head><meta charset="utf-8"><title>Payment status</title>
<link rel="stylesheet" href="css/bootstrap.min.css"></head>
<body class="p-4">
  <div class="container">
    <?php if ($resultCode === '0'): ?>
      <div class="alert alert-success">✅ Thanh toán thành công!</div>
      <a class="btn btn-primary" href="index.php">Về trang chủ</a>
    <?php else: ?>
      <div class="alert alert-danger">
        ❌ Thanh toán thất bại (mã: <?= htmlspecialchars($resultCode) ?>). Có thể link/QR đã hết hạn.
      </div>
      <a class="btn btn-primary" href="ticket.php">Tạo lại thanh toán</a>
    <?php endif; ?>
  </div>
</body>
</html>
