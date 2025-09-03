<footer class="footer">
    <div class="footer-container">
        <div class="footer-grid">
            <div class="footer-section">
                <div class="footer-brand">
                    <i class="fas fa-book"></i>
                    <span>Boi Lagbe!</span>
                </div>
                <p class="footer-description">
                    Your premier destination for books of all genres. We believe in the power of reading to transform lives and inspire minds.
                </p>
                <div class="footer-social">
                    <a href="#" class="social-link">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fab fa-linkedin"></i>
                    </a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3 class="footer-title">Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
      </div>
            
            <div class="footer-section">
                <h3 class="footer-title">Customer Area</h3>
                <ul class="footer-links">
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="cart.php">Cart</a></li>
                    <li><a href="orders.php">Orders</a></li>
                </ul>
    </div>

            <div class="footer-section">
                <h3 class="footer-title">Contact Info</h3>
                <ul class="footer-contact">
                    <li>
                        <i class="fas fa-phone"></i>
                        <span>+880 1971961137</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span>boilagbe@gmail.com</span>
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Dhaka, Bangladesh</span>
                    </li>
                    <li>
                        <i class="fas fa-clock"></i>
                        <span>Open 24/7</span>
                    </li>
                </ul>
            </div>
    </div>

        <div class="footer-bottom">
            <p class="copyright">
                Copyright © <?php echo date('Y'); ?> BoiLagbe!.com. All rights reserved.
            </p>
            <div class="footer-bottom-links">
                <a href="privacy_policy.php">Privacy Policy</a>
                <a href="terms_of_service.php">Terms of Service</a>
                <a href="cookie_policy.php">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>

<style>
.footer {
    background-color: var(--text-primary);
    color: white;
    padding: var(--spacing-xl) 0;
    margin-top: var(--spacing-xl);
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-lg);
}

.footer-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--spacing-xl);
    margin-bottom: var(--spacing-xl);
}

.footer-brand {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: var(--spacing-md);
}

.footer-brand i {
    color: var(--primary);
}

.footer-description {
    color: rgba(255, 255, 255, 0.7);
    line-height: 1.6;
    margin-bottom: var(--spacing-lg);
}

.footer-social {
    display: flex;
    gap: var(--spacing-md);
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 9999px;
    background-color: rgba(255, 255, 255, 0.1);
    color: white;
    text-decoration: none;
    transition: all 0.2s ease;
}

.social-link:hover {
    background-color: var(--primary);
    transform: translateY(-2px);
}

.footer-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: var(--spacing-lg);
}

.footer-links {
    list-style: none;
    padding: 0;
}

.footer-links li {
    margin-bottom: var(--spacing-sm);
}

.footer-links a {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: all 0.2s ease;
}

.footer-links a:hover {
    color: white;
    padding-left: var(--spacing-xs);
}

.footer-contact {
    list-style: none;
    padding: 0;
}

.footer-contact li {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-sm);
    color: rgba(255, 255, 255, 0.7);
}

.footer-contact i {
    color: var(--primary);
}

.footer-bottom {
    padding-top: var(--spacing-lg);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.copyright {
    color: rgba(255, 255, 255, 0.7);
}

.footer-bottom-links {
    display: flex;
    gap: var(--spacing-lg);
}

.footer-bottom-links a {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.footer-bottom-links a:hover {
    color: white;
}

@media (max-width: 1024px) {
    .footer-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .footer-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
    
    .footer-bottom {
        flex-direction: column;
        gap: var(--spacing-md);
        text-align: center;
    }
    
    .footer-bottom-links {
        flex-direction: column;
        gap: var(--spacing-sm);
    }
}
</style>
