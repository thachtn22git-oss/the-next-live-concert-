<?php
// admin.php — Giao diện cũ, thêm login + search cả 2 bảng + tìm theo ticket_type + nút Reset

session_start();
require_once "db.php";
mysqli_set_charset($conn, 'utf8mb4');

/* ========== Helpers ========== */
function esc($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money_vnd($n){ return number_format((float)$n, 0, ',', '.') . ' VND'; }
function col_exists(mysqli $conn, string $table, string $col): bool {
  $res = $conn->query("SHOW COLUMNS FROM `$table` LIKE '".$conn->real_escape_string($col)."'");
  return $res && $res->num_rows > 0;
}

/* ========== Logout ========== */
if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: admin.php");
  exit;
}

/* ========== Login đơn giản ========== */
if (!isset($_SESSION['admin'])) {
  if ($_SERVER['REQUEST_METHOD']==='POST') {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';
    if ($u==='admin' && $p==='12345') {
      $_SESSION['admin'] = true;
      header("Location: admin.php");
      exit;
    } else {
      $error = "Sai tài khoản hoặc mật khẩu!";
    }
  }
  ?>
  <!doctype html>
  <html lang="vi">
  <head>
    <meta charset="utf-8">
    <title>Admin Login</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body class="bg-light d-flex align-items-center" style="min-height:100vh;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-4">
          <h4 class="mb-3 text-center">Admin Login</h4>
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo esc($error); ?></div>
          <?php endif; ?>
          <form method="post" class="border p-3 bg-white rounded">
            <div class="mb-3">
              <label class="form-label">Tài khoản</label>
              <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Mật khẩu</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100">Đăng nhập</button>
          </form>
        </div>
      </div>
    </div>
    <script src="js/bootstrap.min.js"></script>
  </body>
  </html>
  <?php
  exit;
}

/* ========== Tham số giao diện: tab + search + auto limit ========== */
$tab   = $_GET['tab'] ?? 'tickets';
if (!in_array($tab, ['tickets','contacts'], true)) $tab = 'tickets';

$q     = trim($_GET['q'] ?? '');
$limit = 200; // Auto limit 200

$safeQ = $q !== '' ? '%'.$conn->real_escape_string($q).'%' : null;

$tickets = $contacts = [];
$tick_count = $cont_count = 0;

/* ========== Query TICKETS (có tìm theo ticket_type) ========== */
$ticketConds = [];
if ($safeQ) {
  if (col_exists($conn,'tickets','full_name'))   $ticketConds[] = "full_name LIKE '$safeQ'";
  if (col_exists($conn,'tickets','fullname'))    $ticketConds[] = "fullname LIKE '$safeQ'";
  if (col_exists($conn,'tickets','email'))       $ticketConds[] = "email LIKE '$safeQ'";
  if (col_exists($conn,'tickets','phone'))       $ticketConds[] = "phone LIKE '$safeQ'";
  if (col_exists($conn,'tickets','order_id'))    $ticketConds[] = "order_id LIKE '$safeQ'";
  if (col_exists($conn,'tickets','ticket_type')) $ticketConds[] = "ticket_type LIKE '$safeQ'"; // thêm tìm theo loại vé
}
$sqlT = "SELECT SQL_CALC_FOUND_ROWS * FROM tickets";
if ($safeQ && $ticketConds) $sqlT .= " WHERE ".implode(" OR ", $ticketConds);
$sqlT .= " ORDER BY created_at DESC LIMIT $limit";
$rsT = $conn->query($sqlT);
while ($rsT && $row = $rsT->fetch_assoc()) $tickets[] = $row;
$rsCnt = $conn->query("SELECT FOUND_ROWS() AS c"); 
$tick_count = (int)($rsCnt->fetch_assoc()['c'] ?? 0);

