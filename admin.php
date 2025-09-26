<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}
?>


<?php
// admin.php — Dashboard xem DB (Tickets & Contacts) với Bootstrap 5
// Đặt cạnh db.php. Mặc định hiển thị 200 bản ghi mới nhất mỗi bảng.
// (Tuỳ chọn) Bảo vệ bằng mật khẩu đơn giản:
// $ADMIN_PASS = 'changeme123'; if(($_GET['pass']??'') !== $ADMIN_PASS){ die('Unauthorized'); }

require __DIR__ . '/db.php';
mysqli_set_charset($conn, 'utf8mb4');

// --- Helpers ---
function esc($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money_vnd($n){ return number_format((float)$n, 0, ',', '.') . ' VND'; }

// --- Tabs & search ---
$tab = $_GET['tab'] ?? 'tickets';
if (!in_array($tab, ['tickets','contacts'], true)) $tab = 'tickets';

$q   = trim($_GET['q'] ?? '');                 // ô tìm nhanh
$limit = 200;                                  // số dòng hiển thị
$tickets = $contacts = [];
$tick_count = $cont_count = 0;

// --- Export CSV ---
if (isset($_GET['export']) && in_array($_GET['export'], ['tickets','contacts'], true)) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="'.$_GET['export'].'-'.date('Ymd_His').'.csv"');
    $out = fopen('php://output', 'w');

    if ($_GET['export'] === 'tickets') {
        fputcsv($out, ['id','order_id','full_name','email','phone','ticket_type','quantity','amount','created_at']);
        $sql = "SELECT * FROM tickets";
        if ($q !== '') {
            $safe = '%'.$conn->real_escape_string($q).'%';
            $sql .= " WHERE order_id LIKE '$safe' OR email LIKE '$safe' OR full_name LIKE '$safe' OR phone LIKE '$safe'";
        }
        $sql .= " ORDER BY created_at DESC LIMIT 10000";
        $rs = $conn->query($sql);
        while ($r = $rs->fetch_assoc()) fputcsv($out, $r);
    } else {
        fputcsv($out, ['id','name','email','company','message','created_at']);
        $sql = "SELECT * FROM contacts";
        if ($q !== '') {
            $safe = '%'.$conn->real_escape_string($q).'%';
            $sql .= " WHERE email LIKE '$safe' OR name LIKE '$safe' OR company LIKE '$safe' OR message LIKE '$safe'";
        }
        $sql .= " ORDER BY created_at DESC LIMIT 10000";
        $rs = $conn->query($sql);
        while ($r = $rs->fetch_assoc()) fputcsv($out, $r);
    }
    fclose($out);
    exit;
}

// --- Query tickets ---
$safeQ = $q !== '' ? '%'.$conn->real_escape_string($q).'%' : null;

$sqlT = "SELECT SQL_CALC_FOUND_ROWS * FROM tickets";
if ($safeQ) $sqlT .= " WHERE order_id LIKE '$safeQ' OR email LIKE '$safeQ' OR full_name LIKE '$safeQ' OR phone LIKE '$safeQ'";
$sqlT .= " ORDER BY created_at DESC LIMIT $limit";
$rsT = $conn->query($sqlT);
while($rsT && $row = $rsT->fetch_assoc()){ $tickets[] = $row; }
$rsCnt = $conn->query("SELECT FOUND_ROWS() AS c"); $tick_count = (int)($rsCnt->fetch_assoc()['c'] ?? 0);

// --- Query contacts ---
$sqlC = "SELECT SQL_CALC_FOUND_ROWS * FROM contacts";
if ($safeQ) $sqlC .= " WHERE email LIKE '$safeQ' OR name LIKE '$safeQ' OR company LIKE '$safeQ' OR message LIKE '$safeQ'";
$sqlC .= " ORDER BY created_at DESC LIMIT $limit";
$rsC = $conn->query($sqlC);
while($rsC && $row = $rsC->fetch_assoc()){ $contacts[] = $row; }
$rsCnt2 = $conn->query("SELECT FOUND_ROWS() AS c"); $cont_count = (int)($rsCnt2->fetch_assoc()['c'] ?? 0);

