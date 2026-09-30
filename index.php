<?php
require_once 'db.php';

// Lấy danh sách phòng
$sql_phong = "SELECT * FROM phong ORDER BY id DESC";
$res_phong = $conn->query($sql_phong);

// Lấy danh sách đặt phòng
$sql_datphong = "SELECT dp.*, p.ten_phong, kh.ho_ten, kh.so_dien_thoai 
                 FROM dat_phong dp 
                 JOIN phong p ON dp.phong_id = p.id 
                 JOIN khach_hang kh ON dp.khach_hang_id = kh.id 
                 ORDER BY dp.id DESC";
$res_datphong = $conn->query($sql_datphong);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ thống Quản lý Khách sạn - Ngô Mẫn Thuận (25TH2532)</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; }
        .header { background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .header h1 { margin: 0; font-size: 26px; }
        .header p { margin: 5px 0 0 0; opacity: 0.9; }
        .container { display: flex; gap: 20px; margin-top: 20px; flex-wrap: wrap; }
        .card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); flex: 1; min-width: 300px; }
        h2 { color: #1e3c72; border-bottom: 2px solid #1e3c72; padding-bottom: 8px; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e1e8ed; padding: 10px; text-align: left; font-size: 14px; }
        th { background-color: #f8f9fa; color: #333; }
        .btn { padding: 6px 12px; border: none; border-radius: 4px; color: white; cursor: pointer; text-decoration: none; font-size: 12px; display: inline-block; }
        .btn-add { background-color: #27ae60; margin-bottom: 10px; font-size: 14px; }
        .btn-bill { background-color: #2980b9; }
        .badge { padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; color: white; }
        .badge-success { background-color: #2ecc71; }
        .badge-warning { background-color: #e67e22; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 4px; font-size: 13px; }
        .form-group input, .form-group select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .alert { padding: 10px; border-radius: 4px; margin-bottom: 15px; font-weight: bold; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

<div class="header">
    <h1>HỆ THỐNG QUẢN LÝ KHÁCH SẠN</h1>
    <p>Sinh viên thực hiện: <strong>Ngô Mẫn Thuận</strong> - MSSV: <strong>25TH2532</strong></p>
</div>

<div class="container">
    <!-- KHU VỰC ĐẶT PHÒNG KHÁCH SẠN -->
    <div class="card">
        <h2>🏢 ĐẶT PHÒNG MỚI (CHỐNG TRÙNG LỊCH)</h2>
        
        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] == 'conflict'): ?>
                <div class="alert alert-danger">⚠️ TRÙNG LỊCH! Phòng này đã có người đặt trong khoảng thời gian trên. Vui lòng chọn ngày hoặc phòng khác!</div>
            <?php elseif ($_GET['msg'] == 'success'): ?>
                <div class="alert alert-success">✅ Đặt phòng thành công!</div>
            <?php elseif ($_GET['msg'] == 'invalid_date'): ?>
                <div class="alert alert-danger">⚠️ Ngày trả phòng (Check-out) phải sau ngày nhận phòng (Check-in)!</div>
            <?php endif; ?>
        <?php endif; ?>

        <form action="datphong_xuly.php" method="POST">
            <div class="form-group">
                <label>Họ tên khách hàng:</label>
                <input type="text" name="ho_ten" required placeholder="Nguyễn Văn A">
            </div>
            <div class="form-group">
                <label>Số điện thoại:</label>
                <input type="text" name="so_dien_thoai" required placeholder="0901234567">
            </div>
            <div class="form-group">
                <label>Số CCCD/CMND:</label>
                <input type="text" name="cccd" required placeholder="05609xxxxxxx">
            </div>
            <div class="form-group">
                <label>Chọn Phòng:</label>
                <select name="phong_id" required>
                    <?php 
                    $res_phong_select = $conn->query("SELECT * FROM phong");
                    while($p = $res_phong_select->fetch_assoc()): 
                    ?>
                        <option value="<?php echo $p['id']; ?>">
                            <?php echo $p['ten_phong']; ?> (<?php echo $p['loai_phong']; ?> - <?php echo number_format($p['gia_phong']); ?>đ/ngày)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Ngày nhận phòng (Check-in):</label>
                <input type="date" name="ngay_checkin" required value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group">
                <label>Ngày trả phòng (Check-out):</label>
                <input type="date" name="ngay_checkout" required value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
            </div>
            <button type="submit" class="btn btn-add" style="width: 100%;">XÁC NHẬN ĐẶT PHÒNG</button>
        </form>
    </div>

    <!-- KHU VỰC DANH SÁCH ĐẶT PHÒNG & XUẤT HÓA ĐƠN -->
    <div class="card" style="flex: 2;">
        <h2>📋 DANH SÁCH ĐẶT PHÒNG & XUẤT HÓA ĐƠN</h2>
        <table>
            <thead>
                <tr>
                    <th>Mã Đặt</th>
                    <th>Khách Hàng</th>
                    <th>Phòng</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($res_datphong->num_rows > 0): ?>
                    <?php while($dp = $res_datphong->fetch_assoc()): ?>
                        <tr>
                            <td>#DP<?php echo $dp['id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($dp['ho_ten']); ?></strong><br>
                                <small>SĐT: <?php echo htmlspecialchars($dp['so_dien_thoai']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($dp['ten_phong']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($dp['ngay_checkin'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($dp['ngay_checkout'])); ?></td>
                            <td>
                                <span class="badge badge-success"><?php echo $dp['trang_thai']; ?></span>
                            </td>
                            <td>
                                <a href="hoadon.php?id=<?php echo $dp['id']; ?>" class="btn btn-bill">🧾 Tính Tiền & Xuất Hóa Đơn</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align: center;">Chưa có lượt đặt phòng nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2 style="margin-top: 30px;">🚪 QUẢN LÝ PHÒNG</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Phòng</th>
                    <th>Loại Phòng</th>
                    <th>Giá / Ngày</th>
                    <th>Trạng Thái</th>
                </tr>
            </thead>
            <tbody>
                <?php while($p = $res_phong->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $p['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($p['ten_phong']); ?></strong></td>
                        <td><?php echo htmlspecialchars($p['loai_phong']); ?></td>
                        <td><?php echo number_format($p['gia_phong']); ?> VNĐ</td>
                        <td>
                            <span class="badge <?php echo $p['trang_thai'] == 'Trống' ? 'badge-success' : 'badge-warning'; ?>">
                                <?php echo $p['trang_thai']; ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>