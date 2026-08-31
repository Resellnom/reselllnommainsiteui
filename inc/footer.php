<!-- footer-area -->
<style>
/* ── Footer brand extras ── */
.rn-footer-logo { margin-bottom: 16px; }
.rn-footer-logo img { width: 160px; }
.rn-footer-desc { font-size: 13px; color: #888; line-height: 1.8; margin-bottom: 20px; }
.rn-footer-social { display: flex; gap: 10px; flex-wrap: wrap; }
.rn-social-btn {
    width: 36px; height: 36px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; color: #888; border: 1px solid #2c2f36;
    transition: background .2s, color .2s, border-color .2s;
    text-decoration: none;
}
.rn-social-btn:hover { background: #01adef; color: #fff; border-color: #01adef; text-decoration: none; }
/* Status badge */
.rn-status-wrap { display: flex; align-items: center; gap: 8px; margin-top: 20px; }
.rn-status-indicator {
    width: 8px; height: 8px; border-radius: 50%; background: #2acb35;
    box-shadow: 0 0 0 3px rgba(42,203,53,.2);
    animation: footerPulse 2s infinite;
    flex-shrink: 0;
}
@keyframes footerPulse {
    0%,100% { box-shadow: 0 0 0 3px rgba(42,203,53,.2); }
    50%      { box-shadow: 0 0 0 6px rgba(42,203,53,.07); }
}
.rn-status-wrap span { font-size: 12px; color: #2acb35; font-weight: 600; }
/* Newsletter */
.rn-newsletter-wrap {
    background: linear-gradient(135deg, rgba(1,173,239,.1), rgba(20,106,248,.08));
    border: 1px solid rgba(1,173,239,.15);
    border-radius: 14px; padding: 28px 32px; margin-bottom: 50px;
}
.rn-newsletter-wrap .newsletter-title h5 { font-size: 20px; }
/* Footer divider */
.rn-footer-divider { height: 1px; background: #2c2f36; margin-bottom: 40px; }
/* Copyright extras */
.rn-copyright-links { display: flex; gap: 18px; flex-wrap: wrap; }
.rn-copyright-links a { font-size: 12px; color: #888; text-decoration: none; transition: color .2s; }
.rn-copyright-links a:hover { color: #01adef; }
/* Trustpilot clean */
.rn-trust-wrap { margin-top: 16px; }
</style>

<footer class="footer-style-two footer-style-three">
    <div class="footer-top-wrap dark-bg pt-90 pb-40">
        <div class="container">

            <!-- Newsletter -->
            <div class="rn-newsletter-wrap footer-newsletter mb-50">
                <div class="row justify-content-between align-items-center">
                    <div class="col-lg-5 mb-3 mb-lg-0">
                        <div class="newsletter-title">
                            <h5>Subscribe <span>Now!</span></h5>
                            <p>Get the latest deals, hosting tips and updates from ResellNom.</p>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-7">
                        <form action="#" class="newsletter-form">
                            <input type="email" name="email" placeholder="Enter your email address...">
                            <button type="submit" class="btn green-btn">
                                <i class="flaticon-startup"></i> Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="rn-footer-divider"></div>

            <div class="row">

                <!-- Brand Column -->
                <div class="col-lg-4 col-md-6 mb-50">
                    <div class="rn-footer-logo">
                        <a href="/"><img src="img/logo/w_logo.png" alt="ResellNom"></a>
                    </div>
                    <p class="rn-footer-desc">
                        ResellNom is a trusted web hosting provider offering Web Hosting, Reseller Hosting, BDIX VPS, and Dedicated Servers in Bangladesh and worldwide. Fast, reliable, and affordable.
                    </p>
                    
                    <div class="rn-footer-social mt-20">
                        <a href="https://facebook.com/resellnom" class="rn-social-btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="rn-social-btn" title="Twitter/X"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="rn-social-btn" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="rn-social-btn" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="@resellnom" class="rn-social-btn" title="Telegram"><i class="fab fa-telegram-plane"></i></a>
                        <a href="#" class="rn-social-btn" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                    <!-- Trustpilot -->
                    <div class="rn-trust-wrap">
                        <div class="trustpilot-widget"
                             data-locale="en-US"
                             data-template-id="56278e9abfbbba0bdcd568bc"
                             data-businessunit-id="6852feb2366d801e3d9a6439"
                             data-style-height="52px"
                             data-style-width="100%">
                            <a href="https://www.trustpilot.com/review/resellnom.com" target="_blank" rel="noopener">Trustpilot</a>
                        </div>
                    </div>
                </div>

                <!-- Services -->
                <div class="col-lg-2 col-md-3 col-sm-6 mb-50">
                    <div class="footer-widget">
                        <div class="fw-title mb-25">
                            <h4 class="title">Services</h4>
                        </div>
                        <div class="fw-link">
                            <ul>
                                <li><a href="web-hosting.php">Web Hosting</a></li>
                                <li><a href="contact.php">Reseller Hosting</a></li>
                                <li><a href="bdix-vps.php">BDIX VPS</a></li>
                                <li><a href="ram-optimized-bdix-vps.php">RAM Optimized VPS</a></li>
                                <li><a href="cpu-optimized-bdix-vps.php">CPU Optimized VPS</a></li>
                                <li><a href="contact.php">Dedicated Server</a></li>
                                <li><a href="domain.php">Domain Registration</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Company -->
                <div class="col-lg-2 col-md-3 col-sm-6 mb-50">
                    <div class="footer-widget">
                        <div class="fw-title mb-25">
                            <h4 class="title">Company</h4>
                        </div>
                        <div class="fw-link">
                            <ul>
                                <li><a href="about-us.php">About Us</a></li>
                                <li><a href="contact.php">Contact Us</a></li>
                                <li><a href="termsofservice.php">Terms of Service</a></li>
                                <li><a href="termsofservice.php">Privacy Policy</a></li>
                                <li><a href="termsofservice.php">Refund Policy</a></li>
                                <li><a href="termsofservice.php">SLA</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Support -->
                <div class="col-lg-2 col-md-3 col-sm-6 mb-50">
                    <div class="footer-widget">
                        <div class="fw-title mb-25">
                            <h4 class="title">Support</h4>
                        </div>
                        <div class="fw-link">
                            <ul>
                                <li><a href="https://my.resellnom.com/submitticket.php" target="_blank">Open Ticket</a></li>
                                <li><a href="https://my.resellnom.com/knowledgebase.php" target="_blank">Knowledge Base</a></li>
                                <li><a href="https://my.resellnom.com/announcements.php" target="_blank">Announcements</a></li>
                                <li><a href="https://uptime.resellnom.com" target="_blank">Network Status</a></li>
                                <li><a href="contact.php">Contact Support</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-2 col-md-3 col-sm-6 mb-50">
                    <div class="footer-widget">
                        <div class="fw-title mb-25">
                            <h4 class="title">Contact</h4>
                        </div>
                        <div class="fw-link">
                            <ul>
                                <li>
                                    <a href="mailto:sales@resellnom.com">
                                        <i class="far fa-envelope" style="margin-right:6px;color:#01adef;"></i>
                                        sales@resellnom.com
                                    </a>
                                </li>
                                <li>
                                    <a href="mailto:support@resellnom.com">
                                        <i class="fas fa-headset" style="margin-right:6px;color:#01adef;"></i>
                                        support@resellnom.com
                                    </a>
                                </li>
                                <li>
                                    <a href="https://my.resellnom.com" target="_blank">
                                        <i class="fas fa-user-circle" style="margin-right:6px;color:#01adef;"></i>
                                        Client Area
                                    </a>
                                </li>
                                <li>
                                    <a href="https://my.resellnom.com/submitticket.php" target="_blank">
                                        <i class="fas fa-clock" style="margin-right:6px;color:#01adef;"></i>
                                        24/7 Live Support
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Copyright Bar -->
    <div class="copyright-wrap-two">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-2 mb-md-0">
                    <div class="copyright-text">
                        <p>Copyright &copy;<script>document.write(new Date().getFullYear())</script>
                        <span>ResellNom</span> — All Rights Reserved.</p>
                    </div>
                    <div class="rn-copyright-links mt-1">
                        <a href="termsofservice.php">Terms</a>
                        <a href="termsofservice.php">Privacy</a>
                        <a href="termsofservice.php">Refund Policy</a>
                        <a href="sitemap.xml">Sitemap</a>
                    </div>
                </div>
                <div class="col-md-6 text-md-right">
                    <div class="payment-method-img">
                        <img height="32px" src="img/images/payment_method_all-min.png" alt="Accepted Payment Methods — bKash Nagad Visa Mastercard PayPal">
                    </div>
                </div>
            </div>
        </div>
    </div>

</footer>
<!-- footer-area-end -->

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/606b4201067c2605c0bf6c29/1f2hfjmjd';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

<!-- Google Ads Tag -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-671031402"></script>
<script>
window.dataLayer=window.dataLayer||[];
function gtag(){dataLayer.push(arguments);}
gtag('js',new Date());
gtag('config','AW-671031402');
gtag('event','conversion',{'send_to':'AW-671031402/mUOmCPjA14QYEOrA_L8C','transaction_id':''});
</script>