?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard - The Next Live Concert</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Dùng Bootstrap của site -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f6f8fb; }
    .container-narrow { max-width: 1200px; }
    .card { border-radius: 16px; box-shadow: 0 6px 16px rgba(0,0,0,0.06); }
    .table th, .table td { vertical-align: middle; }
    .amount-badge { font-weight:600; }
    .sticky-top-lite { position: sticky; top: 0; z-index: 100; background: #f6f8fb; padding-top: 8px; }
    .search-wrap input { border-radius: 999px; }
  </style>
</head>
<body>
  <div class="container container-narrow py-4">

    <div class="d-flex align-items-center justify-content-between mb-3 sticky-top-lite">
      <h3 class="mb-0">Admin Dashboard</h3>
      <a href="logout.php" class="btn btn-danger" style="margin-left: 700px;">Logout</a>
      <a class="btn btn-secondary" href="index.php">← Back to site</a>
    </div>

    <div class="card mb-4">
      <div class="card-body pb-2">
        <form class="row g-2 align-items-center" method="get" action="admin.php">
          <div class="col-md-auto">
            <ul class="nav nav-pills">
              <li class="nav-item"><a class="nav-link <?php echo $tab==='tickets'?'active':''; ?>" href="?tab=tickets<?php echo $q!==''?'&q='.urlencode($q):''; ?>">Tickets</a></li>
              <li class="nav-item"><a class="nav-link <?php echo $tab==='contacts'?'active':''; ?>" href="?tab=contacts<?php echo $q!==''?'&q='.urlencode($q):''; ?>">Contacts</a></li>
            </ul>
          </div>
          <div class="col-md search-wrap">
            <input type="text" class="form-control" placeholder="Search by email, name, order id, phone, message..." name="q" value="<?php echo esc($q); ?>">
            <input type="hidden" name="tab" value="<?php echo esc($tab); ?>">
          </div>
          <div class="col-md-auto">
            <button class="btn btn-primary">Search</button>
            <?php if ($tab==='tickets'): ?>
              <a class="btn btn-outline-primary" href="?export=tickets<?php echo $q!==''?'&q='.urlencode($q):''; ?>">Export CSV</a>
            <?php else: ?>
              <a class="btn btn-outline-primary" href="?export=contacts<?php echo $q!==''?'&q='.urlencode($q):''; ?>">Export CSV</a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    <?php if ($tab === 'tickets'): ?>

      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div><strong>Tickets</strong> — showing <?php echo count($tickets); ?> / <?php echo $tick_count; ?> latest records</div>
          <span class="text-muted">Auto limit: <?php echo $limit; ?></span>
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
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
                <tr><td colspan="9" class="text-center py-4 text-muted">No data</td></tr>
              <?php else: ?>
                <?php foreach($tickets as $r): ?>
                  <tr>
                    <td><?php echo (int)$r['id']; ?></td>
                    <td><code><?php echo esc($r['order_id']); ?></code></td>
                    <td><?php echo esc($r['full_name']); ?></td>
                    <td><a href="mailto:<?php echo esc($r['email']); ?>"><?php echo esc($r['email']); ?></a></td>
                    <td><a href="tel:<?php echo esc($r['phone']); ?>"><?php echo esc($r['phone']); ?></a></td>
                    <td>
                      <?php if ($r['ticket_type']==='NECH_AB'): ?>
                        <span class="badge bg-info">NECH_A_B</span>
                      <?php else: ?>
                        <span class="badge bg-secondary">GA</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-end"><?php echo (int)$r['quantity']; ?></td>
                    <td class="text-end"><span class="amount-badge"><?php echo money_vnd($r['amount']); ?></span></td>
                    <td><span class="text-muted small"><?php echo esc($r['created_at']); ?></span></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    <?php else: ?>

      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div><strong>Contacts</strong> — showing <?php echo count($contacts); ?> / <?php echo $cont_count; ?> latest records</div>
          <span class="text-muted">Auto limit: <?php echo $limit; ?></span>
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Company</th>
                <th>Message</th>
                <th>Created</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($contacts)): ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">No data</td></tr>
              <?php else: ?>
                <?php foreach($contacts as $r): ?>
                  <tr>
                    <td><?php echo (int)$r['id']; ?></td>
                    <td><?php echo esc($r['name'] ?? $r['full_name'] ?? ''); ?></td>
                    <td><a href="mailto:<?php echo esc($r['email']); ?>"><?php echo esc($r['email']); ?></a></td>
                    <td><?php echo esc($r['company']); ?></td>
                    <td style="max-width:420px;">
                      <div class="text-truncate"><?php echo esc($r['message']); ?></div>
                    </td>
                    <td><span class="text-muted small"><?php echo esc($r['created_at']); ?></span></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      

    <?php endif; ?>

    <div class="text-center text-muted small my-4">
      © <?php echo date('Y'); ?> The Next Live Concert — Admin view
    </div>
  </div>

  <!-- Bootstrap JS (dùng file có sẵn của bạn) -->
  <script src="js/bootstrap.min.js"></script>
</body>
</html>
