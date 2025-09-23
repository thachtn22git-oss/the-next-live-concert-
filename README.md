# 🎶 The Next Live Concert Website  

Một đồ án cuối kỳ về website bán vé và giới thiệu sự kiện âm nhạc.  
Dự án này sử dụng **PHP + MySQL + Bootstrap** để xây dựng trang web hiển thị thông tin sự kiện, nghệ sĩ, lịch diễn và tính năng mua vé trực tuyến.  

---

## 🚀 Tính năng chính
- Trang chủ hiển thị thông tin sự kiện (thời gian, địa điểm, video nền).  
- Giới thiệu nghệ sĩ, lịch diễn, giá vé.  
- Form **Contact**: Người dùng có thể gửi lời nhắn → lưu vào database.  
- Form **Buy Ticket**: Người dùng nhập thông tin mua vé → lưu vào database.  
- Tính toán tổng tiền động (bao gồm giảm giá combo 5 vé, 10 vé).  
- Responsive UI (hiển thị đẹp trên PC & mobile).  

---

## 🛠️ Công nghệ sử dụng
- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript.  
- **Backend**: PHP 8 (chạy qua XAMPP).  
- **Database**: MySQL.  

---

## 📂 Cấu trúc thư mục
templatemo_583_festava_live/
│
├── css/ # File CSS (Bootstrap + custom styles)
├── js/ # File JS (totalprice.js, custom.js, ...)
├── images/ # Hình ảnh (logo, nghệ sĩ, ...)
├── video/ # Video nền (khuyến nghị bỏ khỏi repo do >100MB)
│
├── index.php # Trang chủ
├── ticket.php # Trang mua vé
├── contact_process.php # Xử lý form liên hệ
├── purchase.php # Xử lý mua vé
├── db.php # Kết nối database
├── README.md # File mô tả dự án
└── ...

---

## 🗄️ Cấu trúc Database

### 1. Bảng `contacts`
```sql
CREATE TABLE contacts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  company VARCHAR(100),
  message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
2. Bảng tickets
CREATE TABLE tickets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  ticket_type VARCHAR(50) NOT NULL,
  quantity INT NOT NULL,
  total_price DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
⚡ Cách chạy dự án

Clone repo về máy:

git clone https://github.com/<your-username>/the-next-live-concert-.git


Copy toàn bộ project vào thư mục htdocs của XAMPP:

C:/xampp/htdocs/templatemo_583_festava_live/


Khởi động Apache & MySQL trong XAMPP Control Panel.

Tạo database mới (vd: concert_db) trong phpMyAdmin và import bảng như mô tả ở trên.

Chỉnh file db.php:

<?php
$host = "localhost";
$user = "root";      // mặc định XAMPP
$pass = "";
$dbname = "concert_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>


Mở trình duyệt và chạy:

http://localhost/templatemo_583_festava_live/