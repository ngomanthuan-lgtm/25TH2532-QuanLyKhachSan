<?php
require_once 'db.php';

$id = $_GET['id'] ?? 0;

$sql = "SELECT dp.*, p.ten_phong, p.loai_phong, p.gia_phong, kh.ho_ten, kh.so_dien_thoai, kh.cccd
        FROM dat_phong dp 
        JOIN phong p ON dp.phong_id = p.id 
        JOIN khach_hang kh ON dp.khach_hang_id = kh.id 
        WHERE dp.id = '$id'";

$res = $conn->query($sql);
$data = $res->fetch_assoc();

if (!$data) {
    die("Không tìm thấy dữ liệu đặt phòng!");
}

// Tính số ngày ở
$checkin = new DateTime($data['ngay_checkin']);
$checkout = new DateTime($data['ngay_checkout']);
$so_ngay = $checkin->diff($checkout)->days;
if ($so_ngay == 0) $so_ngay = 1;

$tong_tien = $so_ngay * $data['gia_phong'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa Đơn Thanh Toán - #DP<?php echo $data['id']; ?></title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f6f9; padding: 40px; }
        .invoice-box { max-width: 600px; margin: auto; padding: 30px; border: 1px solid #eee; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
        .title { text-align: center; color: #1e3c72; border-bottom: 2px solid #1e3c72; padding-bottom: 10px; }
        table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; margin-top: 20px; }
        td, th { padding: 10px; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; }
        .total { font-size: 18px; font-weight: bold; color: #e74c3c; text-align: right; margin-top: 20px; }
        .btn-print { background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>

<div class="invoice-box">
    <h2 class="title">🧾 HÓA ĐƠN THANH TOÁN KHÁCH SẠN</h2>
    <p><strong>Mã Đặt Phòng:</strong> #DP<?php echo $data['id']; ?></p>
    <p><strong>Ngày Lập Hóa Đơn:</strong> <?php echo date('d/m/Y H:i'); ?></p>
    <hr>
    <p><strong>Họ Tên Khách Hàng:</strong> <?php echo htmlspecialchars($data['ho_ten']); ?></p>
    <p><strong>Số Điện Thoại:</strong> <?php echo htmlspecialchars($data['so_dien_thoai']); ?></p>
    <p><strong>Số CCCD:</strong> <?php echo htmlspecialchars($data['cccd']); ?></p>
    
    <table>
        <thead>
            <tr>
                <th>Chi Tiết</th>
                <th>Thông Tin</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Tên Phòng</td><td><strong><?php echo $data['ten_phong']; ?></strong> (<?php echo $data['loai_phong']; ?>)</td></tr>
            <tr><td>Đơn Giá / Ngày</td><td><?php echo number_format($data['gia_phong']); ?> VNĐ</td></tr>
            <tr><td>Ngày Check-in</td><td><?php echo date('d/m/Y', strtotime($data['ngay_checkin'])); ?></td></tr>
            <tr><td>Ngày Check-out</td><td><?php echo date('d/m/Y', strtotime($data['ngay_checkout'])); ?></td></tr>
            <tr><td>Tổng Số Ngày Ở</td><td><strong><?php echo $so_ngay; ?> ngày</strong></td></tr>
        </tbody>
    </table>

    <div class="total">
        TỔNG TIỀN THANH TOÁN: <?php echo number_format($tong_tien); ?> VNĐ
    </div>

    <div style="text-align: center;">
        <button onclick="window.print()" class="btn-print">🖨️ In Hóa Đơn</button>
        <a href="index.php" class="btn-print" style="background: #7f8c8d;">⬅️ Quay Lại Trang Chủ</a>
    </div>
</div>

</body>
</html>