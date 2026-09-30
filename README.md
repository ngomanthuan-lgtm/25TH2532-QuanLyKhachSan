# 🏨 BÀI TẬP LỚN MÔN PHÁT TRIỂN PHẦN MỀM MÃ NGUỒN MỞ
> **ĐỀ TÀI: ỨNG DỤNG QUẢN LÝ KHÁCH SẠN (HOTEL MANAGEMENT SYSTEM)**

---

## 👨‍🎓 THÔNG TIN SINH VIÊN THỰC HIỆN
* **Họ và tên:** Ngô Mẫn Thuận
* **Mã số sinh viên:** 25TH2532
* **Lớp:** CC25TTH (Trường Đại học Nha Trang - NTU)
* **Môn học:** Phát triển phần mềm mã nguồn mở
* **Ngôn ngữ phát triển:** PHP 8.x
* **Hệ quản trị CSDL:** MySQL / MariaDB (XAMPP)
* **Link Repository GitHub:** [https://github.com/ngomanthuan-lgtm/25TH2532-QuanLyKhachSan](https://github.com/ngomanthuan-lgtm/25TH2532-QuanLyKhachSan)

---

## 📋 GIỚI THIỆU & MỤC TIÊU ĐỀ TÀI
Dự án được xây dựng nhằm đáp ứng đầy đủ các yêu cầu chuyên môn của đề tài Quản lý khách sạn, bao gồm:
1. **Quản lý danh mục phòng:** Quản lý thông tin các phòng khách sạn, loại phòng, giá tiền và trạng thái (Trống / Có khách).
2. **Đặt phòng chống trùng lịch (Conflict Checking Algorithm):** Tự động phát hiện và ngăn chặn việc đặt phòng bị trùng khoảng thời gian (Check-in đến Check-out) giữa các khách hàng khác nhau.
3. **Tính tiền & Xuất hóa đơn:** Tự động tính chính xác tổng số ngày lưu trú, tổng số tiền cần thanh toán và hỗ trợ in/xuất hóa đơn trực tiếp.

---

## 🗄️ THIẾT KẾ CƠ SỞ DỮ LIỆU (DATABASE SCHEMA)
Hệ thống sử dụng 4 bảng dữ liệu liên kết chặt chẽ với nhau qua khóa ngoại (Foreign Keys):
* **`phong`**: `id`, `ten_phong`, `loai_phong`, `gia_phong`, `trang_thai`
* **`khach_hang`**: `id`, `ho_ten`, `so_dien_thoai`, `cccd`
* **`dat_phong`**: `id`, `phong_id`, `khach_hang_id`, `ngay_checkin`, `ngay_checkout`, `trang_thai`
* **`hoa_don`**: `id`, `dat_phong_id`, `ngay_lap`, `tong_tien`, `trang_thai`

---

## 📸 BÁO CÁO KẾT QUẢ VÀ GIAO DIỆN CHỨC NĂNG

### 1. Giao diện trang chủ Quản lý Khách sạn
Giao diện chính được thiết kế hiện đại, bố cục 2 cột phân chia rõ ràng giữa form Đặt phòng mới và Bảng danh sách phòng / Danh sách đặt phòng:
> **Mô tả:** Hệ thống load danh sách phòng động từ CSDL MySQL, hỗ trợ chọn phòng, tự động gợi ý ngày Check-in/Check-out.

### 2. Chức năng Đặt phòng thành công
Khi người dùng nhập đầy đủ thông tin khách hàng và khoảng thời gian không bị trùng lịch:
> ✅ **Thông báo:** Hệ thống hiển thị alert xanh `✅ Đặt phòng thành công!`, lưu thông tin khách hàng mới và đưa lượt đặt phòng vào danh sách chờ xuất hóa đơn.

### 3. Chức năng Cảnh báo Trùng lịch đặt phòng
Khi khách hàng cố tình đặt vào phòng đã có người đăng ký trong cùng khoảng thời gian:
> ⚠️ **Cảnh báo:** Thuật toán phát hiện xung đột thời gian và báo lỗi màu đỏ `⚠️ TRÙNG LỊCH! Phòng này đã có người đặt trong khoảng thời gian trên.` ngăn chặn việc đặt trùng.

### 4. Chức năng Tính tiền & Xuất hóa đơn
Khi bấm vào nút `🧾 Tính Tiền & Xuất Hóa Đơn`:
> 🧾 **Kết quả:** Trang hóa đơn tự động tính số ngày ở ($Ngày\ Check-out - Ngày\ Check-in$), nhân với giá phòng/ngày để ra tổng tiền thanh toán và tích hợp nút `🖨️ In Hóa Đơn` trực tiếp.

---

## 🛠️ HƯỚNG DẪN CÀI ĐẶT & CHẠY ỨNG DỤNG LOCAL
1. Clone dự án về máy:
   ```bash
   git clone https://github.com/ngomanthuan-lgtm/25TH2532-QuanLyKhachSan.git