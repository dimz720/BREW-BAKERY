<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>Tentang Brew Bakery</h3>
            <p>Kami menjual roti dan pastry berkualitas tinggi dengan cita rasa istimewa. Diproduksi fresh setiap hari dengan bahan-bahan pilihan terbaik.</p>
        </div>
        <div class="footer-section">
            <h3>Menu</h3>
            <ul>
                <li><a href="<?php echo BASE_URL; ?>">Beranda</a></li>
                <li><a href="<?php echo CUSTOMER_URL; ?>shop.php">Belanja</a></li>
                <li><a href="<?php echo CUSTOMER_URL; ?>articles.php">Artikel</a></li>
                <li><a href="<?php echo AUTH_URL; ?>login-customer.php">Login</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Hubungi Kami</h3>
            <ul>
                <li>📞 +62 812-3456-7890</li>
                <li>📧 info@brewbakery.com</li>
                <li>📍 Jl. Bakery No. 123, Jakarta</li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Media Sosial</h3>
<div class="social-links">
    <a href="https://www.facebook.com" target="_blank">Facebook</a>
    <a href="https://www.instagram.com" target="_blank">Instagram</a>
    <a href="https://wa.me/6281234567890" target="_blank">WhatsApp</a>
</div>

    <div class="footer-bottom">
        <p>&copy; 2024 Brew Bakery. Semua hak dilindungi.</p>
    </div>
</footer>

<style>
    .footer {
        background-color: var(--dark);
        color: white;
        padding: 3rem 0 1rem;
        margin-top: 4rem;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .footer-section h3 {
        color: var(--secondary);
        margin-bottom: 1rem;
    }

    .footer-section ul {
        list-style: none;
    }

    .footer-section ul li {
        margin-bottom: 0.5rem;
    }

    .footer-section a {
        color: white;
        text-decoration: none;
        transition: color 0.3s;
    }

    .footer-section a:hover {
        color: var(--secondary);
    }

    .social-links {
        display: flex;
        gap: 1rem;
    }

    .social-links a {
        display: inline-block;
        padding: 0.5rem 1rem;
        background-color: var(--primary);
        border-radius: 0.3rem;
        transition: background-color 0.3s;
    }

    .social-links a:hover {
        background-color: var(--secondary);
    }

    .footer-bottom {
        text-align: center;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        color: #ccc;
    }

    @media (max-width: 768px) {
        .footer-container {
            grid-template-columns: 1fr;
        }

        .social-links {
            flex-direction: column;
        }
    }
</style>
