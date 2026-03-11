<style>
    #footer {
        background: #0f172a;
        color: white;
        font-family: 'Segoe UI', sans-serif;
    }

    .footer-container {
        max-width: 1300px;
        margin: auto;
        padding: 50px 20px 20px;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 30px;
    }

    .footer-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #38bdf8;
    }

    .footer-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-list li {
        margin-bottom: 10px;
        font-size: 14px;
        color: #cbd5f5;
    }

    .footer-list a {
        color: #cbd5f5;
        text-decoration: none;
        transition: 0.3s;
    }

    .footer-list a:hover {
        color: #38bdf8;
        padding-left: 5px;
    }

    .footer-social a {
        display: inline-block;
        margin-right: 10px;
        font-size: 18px;
        color: #cbd5f5;
        transition: 0.3s;
    }

    .footer-social a:hover {
        color: #38bdf8;
        transform: scale(1.2);
    }

    .footer-bottom {
        border-top: 1px solid #1e293b;
        margin-top: 30px;
        padding-top: 15px;
        text-align: center;
        font-size: 13px;
        color: #94a3b8;
    }
</style>

<footer id="footer">
    <div class="footer-container">
        <div class="footer-grid">

            <div>
                <div class="footer-title">Liên hệ</div>
                <ul class="footer-list">
                    <li>📍 168 Nguyễn Văn Cừ, Cần Thơ</li>
                    <li>📞 (0292) 3 798 222</li>
                    <li>✉ dhnamcantho@nctu.edu.vn</li>
                </ul>
            </div>

            <div>
                <div class="footer-title">Dịch vụ</div>
                <ul class="footer-list">
                    <li><a href="#">Tour trong nước</a></li>
                    <li><a href="#">Tour quốc tế</a></li>
                    <li><a href="#">Khách sạn</a></li>
                    <li><a href="#">Vé máy bay</a></li>
                    <li><a href="#">Thuê xe du lịch</a></li>
                </ul>
            </div>

            <div>
                <div class="footer-title">Tài khoản</div>
                <ul class="footer-list">
                    <li><a href="#">Đăng nhập</a></li>
                    <li><a href="#">Đăng ký</a></li>
                    <li><a href="#">Quản lý đặt tour</a></li>
                    <li><a href="#">Quên mật khẩu</a></li>
                </ul>
            </div>

            <div>
                <div class="footer-title">Về hệ thống</div>
                <ul class="footer-list">
                    <li><a href="#">Giới thiệu</a></li>
                    <li><a href="#">Điều khoản sử dụng</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                </ul>
                <div class="footer-social">
                    <a href="#"><i class="fa fa-facebook"></i></a>
                    <a href="#"><i class="fa fa-youtube-play"></i></a>
                    <a href="#"><i class="fa fa-instagram"></i></a>
                    <a href="#"><i class="fa fa-linkedin"></i></a>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            © 2026 Hệ thống quản lý & đặt tour du lịch | Phát triển bởi NCTU
        </div>
    </div>
</footer>
