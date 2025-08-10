        </div>
    </main>
    
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><i class="fas fa-store"></i> REMA-SHOP</h3>
                    <p>Votre boutique en ligne de confiance pour tous vos besoins.</p>
                </div>
                
                <div class="footer-section">
                    <h4>Navigation</h4>
                    <ul>
                        <li><a href="index.php">Accueil</a></li>
                        <li><a href="ajouter.php">Ajouter un produit</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="apropos.php">À propos</a></li>
                        <li><a href="panier.php">Panier</a></li>
                        <li><a href="modifier_image.php?id=1">Modifier une image (test)</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p><i class="fas fa-envelope"></i> demetech@gmail.com</p>
                    <p><i class="fas fa-phone"></i> +221 77 553 80 01</p>
                </div>
                
                <div class="footer-section">
                    <h4>Suivez-nous</h4>
                    <div class="social-links">
                        <a href="https://www.facebook.com/people/Rema-Kids/100063605978720/" target="_blank" rel="noopener"><i class="fab fa-facebook"></i></a>
                        <a href="#" target="_blank" rel="noopener"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/remakids/" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
                        <a href="#" target="_blank" rel="noopener"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> REMA-SHOP. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
    
    <script>
        // Menu mobile
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggle = document.querySelector('.mobile-menu-toggle');
            const navMenu = document.querySelector('.nav-menu');
            
            if (mobileToggle && navMenu) {
                mobileToggle.addEventListener('click', function() {
                    navMenu.classList.toggle('active');
                    this.classList.toggle('active');
                });
            }
            
            // Fermer le menu mobile lors du clic sur un lien
            const navLinks = document.querySelectorAll('.nav-menu a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    navMenu.classList.remove('active');
                    mobileToggle.classList.remove('active');
                });
            });
            
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>

