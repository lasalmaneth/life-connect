<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="f-brand">
                <div class="f-logo"><img src="<?= ROOT ?>/public/assets/images/logo.png" alt="Logo"><span>Life Connect</span></div>
                <p>Connecting lives through compassion. Sri Lanka's national platform for organ and body donation coordination.</p>
                <div class="f-social">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="f-col"><h4>Explore</h4><ul><li><a href="<?= ROOT ?>/home">Home</a></li><li><a href="<?= ROOT ?>/home#stats">Statistics</a></li><li><a href="<?= ROOT ?>/education">Education</a></li><li><a href="<?= ROOT ?>/legal">Legal Framework</a></li></ul></div>
            <div class="f-col"><h4>About</h4><ul><li><a href="<?= ROOT ?>/our-story">Our Story</a></li><li><a href="<?= ROOT ?>/religion">Faith & Donation</a></li><li><a href="<?= ROOT ?>/reach-us">Contact Us</a></li></ul></div>
            <div class="f-col"><h4>Donation</h4><ul><li><a href="<?= ROOT ?>/signup">Become a Donor</a></li><li><a href="<?= ROOT ?>/live-donation">Live Donation</a></li><li><a href="<?= ROOT ?>/deceased-donation">Deceased Donation</a></li></ul></div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Life Connect Sri Lanka. All Rights Reserved.</p>
            <div><a href="#">Privacy Policy</a><a href="#">Terms of Service</a></div>
        </div>
    </div>
</footer>

<script>
// Hamburger
document.getElementById('hamburger')?.addEventListener('click',()=>{
    document.getElementById('navLinks')?.classList.toggle('open');
});
</script>
