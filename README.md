# 🎶 The Next Live Concert

Website giới thiệu sự kiện & mua vé (đồ án cuối kỳ).  
Stack: **PHP 8 (XAMPP) + MySQL + Bootstrap 5 + jQuery**.

---

## 🚀 Tính năng
- Xem thông tin sự kiện, nghệ sĩ, lịch diễn, giá vé.
- **Contact Form** → lưu lời nhắn vào DB (`contacts`).
- **Buy Ticket** → lưu đơn hàng vào DB (`tickets`).
- Tính tổng tiền động (giảm **5%** cho 5–9 vé, **10%** cho ≥10 vé).
- (Tuỳ chọn) Mở trang thanh toán MoMo Sandbox để quét QR.
- Responsive UI.

---

## 🗂 Cấu trúc
the-next-live-concert/
├─ css/
├─ js/ # totalprice.js, custom.js, ...
├─ images/
├─ video/ # (khuyến nghị ignore do >100MB)
├─ index.php
├─ ticket.php
├─ contact_process.php
├─ save_ticket.php # lưu vé trực tiếp vào DB (nếu dùng)
├─ purchase_and_save.php # lưu DB + tạo payUrl MoMo
├─ return_momo.php # nhận redirect từ MoMo (success/cancel)
├─ ipn_momo.php # (tuỳ chọn) nhận IPN từ MoMo
├─ db.php # thông tin kết nối DB
└─ README.md

---

## 🗄 Database

### Tạo DB & bảng
```sql
CREATE DATABASE IF NOT EXISTS concert_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE concert_db;

CREATE TABLE IF NOT EXISTS contacts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  company VARCHAR(100),
  message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_contacts_email (email),
  INDEX idx_contacts_created_at (created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tickets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id VARCHAR(64) NOT NULL,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  ticket_type ENUM('GA','NECH_AB') NOT NULL,
  quantity INT NOT NULL,
  amount BIGINT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_tickets_order_id (order_id),
  INDEX idx_tickets_email (email),
  INDEX idx_tickets_created_at (created_at)
) ENGINE=InnoDB;
```
Kết nối DB (db.php)
<?php
$host = "localhost";
$user = "root";      // XAMPP mặc định
$pass = "";
$dbname = "concert_db";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("DB connect failed: ".$conn->connect_error);

⚙️ Chạy dự án

Copy project vào: C:\xampp\htdocs\the-next-live-concert\

Mở XAMPP: Start Apache + MySQL

Tạo DB & bảng (phpMyAdmin hoặc MySQL CLI)

Vào trình duyệt: http://localhost/the-next-live-concert/index.php

💳 (Tuỳ chọn) Thanh toán MoMo Sandbox

File gợi ý: purchase_and_save.php (lưu DB trước, rồi gọi API create để lấy payUrl).

Dùng sandbox keys public:

partnerCode=MOMO, accessKey=F8BBA842ECF85, secretKey=K951B6PE1waDMi640xX08PD3vg6EkVlz

Redirect URL: return_momo.php (nếu resultCode != 0 → tự quay lại ticket.php).

Lưu ý: 1005 thường do link/QR hết hạn. Mục đích đồ án là hiển thị trang MoMo; lưu DB vẫn thành công trước đó.

🧮 Giá vé & giảm giá (đồng bộ Client/Server)

GA (Standing): 735.000 VND

NẾCH A–B (Seating): 1.450.000 VND

Combo: 5–9 vé: −5%; ≥10 vé: −10%

JS tính tổng: js/totalprice.js

Server-side tính tổng lại: save_ticket.php hoặc purchase_and_save.php

🧰 Troubleshooting

Push bị chặn do file >100MB → dùng .gitignore để bỏ video/ và *.mp4 hoặc dùng Git LFS.

MoMo 1005 → do payUrl/QR hết hạn; mục đích demo là hiển thị trang MoMo, đơn đã lưu vào DB trước đó.

Không tự cập nhật tổng tiền → kiểm tra js/totalprice.js, id/name input và hard refresh (Ctrl+F5).

Không lưu DB → xem error_log, bật display_errors, kiểm tra db.php và quyền MySQL.

👨‍💻 Tác giả

Tên SV: Trần Ngọc Thạch • MSSV: 22IT261 • Trường: VKU


---

### Lệnh cập nhật & đẩy README lên GitHub

```bash
cd C:\xampp\htdocs\the-next-live-concert

git pull --rebase origin main   # kéo thay đổi mới nhất (nếu có)
git add README.md
git commit -m "Update README: add diagrams, setup, troubleshooting"
git push origin main

git fetch origin
git pull --rebase origin main
git push origin main

Nếu gặp lỗi non-fast-forward, làm:
git fetch origin
git pull --rebase origin main
git push origin main
