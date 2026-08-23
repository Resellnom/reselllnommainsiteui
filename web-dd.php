

<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Web Hosting | Resellnom | VPS Server | Reseller Hosting</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
        <!-- Place favicon.ico in the root directory -->

		<!-- CSS here -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/animate.min.css">
        <link rel="stylesheet" href="css/magnific-popup.css">
        <link rel="stylesheet" href="css/fontawesome-all.min.css">
        <link rel="stylesheet" href="css/nice-select.css">
        <link rel="stylesheet" href="css/flaticon.css">
        <link rel="stylesheet" href="css/odometer.css">
        <link rel="stylesheet" href="css/slick.css">
        <link rel="stylesheet" href="css/default.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/responsive.css">
    </head>
    <body>

        <!-- Preloader -->
        <div id="preloader">
            <div class="preloader-wrapper">
                <div class="spinner"></div>
            </div>
        </div>
        <!-- Preloader-end -->

		<!-- Scroll-top -->
        <button class="scroll-top scroll-to-target" data-target="html">
            <i class="fas fa-angle-up"></i>
        </button>
        <!-- Scroll-top-end-->

        <?php include "./inc/header.php"; ?>

        <!-- main-area -->
        <main>

            <!-- breadcrumb-area -->
            <section class="breadcrumb-area breadcrumb-bg" data-background="img/bg/breadcrumb_bg.jpg" style="background-image: url(&quot;img/bg/breadcrumb_bg.jpg&quot;);">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="breadcrumb-content text-center">
                                <h2>Premium Web Hosting</h2>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Premium Web Hosting</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="breadcrumb-shape alltuchtopdown"><img src="img/images/breadcrumb_roket.png" alt=""></div>
            </section>
            <!-- breadcrumb-area-end -->

            <!-- pricing-area -->
            <section class="pricing-area gray-bg position-relative pt-100 pb-70">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="section-title text-center mb-70">
                                <span class="sub-title">HOST YOUR WEBSITE WITH US</span>
                                <h2 class="title">Choose Your Web Hosting Plan</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row pricing-box-wrap justify-content-center">
                        <div class="col-lg-4 col-md-6 col-sm-9">
                            <div class="pricing-box mb-30">
                                <div class="pricing-head">
                                    <h6>Mini</h6>
                                    <div class="pricing-icon services-icon">
                                        <i class="flaticon-hosting"></i>
                                    </div>
                                </div>
                                <div class="pricing-list mb-30">
                                    <h5>features</h5>
                                     <ul>
                                                <li><i class="fas fa-check"></i> 1 GB Storage</li> <li><i class="fas fa-check"></i> 50 GB Bandwidth</li> <li><i class="fas fa-check"></i> 1 Website</li> <li><i class="fas fa-check"></i>Free Migration</li> <li><i class="fas fa-check"></i>Limited Email Accounts</li> <li><i class="fas fa-check"></i> Lifetime SSL Certificate</li> <li><i class="fas fa-check"></i> LifeSpeed Web Server</li>                                            </ul>
                                    <ul>
                                      
                                    </ul>
                                </div>
                                 <div class="price mb-20">
                                            <h2><?php
                                                $url = "https://my.resellnom.com/feeds/productsinfo.php?pid=28&get=price&billingcycle=monthly&currency=2";
                                                $newCurl = curl_init();
                                                curl_setopt($newCurl, CURLOPT_URL, $url);
                                                curl_exec($newCurl);
                                                ?><span>/mo</span></h2>
                                        </div>
                                        <div class="pricing-btn">
                                            <a href="https://my.resellnom.com/store/premium-web/mini" class="btn"><span>+</span> Get started</a>
                                        </div>
                            </div>
                        </div>
                        
                        
           
                        
                        
                        <div class="col-lg-4 col-md-6 col-sm-9">
                            <div class="pricing-box mb-30">
                                <div class="pricing-head">
                                     <h6>Bronze</h6>

                                    <h6><script language="javascript" src="https://my.resellnom.com/feeds/productsinfo.php?pid=6&get=name"></script></h6>
                                    <div class="pricing-icon services-icon">
                                        <i class="flaticon-hosting"></i>
                                    </div>
                                </div>
                                <div class="pricing-list mb-30">
                                    <h5>features</h5>
                                    
                                    <ul>
                                                <li><i class="fas fa-check"></i> 5 GB Storage</li> <li><i class="fas fa-check"></i> 250 GB Bandwidth</li> <li><i class="fas fa-check"></i> 5 Website</li> <li><i class="fas fa-check"></i> Free Migration</li> <li><i class="fas fa-check"></i> Limited Email Accounts</li> <li><i class="fas fa-check"></i> Lifetime SSL Certificate</li> <li><i class="fas fa-check"></i> LifeSpeed Web Server</li>                                            </ul>
                                                
                                                
                                    <ul>
                                        <script language="javascript" src="https://my.resellnom.com/feeds/productsinfo.php?pid=6&get=description"></script>
                                    </ul>
                                </div>
                                  <div class="price mb-20">
                                            <h2>
                                            <?php
                                                $url = "https://my.resellnom.com/feeds/productsinfo.php?pid=6&get=price&billingcycle=monthly&currency=2";
                                                $newCurl = curl_init();
                                                curl_setopt($newCurl, CURLOPT_URL, $url);
                                                curl_exec($newCurl);
                                                ?><span>/mo</span></h2>
                                        </div>
                                        <div class="pricing-btn">
                                            <a href="https://my.resellnom.com/cart.php?a=add&amp;pid=6" class="btn"><span>+</span> Get started</a>
                                        </div>>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-9">
                            <div class="pricing-box mb-30">
                                <div class="pricing-head">
                                     <h6>Gold</h6>
                                    <h6><script language="javascript" src="https://my.resellnom.com/feeds/productsinfo.php?pid=7&get=name"></script></h6>
                                    <div class="pricing-icon services-icon">
                                        <i class="flaticon-hosting"></i>
                                    </div>
                                </div>
                                <div class="pricing-list mb-30">
                                    <h5>features</h5>
                                    
                                     <ul>
                                                <li><i class="fas fa-check"></i> 10 GB Storage</li> <li><i class="fas fa-check"></i> 500 GB Bandwidth</li> <li><i class="fas fa-check"></i> 10 Website</li> <li><i class="fas fa-check"></i> Free Migration</li> <li><i class="fas fa-check"></i> Limited Email Accounts</li> <li><i class="fas fa-check"></i> Lifetime SSL Certificate</li> <li><i class="fas fa-check"></i> LifeSpeed Web Server</li>                                            </ul>
                                                
                                                
                                    <ul>
                                        <script language="javascript" src="https://my.resellnom.com/feeds/productsinfo.php?pid=7&get=description"></script>
                                    </ul>
                                </div>
                                <div class="price mb-20">
                                            <h2>
                                            <?php
                                                $url = "https://my.resellnom.com/feeds/productsinfo.php?pid=7&get=price&billingcycle=monthly&currency=2";
                                                $newCurl = curl_init();
                                                curl_setopt($newCurl, CURLOPT_URL, $url);
                                                curl_exec($newCurl);
                                                ?>
                                            <span>/mo</span></h2>
                                        </div>
                                        <div class="pricing-btn">
                                            <a href="https://my.resellnom.com/cart.php?a=add&amp;pid=7" class="btn"><span>+</span> Get started</a>
                                        </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-9">
                            <div class="pricing-box mb-30">
                                <div class="pricing-head">
                                     <h6>Plus</h6>
                                    <h6><script language="javascript" src="https://my.resellnom.com/feeds/productsinfo.php?pid=8&get=name"></script></h6>
                                    <div class="pricing-icon services-icon">
                                        <i class="flaticon-hosting"></i>
                                    </div>
                                </div>
                                <div class="pricing-list mb-30">
                                    <h5>features</h5>
                                    
                                      <ul>
                                                <li><i class="fas fa-check"></i> 20 GB Storage</li> <li><i class="fas fa-check"></i> 1 TB Bandwidth</li> <li><i class="fas fa-check"></i> 20 Website</li> <li><i class="fas fa-check"></i> Free Migration</li> <li><i class="fas fa-check"></i> Limited Email Accounts</li> <li><i class="fas fa-check"></i> Lifetime SSL Certificate</li> <li><i class="fas fa-check"></i> LifeSpeed Web Server</li>                                            </ul>
                                    
                                    <ul>
                                        <script language="javascript" src="https://my.resellnom.com/feeds/productsinfo.php?pid=8&get=description"></script>
                                    </ul>
                                </div>
                                 <div class="price mb-20">
                                            <h2><?php
                                                $url = "https://my.resellnom.com/feeds/productsinfo.php?pid=8&get=price&billingcycle=monthly&currency=2";
                                                $newCurl = curl_init();
                                                curl_setopt($newCurl, CURLOPT_URL, $url);
                                                curl_exec($newCurl);
                                                ?><span>/mo</span></h2>
                                        </div>
                                        <div class="pricing-btn">
                                            <a href="https://my.resellnom.com/cart.php?a=add&amp;pid=8" class="btn"><span>+</span> Get started</a>
                                        </div>
                            </div>
                        </div>
                        
                        
                         <div class="col-lg-4 col-md-6 col-sm-9">
                            <div class="pricing-box mb-30">
                                <div class="pricing-head">
                                    <h6>Platinam</h6>
                                    <h6><script language="javascript" src="https://my.resellnom.com/feeds/productsinfo.php?pid=8&get=name"></script></h6>
                                    <div class="pricing-icon services-icon">
                                        <i class="flaticon-hosting"></i>
                                    </div>
                                </div>
                                <div class="pricing-list mb-30">
                                    <h5>features</h5>
                                    
                                     <ul>
                                                <li><i class="fas fa-check"></i> 40 GB Storage</li> <li><i class="fas fa-check"></i> 2 TB Bandwidth</li> <li><i class="fas fa-check"></i> 30 Website</li> <li><i class="fas fa-check"></i> Free Migration</li> <li><i class="fas fa-check"></i> Limited Email Accounts</li> <li><i class="fas fa-check"></i> Lifetime SSL Certificate</li> <li><i class="fas fa-check"></i> LifeSpeed Web Server</li>                                            </ul>
                                    
                                    <ul>
                                        <script language="javascript" src="https://my.resellnom.com/feeds/productsinfo.php?pid=8&get=description"></script>
                                    </ul>
                                </div>
                                 <div class="price mb-20">
                                            <h2>
                                            <?php
                                                $url = "https://my.resellnom.com/feeds/productsinfo.php?pid=10&get=price&billingcycle=monthly&currency=2";
                                                $newCurl = curl_init();
                                                curl_setopt($newCurl, CURLOPT_URL, $url);
                                                curl_exec($newCurl);
                                                ?><span>/mo</span></h2>
                                        </div>
                                        <div class="pricing-btn">
                                            <a href="https://my.resellnom.com/cart.php?a=add&amp;pid=10" class="btn"><span>+</span> Get started</a>
                                        </div>
                            </div>
                        </div>
                        
                        
                         <div class="col-lg-4 col-md-6 col-sm-9">
                            <div class="pricing-box mb-30">
                                <div class="pricing-head">
                                    <h6>Custom</h6>
                                    <h6><script language="javascript" src="https://my.resellnom.com/feeds/productsinfo.php?pid=8&get=name"></script></h6>
                                    <div class="pricing-icon services-icon">
                                        <i class="flaticon-hosting"></i>
                                    </div>
                                </div>
                                <div class="pricing-list mb-30">
                                    <h5>features</h5>
                                    
                                      <ul>
                                                <li><i class="fas fa-check"></i> 50 GB Storage</li> <li><i class="fas fa-check"></i> 3 TB Bandwidth</li> <li><i class="fas fa-check"></i> 30 Website</li> <li><i class="fas fa-check"></i> Free Migration</li> <li><i class="fas fa-check"></i> Limited Email Accounts</li> <li><i class="fas fa-check"></i> Lifetime SSL Certificate</li> <li><i class="fas fa-check"></i> LifeSpeed Web Server</li>                                            </ul>
                                    
                                    
                                    <ul>
                                        <script language="javascript" src="https://my.resellnom.com/feeds/productsinfo.php?pid=8&get=description"></script>
                                    </ul>
                                </div>
                                <div class="price mb-20">
                                            <h2>
                                            <?php
                                                $url = "https://my.resellnom.com/feeds/productsinfo.php?pid=12&get=price&billingcycle=monthly&currency=2";
                                                $newCurl = curl_init();
                                                curl_setopt($newCurl, CURLOPT_URL, $url);
                                                curl_exec($newCurl);
                                                ?>
                                            <span>/mo</span></h2>
                                        </div>
                                        <div class="pricing-btn">
                                            <a href="https://my.resellnom.com/cart.php?a=add&amp;pid=12" class="btn"><span>+</span> Get started</a>
                                        </div>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
                
                
                
                
                
                
            </section>
            <!-- pricing-area-end -->
            
            <!--service explain-->
            <section class="hosting-features-area fix pt-100 pb-70">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="section-title title-style-two white-title text-center mb-60">
                                <span class="sub-title">That's why we're Best</span>
                                <h2 class="title">Resellnom Features</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 col-sm-8">
                            <div class="hosting-features-item text-center mb-30">
                                <div class="hosting-features-icon mb-35">
                                    <img src="img/icon/hosting_features_icon01.png" alt="">
                                </div>
                                <div class="hosting-features-content">
                                    <h4>24/7 Expert Support</h4>
                                    <p>Fully-Managed SSD Shared Hosting optimal solution for fast reliankwn printer galley of type scrambled.</p>
                                    <a href="#" class="btn green-btn">Sign Up Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-8">
                            <div class="hosting-features-item text-center mb-30">
                                <div class="hosting-features-icon mb-35">
                                    <img src="img/icon/hosting_features_icon02.png" alt="">
                                </div>
                                <div class="hosting-features-content">
                                    <h4>100% Uptime Guaranteed</h4>
                                    <p>Fully-Managed SSD Shared Hosting optimal solution for fast reliankwn printer galley of type scrambled.</p>
                                    <a href="#" class="btn green-btn">Sign Up Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-8">
                            <div class="hosting-features-item text-center mb-30">
                                <div class="hosting-features-icon mb-35">
                                    <img src="img/icon/hosting_features_icon03.png" alt="">
                                </div>
                                <div class="hosting-features-content">
                                    <h4>Fast &amp; Reliable</h4>
                                    <p>Fully-Managed SSD Shared Hosting optimal solution for fast reliankwn printer galley of type scrambled.</p>
                                    <a href="#" class="btn green-btn">Sign Up Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="exclusive-services-shape"><img src="img/images/services_circle_shape.png" alt=""></div>
            </section>
            <!--service explain end-->

           
            <!-- faq-area -->
            <section class="faq-area pt-100 pb-100">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="faq-img">
                                <img src="img/images/faq_img.png" alt="">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="section-title text-left mb-40">
                                <span class="sub-title">faq help</span>
                                <h2 class="title">Frequently Asked Questions</h2>
                            </div>
                            <div class="faq-wrap">
                                <div class="faq-set">
                                    <a class="active" href="#">What is web hosting and why do I need it?<i
                                            class="fas fa-angle-down"></i></a>
                                    <div class="content" style="display: block;">
                                        <p>Web hosting is a service that allows you to store your website files
                                             and data on a server, making it accessible to visitors on the internet. 
                                             You need web hosting to make your website live and accessible to the public.                                        </p>
                                    </div>
                                </div>
                                <div class="faq-set">
                                    <a href="#">What is reseller hosting and how does it work?<i class="fas fa-angle-up"></i></a>
                                    <div class="content">
                                        <p>Reseller hosting allows you to sell hosting services to your own clients using 
                                            your own branding and pricing. You can create and manage multiple hosting 
                                            accounts, and your clients will receive their own control panel to manage their hosting.
                                        </p>
                                    </div>
                                </div>
                                <div class="faq-set">
                                    <a href="#">How do I choose the right hosting plan for my website? <i class="fas fa-angle-up"></i></a>
                                    <div class="content">
                                        <p>Consider factors such as your website's traffic, storage and bandwidth needs, 
                                            security requirements, and budget. You may also want to look for features 
                                            such as customer support, uptime guarantees, and scalability.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- faq-area-end -->
            
            <!-- brand-area -->
            <div class="brand-area gradient-bg ">
                <div class="container">
                    <div class="row brand-active">
                        <div class="col-xl-2">
                            <div class="brand-item text-center">
                                <img src="img/brand/cpanel-whm.png" alt="">
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="brand-item text-center">
                                <img src="img/brand/plesk.png" alt="">
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="brand-item text-center">
                                <img src="img/brand/directadmin.png" alt="">
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="brand-item text-center">
                                <img src="img/brand/cloudlinux.png" alt="">
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="brand-item text-center">
                                <img src="img/brand/ubuntu.png" alt="">
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="brand-item text-center">
                                <img src="img/brand/windowse.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- brand-area-end -->
            
        </main>
        <!-- main-area-end -->


       <?php include "./inc/footer.php"; ?>


		<!-- JS here -->
        <script src="js/vendor/jquery-3.5.0.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/isotope.pkgd.min.js"></script>
        <script src="js/imagesloaded.pkgd.min.js"></script>
        <script src="js/jquery.magnific-popup.min.js"></script>
        <script src="js/jquery.nice-select.min.js"></script>
        <script src="js/jquery.odometer.min.js"></script>
        <script src="js/jquery.countdown.min.js"></script>
        <script src="js/jquery.appear.js"></script>
        <script src="js/slick.min.js"></script>
        <script src="js/ajax-form.js"></script>
        <script src="js/wow.min.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        <script src="js/custom.js"></script>
    </body>
</html>
