<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ho_ten = $_POST['ho_ten'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $cccd = $_POST['cccd'];
    $phong_id = $_POST['phong_id'];
    $ngay_checkin = $_POST['ngay_checkin'];
    $ngay_checkout = $_POST['ngay_checkout'];

    // 1. Kiểm tra ngày checkout phải sau checkin
    if (strtotime($ngay_checkout) <= strtotime($ngay_checkin)) {
        header("Location: index.php?msg=invalid_date");
        exit();
    }

    // 2. THUẬT TOÁN KIỂM TRA TRÙNG LỊCH ĐẶT PHÒNG
    $sql_check = "SELECT * FROM dat_phong 
                  WHERE phong_id = '$phong_id' 
                  AND trang_thai = 'Đã đặt'
                  AND NOT (ngay_checkout <= '$ngay_checkin' OR ngay_checkin >= '$ngay_checkout')";
    
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        // Trùng lịch đặt phòng!
        header("Location: index.php?msg=conflict");
        exit();
    }

    // 3. Thêm khách hàng mới
    $sql_kh = "INSERT INTO khach_hang (ho_ten, so_dien_thoai, cccd) VALUES ('$ho_ten', '$so_dien_thoai', '$cccd')";
    $conn->query($sql_kh);
    $khach_hang_id = $conn->insert_id;

    // 4. Đặt phòng thành công
    $sql_dp = "INSERT INTO dat_phong (phong_id, khach_hang_id, ngay_checkin, ngay_checkout, trang_thai) 
               VALUES ('$phong_id', '$khach_hang_id', '$ngay_checkin', '$ngay_checkout', 'Đã đặt')";
    $conn->query($sql_dp);

    // Cập nhật trạng thái phòng
    $conn->query("UPDATE phong SET trang_thai = 'Có khách' WHERE id = '$phong_id'");

    header("Location: index.php?msg=success");
    exit();
}
?>