/* ========== Query CONTACTS (tự dò cột name/full_name) ========== */
$contactConds = [];
if ($safeQ) {
  if (col_exists($conn,'contacts','name'))       $contactConds[] = "name LIKE '$safeQ'";
  if (col_exists($conn,'contacts','full_name'))  $contactConds[] = "full_name LIKE '$safeQ'";
  if (col_exists($conn,'contacts','email'))      $contactConds[] = "email LIKE '$safeQ'";
  if (col_exists($conn,'contacts','company'))    $contactConds[] = "company LIKE '$safeQ'";
  if (col_exists($conn,'contacts','message'))    $contactConds[] = "message LIKE '$safeQ'";
}
$sqlC = "SELECT SQL_CALC_FOUND_ROWS * FROM contacts";
if ($safeQ && $contactConds) $sqlC .= " WHERE ".implode(" OR ", $contactConds);
$sqlC .= " ORDER BY created_at DESC LIMIT $limit";
$rsC = $conn->query($sqlC);
while ($rsC && $row = $rsC->fetch_assoc()) $contacts[] = $row;
$rsCnt2 = $conn->query("SELECT FOUND_ROWS() AS c"); 
$cont_count = (int)($rsCnt2->fetch_assoc()['c'] ?? 0);
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Admin — The Next Live Concert</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Admin Page</h4>
    <div>
      <a href="index.php" class="btn btn-outline-secondary btn-sm">← Về trang chính</a>
      <a href="admin.php?logout=1" class="btn btn-outline-danger btn-sm">Đăng xuất</a>
    </div>
  </div>

  <!-- Tabs kiểu cũ -->
  <ul class="nav nav-pills mb-3">
    <li class="nav-item">
      <a class="nav-link <?php echo $tab==='tickets'?'active':''; ?>" href="?tab=tickets<?php echo $q!==''?'&q='.urlencode($q):''; ?>">
        Tickets (<?php echo $tick_count; ?>)
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php echo $tab==='contacts'?'active':''; ?>" href="?tab=contacts<?php echo $q!==''?'&q='.urlencode($q):''; ?>">
        Contacts (<?php echo $cont_count; ?>)
      </a>
    </li>
  </ul>

  <!-- Search + Reset -->
  <form class="row g-2 mb-3" method="get" action="admin.php">
    <input type="hidden" name="tab" value="<?php echo esc($tab); ?>">
    <div class="col-md-6">
      <input type="text" class="form-control" name="q"
             placeholder="Search by name / email / phone / order_id / ticket_type / message..."
             value="<?php echo esc($q); ?>">
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary w-100">Search</button>
    </div>
    <div class="col-md-2">
      <a class="btn btn-secondary w-100" href="admin.php?tab=<?php echo esc($tab); ?>">Reset</a>
    </div>
    <div class="col-md-2 text-end">
      <span class="text-muted">Auto limit: <?php echo (int)$limit; ?></span>
    </div>
  </form>

  <?php if ($tab==='tickets'): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th style="white-space:nowrap;">ID</th>
            <th>Order ID</th>
            <th>Full name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Type</th>
            <th class="text-end">Qty</th>
            <th class="text-end">Amount</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($tickets)): ?>
            <tr><td colspan="9" class="text-center text-muted">No data</td></tr>
          <?php else: foreach ($tickets as $r): ?>
            <tr>
              <td><?php echo (int)$r['id']; ?></td>
              <td><code><?php echo esc($r['order_id'] ?? ''); ?></code></td>
              <td><?php echo esc($r['full_name'] ?? $r['fullname'] ?? ''); ?></td>
              <td><a href="mailto:<?php echo esc($r['email'] ?? ''); ?>"><?php echo esc($r['email'] ?? ''); ?></a></td>
              <td><a href="tel:<?php echo esc($r['phone'] ?? ''); ?>"><?php echo esc($r['phone'] ?? ''); ?></a></td>
              <td><?php echo esc($r['ticket_type'] ?? ''); ?></td>
              <td class="text-end"><?php echo (int)($r['quantity'] ?? 0); ?></td>
              <td class="text-end"><?php echo money_vnd($r['amount'] ?? $r['total_price'] ?? 0); ?></td>
              <td><span class="text-muted small"><?php echo esc($r['created_at'] ?? ''); ?></span></td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Company</th>
            <th>Message</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($contacts)): ?>
            <tr><td colspan="6" class="text-center text-muted">No data</td></tr>
          <?php else: foreach ($contacts as $r): ?>
            <tr>
              <td><?php echo (int)$r['id']; ?></td>
              <td><?php echo esc($r['full_name'] ?? $r['name'] ?? ''); ?></td>
              <td><a href="mailto:<?php echo esc($r['email'] ?? ''); ?>"><?php echo esc($r['email'] ?? ''); ?></a></td>
              <td><?php echo esc($r['company'] ?? ''); ?></td>
              <td style="max-width: 480px;"><div class="text-truncate"><?php echo esc($r['message'] ?? ''); ?></div></td>
              <td><span class="text-muted small"><?php echo esc($r['created_at'] ?? ''); ?></span></td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

</div>

<script src="js/bootstrap.min.js"></script>
</body>
</html>
