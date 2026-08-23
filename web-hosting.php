<?php
// ═══════════════════════════════════════════════════════════
// WHMCS PRICE + NAME FETCH — Server-side, 30min cache
// ═══════════════════════════════════════════════════════════
$whmcs = 'https://my.resellnom.com';

$plans = [
    [
        'pid'=>4, 'slug'=>'basic', 'name_fb'=>'Basic', 'color'=>'1',
        'features'=>['1 Website','5 GB NVMe SSD Storage','50 GB Premium Bandwidth','1 Core CPU','1 GB RAM','25 Entry Processes','100,000 Inodes','5 Email Accounts','1 MySQL Database','Free SSL Certificate','LiteSpeed Web Server','LSCache Optimization','Weekly Backup','Free Website Migration','cPanel Access','99.9% Uptime Guarantee'],
        'order'=>'https://my.resellnom.com/cart.php?a=add&pid=4',
        'storage'=>'5 GB NVMe','bandwidth'=>'50 GB','websites'=>'1','emails'=>'5','mysql'=>'1','backup'=>'Weekly','cpu'=>'1 Core','ram'=>'1 GB','entry'=>'25','inodes'=>'100K','ip'=>'Shared','security'=>'—','support'=>'Standard',
    ],
    [
        'pid'=>6, 'slug'=>'growth', 'name_fb'=>'Growth', 'color'=>'2',
        'features'=>['5 Websites','15 GB NVMe SSD Storage','Unlimited Bandwidth (Fair Usage)','1 Core CPU','1 GB RAM','50 Entry Processes','250,000 Inodes','20 Email Accounts','10 MySQL Databases','Free SSL Certificate','LiteSpeed Web Server','LSCache Optimization','Daily Backup','Softaculous Auto Installer','Free Website Migration','cPanel Access','99.9% Uptime Guarantee'],
        'order'=>'https://my.resellnom.com/cart.php?a=add&pid=6',
        'storage'=>'15 GB NVMe','bandwidth'=>'Unlimited','websites'=>'5','emails'=>'20','mysql'=>'10','backup'=>'Daily','cpu'=>'1 Core','ram'=>'1 GB','entry'=>'50','inodes'=>'250K','ip'=>'Shared','security'=>'Basic','support'=>'Priority',
    ],
    [
        'pid'=>9, 'slug'=>'pro', 'name_fb'=>'Pro', 'color'=>'3',
        'features'=>['Unlimited Websites','50 GB NVMe SSD Storage','Unlimited Bandwidth (Fair Usage)','2 Core CPU','2 GB RAM','100 Entry Processes','500,000 Inodes','Unlimited Email Accounts','Unlimited MySQL Databases','Free SSL Certificate','LiteSpeed Enterprise','Imunify360 Security','Daily Backup','Priority Support','Softaculous Auto Installer','Free Website Migration','cPanel Access','99.9% Uptime Guarantee'],
        'order'=>'https://my.resellnom.com/cart.php?a=add&pid=9',
        'storage'=>'50 GB NVMe','bandwidth'=>'Unlimited','websites'=>'Unlimited','emails'=>'Unlimited','mysql'=>'Unlimited','backup'=>'Daily','cpu'=>'2 Core','ram'=>'2 GB','entry'=>'100','inodes'=>'500K','ip'=>'Free Dedicated IP','security'=>'Imunify360','support'=>'Priority 24/7',
    ],
];

$fallback_prices = [4=>99.00, 6=>299.00, 9=>999.00];

// ── WHMCS fetch helper ──
// productsinfo.php returns: document.write('৳127.00');
// Extract the value from inside the single quotes
function whmcsExtract($raw) {
    if (!$raw) return null;
    if (preg_match("/document\.write\('(.+?)'\)/u", $raw, $m)) {
        return trim($m[1]);
    }
    // Fallback: plain text
    $plain = trim(strip_tags($raw));
    return preg_match('/[0-9]/', $plain) ? $plain : null;
}

function whmcsFetch($whmcs, $pid, $get, $billingcycle = 'monthly', $currency = 2) {
    $ckey  = $get . '_' . $billingcycle . '_' . $currency;
    $cache = sys_get_temp_dir() . '/rn_wh2_' . $pid . '_' . md5($ckey) . '.txt';
    if (file_exists($cache) && (time() - filemtime($cache)) < 1800) {
        $v = trim(file_get_contents($cache)); if ($v !== '') return $v;
    }
    $url = $whmcs . '/feeds/productsinfo.php?pid=' . $pid . '&get=' . $get;
    if ($get === 'price') $url .= '&billingcycle=' . $billingcycle . '&currency=' . $currency;
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 6,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT      => 'Mozilla/5.0',
    ]);
    $raw = curl_exec($ch); curl_close($ch);
    $val = whmcsExtract($raw);
    if ($val !== null) file_put_contents($cache, $val);
    return $val;
}

foreach ($plans as &$p) {
    // Name: document.write('Basic') → 'Basic'
    $p['name'] = whmcsFetch($whmcs, $p['pid'], 'name') ?: $p['name_fb'];

    // Monthly price: document.write('৳127.00') → '৳127.00'
    $raw_price = whmcsFetch($whmcs, $p['pid'], 'price', 'monthly');
    if ($raw_price) {
        $p['price_monthly'] = $raw_price;
        $num = (float) preg_replace('/[^0-9.]/', '', $raw_price);
    } else {
        $num = $fallback_prices[$p['pid']];
        $p['price_monthly'] = '৳' . number_format($num, 2);
    }

    // Annual price — use if configured (>0), else calculate 20% off monthly×12
    $raw_annual  = whmcsFetch($whmcs, $p['pid'], 'price', 'annually');
    $annual_num  = $raw_annual ? (float) preg_replace('/[^0-9.]/', '', $raw_annual) : 0;

    $p['price_bdt']    = $num;
    $p['price_yearly'] = ($annual_num > 0) ? $annual_num : round($num * 12 * 0.80);
}
unset($p);
$popular_pid=6;

// ── Language: Strict IP detection — BD IP = Bengali, VPN/proxy/other = English ──
// Detects real IP even behind Cloudflare or load balancers
$_rn_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
if (!empty($_SERVER['HTTP_CF_CONNECTING_IP']))       $_rn_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];   // Cloudflare real IP
elseif (!empty($_SERVER['HTTP_X_REAL_IP']))          $_rn_ip = $_SERVER['HTTP_X_REAL_IP'];           // Nginx proxy
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))    $_rn_ip = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]); // Generic proxy

$_rn_gc = sys_get_temp_dir() . '/rn_geo_' . md5($_rn_ip) . '.txt';
if (file_exists($_rn_gc) && (time() - filemtime($_rn_gc)) < 3600) {
    $_rn_cc = trim(file_get_contents($_rn_gc));
} elseif ($_rn_ip === '127.0.0.1' || $_rn_ip === '::1') {
    $_rn_cc = 'BD'; // localhost dev = treat as BD
} else {
    $_rn_ch = curl_init('https://ipapi.co/' . $_rn_ip . '/country/');
    curl_setopt_array($_rn_ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 3,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT      => 'Mozilla/5.0',
    ]);
    $_rn_cc = curl_exec($_rn_ch); curl_close($_rn_ch);
    $_rn_cc = (strlen($_rn_cc ?? '') === 2) ? strtoupper(trim($_rn_cc)) : 'OTHER';
    file_put_contents($_rn_gc, $_rn_cc);
}
// STRICT: only real BD IP gets Bengali — VPN/proxy/foreign IP gets English
$isBD = ($_rn_cc === 'BD');

// ── Bilingual FAQ ──
$faqs = $isBD ? [
    ['q'=>'ওয়েব হোস্টিং কী এবং এটি কীভাবে কাজ করে?',
     'a'=>'ওয়েব হোস্টিং হলো এমন একটি সেবা যেখানে আপনার ওয়েবসাইটের ফাইলগুলো একটি সার্ভারে সংরক্ষিত থাকে এবং ইন্টারনেটের মাধ্যমে যে কেউ সেটি দেখতে পারে। ResellNom-এর সার্ভার বাংলাদেশে অবস্থিত তাই লোকাল স্পিড অনেক বেশি।'],
    ['q'=>'নতুনদের জন্য কোন প্ল্যান সবচেয়ে ভালো?',
     'a'=>'নতুনদের জন্য আমাদের Basic প্ল্যান মাত্র ৳৯৯/মাস থেকে শুরু। এতে ৫ জিবি NVMe SSD স্টোরেজ, cPanel অ্যাক্সেস এবং LiteSpeed সার্ভার রয়েছে।'],
    ['q'=>'কি বিনামূল্যে ওয়েবসাইট মাইগ্রেশন করা যাবে?',
     'a'=>'হ্যাঁ! ResellNom সম্পূর্ণ বিনামূল্যে এবং জিরো ডাউনটাইমে ওয়েবসাইট মাইগ্রেশন করে দেয়।'],
    ['q'=>'WordPress কি এই হোস্টিংয়ে ভালো কাজ করবে?',
     'a'=>'অবশ্যই। আমাদের সব প্ল্যানে LiteSpeed + LSCache আছে যা WordPress-কে অনেক দ্রুত করে। Softaculous দিয়ে ১ ক্লিকে WordPress ইন্সটল করা যায়।'],
    ['q'=>'কি ডেইলি ব্যাকআপ পাওয়া যাবে?',
     'a'=>'Growth এবং Pro প্ল্যানে অটোমেটিক ডেইলি ব্যাকআপ আছে। Basic প্ল্যানে সাপ্তাহিক ব্যাকআপ পাওয়া যায়।'],
    ['q'=>'বাংলাদেশ থেকে কীভাবে পেমেন্ট করব?',
     'a'=>'bKash, Nagad, Rocket, ডেবিট/ক্রেডিট কার্ড সহ সব ধরনের লোকাল পেমেন্ট গ্রহণ করা হয়।'],
    ['q'=>'SSL সার্টিফিকেট কি ফ্রি?',
     'a'=>'হ্যাঁ! সব প্ল্যানে বিনামূল্যে Let\'s Encrypt SSL সার্টিফিকেট দেওয়া হয় যা অটো-রিনিউ হয়।'],
    ['q'=>'প্ল্যান আপগ্রেড করা যাবে কি?',
     'a'=>'হ্যাঁ, যেকোনো সময় my.resellnom.com থেকে প্ল্যান আপগ্রেড করা যাবে। কোনো ডাউনটাইম বা ডেটা লস ছাড়াই রিসোর্স বাড়বে।'],
] : [
    ['q'=>'What is web hosting and how does it work?',
     'a'=>'Web hosting stores your website\'s files on a server connected to the internet, making your site accessible to visitors worldwide. ResellNom uses LiteSpeed servers with NVMe SSD for maximum speed.'],
    ['q'=>'Which plan is best for beginners?',
     'a'=>'Our Basic Plan starting at just ৳99/month is ideal for beginners. It includes 5 GB NVMe SSD storage, cPanel access, LiteSpeed server, and free SSL certificate.'],
    ['q'=>'Do you offer free website migration?',
     'a'=>'Yes! ResellNom provides 100% free migration assistance with zero website downtime. Our team handles everything for you.'],
    ['q'=>'Is this hosting compatible with WordPress?',
     'a'=>'Absolutely. All plans are fully optimized for WordPress with LiteSpeed + LSCache. Install WordPress in 1 click via Softaculous.'],
    ['q'=>'Do you offer daily backups?',
     'a'=>'Growth and Pro plans include fully automated daily backups. The Basic plan includes weekly automated backups.'],
    ['q'=>'What payment methods are accepted?',
     'a'=>'We accept bKash, Nagad, Rocket, local and international debit/credit cards, and bank transfers.'],
    ['q'=>'Is SSL certificate free?',
     'a'=>'Yes! All plans include a free auto-renewing Let\'s Encrypt SSL certificate. Your site will be secured with HTTPS at no extra cost.'],
    ['q'=>'Can I upgrade my plan later?',
     'a'=>'Yes, upgrade anytime from my.resellnom.com with no downtime and no data loss. Resources scale instantly.'],
];

// ── Schema FAQ JSON-LD ──
$schema_faqs=[];
foreach($faqs as $f) $schema_faqs[]=['@type'=>'Question','name'=>$f['q'],'acceptedAnswer'=>['@type'=>'Answer','text'=>$f['a']]];
?>
<!doctype html>
<html class="no-js" lang="<?=$isBD?'bn':'en'?>">
<head>
<meta charset="utf-8">
<title>Web Hosting | cPanel | LiteSpeed | Free SSL | ResellNom</title>
<meta name="description" content="<?=$isBD?'ResellNom — সেরা ওয়েব হোস্টিং সার্ভিস। cPanel, LiteSpeed, Free SSL, Free Migration সহ মাত্র ৳99/মাস থেকে শুরু। ৯৯.৯% আপটাইম গ্যারান্টি।':'ResellNom — Premium web hosting with cPanel, LiteSpeed, Free SSL, and Free Migration from ৳99/mo. 99.9% uptime guaranteed.'?>">
<meta name="keywords" content="ওয়েব হোস্টিং, web hosting, cPanel hosting, LiteSpeed hosting, shared hosting, WordPress hosting, free SSL hosting, web hosting Dhaka, cheap hosting bd, bdix hosting, resellnom, হোস্টিং বাংলাদেশ, সস্তা হোস্টিং">
<meta name="author" content="ResellNom">
<meta name="robots" content="index, follow">
<link rel="canonical" href="https://resellnom.com/web-hosting.php">
<meta property="og:title" content="Web Hosting | cPanel + LiteSpeed | ResellNom">
<meta property="og:description" content="<?=$isBD?'cPanel, LiteSpeed, Free SSL সহ মাত্র ৳99/মাস থেকে শুরু।':'cPanel Hosting with LiteSpeed, Free SSL, Free Migration from ৳99/mo.'?>">
<meta property="og:type" content="website">
<meta property="og:url" content="https://resellnom.com/web-hosting.php">
<meta property="og:image" content="https://resellnom.com/img/bdix-vps-banner.jpg">
<meta property="og:site_name" content="ResellNom">
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"FAQPage","mainEntity":<?=json_encode($schema_faqs,JSON_UNESCAPED_UNICODE)?>}
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"Product","name":"Web Hosting","brand":{"@type":"Brand","name":"ResellNom"},"offers":{"@type":"AggregateOffer","lowPrice":"99","priceCurrency":"BDT","offerCount":"3"}}
</script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
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
<style>
/* Toggle */
.billing-toggle-wrap{display:flex;align-items:center;justify-content:center;gap:14px;margin-bottom:50px;}
.billing-toggle-wrap span{font-family:'Rubik',sans-serif;font-size:15px;font-weight:600;color:#213e6e;cursor:pointer;}
.billing-toggle-wrap span.active{color:#01adef;}
.billing-save-badge{background:linear-gradient(135deg,#01adef,#146af8);color:#fff;font-size:11px;font-weight:700;padding:2px 9px;border-radius:20px;}
.toggle-switch{position:relative;width:52px;height:28px;cursor:pointer;}
.toggle-switch input{display:none;}
.toggle-track{position:absolute;inset:0;background:#cdd5e0;border-radius:20px;transition:background .3s;}
.toggle-switch input:checked~.toggle-track{background:linear-gradient(135deg,#01adef,#146af8);}
.toggle-thumb{position:absolute;top:3px;left:3px;width:22px;height:22px;background:#fff;border-radius:50%;transition:transform .3s;box-shadow:0 2px 6px rgba(0,0,0,.15);}
.toggle-switch input:checked~.toggle-thumb{transform:translateX(24px);}
/* Currency */
.curr-page-bar{display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:16px;}
.curr-page-label{font-size:13px;color:#888;}
.curr-page-select{padding:5px 28px 5px 12px;border:2px solid #01adef;border-radius:24px;font-size:13px;font-weight:700;color:#01adef;background:#fff;cursor:pointer;outline:none;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='5'%3E%3Cpath d='M0 0l4 5 4-5z' fill='%2301adef'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;}
.curr-rate-info{font-size:11px;color:#aaa;}
/* Popular */
.popular-badge{position:absolute;top:16px;right:16px;background:linear-gradient(135deg,#01adef,#146af8);color:#fff;font-size:10px;font-weight:800;padding:3px 11px;border-radius:20px;text-transform:uppercase;z-index:5;}
.pricing-list ul li i{margin-right:8px;color:#01adef;}

/* ── Language Switcher ── */
.lang-switcher {
    display: inline-flex; align-items: center; gap: 0;
    border: 2px solid #01adef; border-radius: 30px; overflow: hidden;
    font-family: 'Rubik', sans-serif;
}
.lang-switcher a {
    padding: 6px 16px; font-size: 13px; font-weight: 700;
    color: #01adef; text-decoration: none;
    transition: background .2s, color .2s;
    display: flex; align-items: center; gap: 5px;
}
.lang-switcher a.active, .lang-switcher a:hover {
    background: linear-gradient(135deg, #01adef, #146af8);
    color: #fff !important; text-decoration: none;
}
.lang-switcher a + a { border-left: 1px solid #01adef; }
.lang-bar {
    display: flex; align-items: center; justify-content: center;
    gap: 12px; padding: 12px 0 0;
}
.lang-bar-label { font-size: 13px; color: #888; }
/* Yearly toggle */
.price-yearly{display:none;}
.yearly-mode .price-monthly{display:none;}
.yearly-mode .price-yearly{display:block;}
.price-per-note{font-size:12px;color:#aaa;margin-top:4px;}
/* Comparison table */
.compare-table{background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 4px 30px rgba(1,40,120,.07);}
.compare-table thead th{background:linear-gradient(135deg,#01adef,#146af8);color:#fff;font-family:'Rubik',sans-serif;font-size:13px;font-weight:700;padding:18px 18px;text-align:center;white-space:nowrap;vertical-align:middle;}
.compare-table thead th:first-child{text-align:left;}
.compare-table thead th.popular-col{background:#fff;color:#01adef;border-top:4px solid #01adef;padding:14px 18px;}
.compare-table thead th.popular-col .pop-name{font-size:15px;font-weight:800;color:#213e6e;display:block;margin-bottom:5px;}
.compare-table thead th.popular-col .pop-badge{display:inline-block;background:linear-gradient(135deg,#01adef,#146af8);color:#fff;font-size:10px;font-weight:800;padding:3px 10px;border-radius:20px;letter-spacing:.4px;}
.compare-table tbody td{padding:13px 18px;font-size:13px;color:#555;border-bottom:1px solid #f0f4ff;text-align:center;vertical-align:middle;}
.compare-table tbody td:first-child{text-align:left;font-weight:600;color:#213e6e;}
.compare-table tbody tr:last-child td{border-bottom:none;}
.compare-table tbody tr:hover td{background:#f7fbff;}
.compare-table .fa-check{color:#2acb35;font-size:15px;}
.compare-table .fa-times{color:#ddd;font-size:15px;}
.compare-table .popular-col{background:rgba(1,173,239,.04);}
.compare-table thead th.popular-col{background:#f0f8ff;}
/* Why cards */
.why-card{background:#fff;border-radius:14px;padding:28px 22px;text-align:center;height:100%;box-shadow:0 4px 20px rgba(1,40,120,.05);border-top:3px solid transparent;transition:transform .2s,box-shadow .2s,border-top-color .2s;}
.why-card:hover{transform:translateY(-4px);box-shadow:0 12px 36px rgba(1,173,239,.13);border-top-color:#01adef;}
.why-icon{width:58px;height:58px;border-radius:50%;background:linear-gradient(135deg,#e8f7ff,#d0ecff);display:flex;align-items:center;justify-content:center;font-size:22px;color:#01adef;margin:0 auto 14px;}
.why-card h5{font-family:'Rubik',sans-serif;color:#213e6e;font-size:15px;font-weight:700;margin-bottom:7px;}
.why-card p{font-size:13px;color:#777;line-height:1.7;margin:0;}
/* FAQ accordion style */
.faq-set a{display:flex;align-items:center;justify-content:space-between;}
/* ── Page Translator ── */
.lang-toggle-btn {
    position: fixed; bottom: 90px; right: 22px; z-index: 9999;
    background: linear-gradient(135deg,#01adef,#146af8);
    color: #fff; border: none; border-radius: 30px;
    padding: 10px 18px; font-size: 13px; font-weight: 700;
    font-family: 'Rubik',sans-serif; cursor: pointer;
    box-shadow: 0 6px 20px rgba(1,173,239,.4);
    display: flex; align-items: center; gap: 7px;
    transition: transform .2s, box-shadow .2s;
}
.lang-toggle-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(1,173,239,.5); }
.lang-toggle-btn .lang-flag { font-size: 16px; }

</style>
</head>
<body>
<button class="scroll-top scroll-to-target" data-target="html"><i class="fas fa-angle-up"></i></button>

<?php include "./inc/header.php"; ?>

<main>

<!-- Breadcrumb -->
<section class="breadcrumb-area breadcrumb-bg" style="background-image:url('img/bg/breadcrumb_bg.jpg');">
    <div class="container">
        <div class="row"><div class="col-12">
            <div class="breadcrumb-content text-center">
                <h2><?=$isBD?'ওয়েব হোস্টিং প্ল্যান':'Web Hosting Plans'?></h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/"><?=$isBD?'হোম':'Home'?></a></li>
                        <li class="breadcrumb-item active"><?=$isBD?'ওয়েব হোস্টিং':'Web Hosting'?></li>
                    </ol>
                </nav>
            </div>
        </div></div>
    </div>
    <div class="breadcrumb-shape alltuchtopdown"><img src="img/images/breadcrumb_roket.png" alt=""></div>
</section>

<!-- ═══ PRICING ═══ -->
<section class="pricing-area gray-bg position-relative pt-100 pb-70" id="pricing">
<div class="container">

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="section-title text-center mb-40">
                <span class="sub-title"><?=$isBD?'সেরা ওয়েব হোস্টিং সার্ভিস':'BEST WEB HOSTING SERVICE'?></span>
                <h2 class="title"><?=$isBD?'আপনার পছন্দের প্ল্যান বেছে নিন':'Choose Your Web Hosting Plan'?></h2>
                <p><?=$isBD?'cPanel · LiteSpeed · ফ্রি SSL · ফ্রি মাইগ্রেশন · ৯৯.৯% আপটাইম':'cPanel · LiteSpeed · Free SSL · Free Migration · 99.9% Uptime'?></p>
            </div>
        </div>
    </div>

    <!-- Currency Bar -->
    <div class="curr-page-bar">
        <span class="curr-page-label">💱 <?=$isBD?'মুদ্রা:':'Currency:'?></span>
        <select id="currSelect" class="curr-page-select">
            <option value="BDT">৳ BDT — <?=$isBD?'বাংলাদেশ':'Bangladesh'?></option>
            <option value="USD">$ USD — <?=$isBD?'আন্তর্জাতিক':'International'?></option>
            <option value="EUR">€ EUR</option>
            <option value="GBP">£ GBP</option>
            <option value="INR">₹ INR</option>
            <option value="SAR">﷼ SAR</option>
            <option value="AED">د.إ AED</option>
            <option value="SGD">S$ SGD</option>
        </select>
        <span class="curr-rate-info" id="currRateInfo"><?=$isBD?'লোকেশন শনাক্ত করা হচ্ছে...':'Detecting location...'?></span>
    </div>

    <!-- Billing Toggle -->
    <div class="billing-toggle-wrap">
        <span class="active" id="lbl-monthly"><?=$isBD?'মাসিক':'Monthly'?></span>
        <label class="toggle-switch">
            <input type="checkbox" id="billingToggle">
            <div class="toggle-track"></div>
            <div class="toggle-thumb"></div>
        </label>
        <span id="lbl-yearly"><?=$isBD?'বার্ষিক':'Yearly'?></span>
        <span class="billing-save-badge"><?=$isBD?'২০% সাশ্রয়':'Save 20%'?></span>
    </div>

    <!-- Pricing Cards -->
    <div class="row pricing-box-wrap justify-content-center" id="pricingWrap">
    <?php foreach($plans as $p):
        $is_popular=($p['pid']===$popular_pid);
    ?>
    <div class="col-lg-4 col-md-6 col-sm-9">
        <div class="pricing-box mb-30" style="<?=$is_popular?'border:2px solid #01adef;':''?>">
            <?php if($is_popular): ?>
            <div class="popular-badge">⭐ <?=$isBD?'সবচেয়ে জনপ্রিয়':'Most Popular'?></div>
            <?php endif; ?>
            <div class="pricing-head">
                <h6><?=htmlspecialchars($p['name'])?></h6>
                <div class="pricing-icon services-icon"><i class="flaticon-hosting"></i></div>
            </div>
            <div class="pricing-list mb-30">
                <h5><?=$isBD?'ফিচারসমূহ':'Features'?></h5>
                <ul>
                    <?php foreach($p['features'] as $f): ?>
                    <li><i class="fas fa-check"></i><?=htmlspecialchars($f)?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <!-- Monthly -->
            <div class="price mb-20 price-monthly">
                <h2 class="geo-price" data-bdt="<?=$p['price_bdt']?>"><?=$p['price_monthly']?></h2>
                <p class="price-per-note"><?=$isBD?'প্রতি মাসে':'per month'?></p>
            </div>
            <!-- Yearly -->
            <div class="price mb-20 price-yearly">
                <h2 class="geo-price-yr" data-bdt-yr="<?=$p['price_yearly']?>">
                    ৳<?=number_format($p['price_yearly'])?>
                    <?php if($p['price_yearly_from_whmcs']): ?>
                    <small style="font-size:13px;color:#aaa;">/yr</small>
                    <?php else: ?>
                    <small style="font-size:13px;color:#2acb35;">(-20%)</small>
                    <?php endif; ?>
                </h2>
                <p class="price-per-note"><?=$isBD?'প্রতি বছরে':'per year'?></p>
            </div>
            <div class="pricing-btn">
                <a href="<?=$p['order']?>" class="btn"><span>+</span> <?=$isBD?'শুরু করুন':'Get Started'?></a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    </div>

    <!-- ═══ COMPARISON TABLE ═══ -->
    <div class="row justify-content-center mt-60">
        <div class="col-12">
            <div class="section-title text-center mb-40">
                <span class="sub-title"><?=$isBD?'বিস্তারিত তুলনা':'FULL COMPARISON'?></span>
                <h2 class="title"><?=$isBD?'প্ল্যান তুলনা':'Plan Comparison'?></h2>
            </div>
        </div>
        <div class="col-lg-10">
            <div class="table-responsive compare-table">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th><?=$isBD?'ফিচার':'Feature'?></th>
                        <?php foreach($plans as $p):
                            $is_pop = ($p['pid']===$popular_pid); ?>
                        <th class="<?=$is_pop?'popular-col':''?>">
                            <?php if($is_pop): ?>
                            <span class="pop-name"><?=htmlspecialchars($p['name'])?></span>
                            <span class="pop-badge">⭐ <?=$isBD?'সবচেয়ে জনপ্রিয়':'Most Popular'?></span>
                            <?php else: ?>
                            <?=htmlspecialchars($p['name'])?>
                            <?php endif; ?>
                        </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                <?php
                $rows=$isBD?[
                    ['স্টোরেজ',       'storage'],
                    ['ব্যান্ডউইথ',    'bandwidth'],
                    ['ওয়েবসাইট',     'websites'],
                    ['ইমেইল',         'emails'],
                    ['MySQL DB',       'mysql'],
                    ['CPU',            'cpu'],
                    ['RAM',            'ram'],
                    ['এন্ট্রি প্রসেস','entry'],
                    ['Inodes',         'inodes'],
                    ['ব্যাকআপ',       'backup'],
                    ['IP ঠিকানা',     'ip'],
                    ['নিরাপত্তা',     'security'],
                    ['সাপোর্ট',       'support'],
                ]:[
                    ['Storage',        'storage'],
                    ['Bandwidth',      'bandwidth'],
                    ['Websites',       'websites'],
                    ['Email Accounts', 'emails'],
                    ['MySQL Databases','mysql'],
                    ['CPU',            'cpu'],
                    ['RAM',            'ram'],
                    ['Entry Processes','entry'],
                    ['Inodes',         'inodes'],
                    ['Backup',         'backup'],
                    ['IP Address',     'ip'],
                    ['Security',       'security'],
                    ['Support',        'support'],
                ];
                $bool_rows=$isBD?[
                    ['ফ্রি SSL',           true,true,true],
                    ['LiteSpeed সার্ভার',  true,true,true],
                    ['LSCache',            true,true,true],
                    ['cPanel',             true,true,true],
                    ['Softaculous',        true,true,true],
                    ['ফ্রি মাইগ্রেশন',    true,true,true],
                    ['Imunify360',         true,true,true],
                    ['Dedicated IP',       false,false,true],
                    ['প্রায়োরিটি সাপোর্ট',false,true,true],
                ]:[
                    ['Free SSL',           true,true,true],
                    ['LiteSpeed Server',   true,true,true],
                    ['LSCache',            true,true,true],
                    ['cPanel Access',      true,true,true],
                    ['Softaculous',        true,true,true],
                    ['Free Migration',     true,true,true],
                    ['Wp Toolkit',         true,true,true],
                    ['Dedicated IP',       false,false,false],
                    ['Priority Support',   false,true,true],
                ];
                foreach($rows as $r):?>
                <tr>
                    <td><?=$r[0]?></td>
                    <?php foreach($plans as $p): ?>
                    <td class="<?=$p['pid']===$popular_pid?'popular-col':''?>"><?=htmlspecialchars($p[$r[1]]??'—')?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach;
                foreach($bool_rows as $br):?>
                <tr>
                    <td><?=$br[0]?></td>
                    <?php foreach([1,2,3] as $ci): ?>
                    <td class="<?=$plans[$ci-1]['pid']===$popular_pid?'popular-col':''?>">
                        <i class="fas <?=$br[$ci]?'fa-check':'fa-times'?>"></i>
                    </td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>

</div>
</section>

<!-- ═══ WHY CHOOSE US ═══ -->
<section class="pt-80 pb-80 why-section">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="section-title text-center mb-60">
                <span class="sub-title"><?=$isBD?'আমাদের বিশেষত্ব':'WHY CHOOSE RESELLNOM'?></span>
                <h2 class="title"><?=$isBD?'কেন ResellNom বেছে নেবেন?':'What Makes Our Hosting Special?'?></h2>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
    <?php
    $why=$isBD?[
        ['fas fa-bolt',       'LiteSpeed সার্ভার',       'Apache-এর চেয়ে ৯ গুণ দ্রুত। LSCache দিয়ে আপনার সাইট রকেটের মতো লোড হবে।'],
        ['fas fa-shield-alt', 'Imunify360 সুরক্ষা',     'Pro প্ল্যানে Imunify360 সিকিউরিটি দেওয়া আছে যা আপনার সাইটকে ম্যালওয়্যার থেকে রক্ষা করে।'],
        ['fas fa-database',   'NVMe SSD স্টোরেজ',       'সাধারণ HDD-এর চেয়ে ১০ গুণ দ্রুত NVMe SSD ব্যবহার করা হয়।'],
        ['fas fa-sync-alt',   'অটো ব্যাকআপ',            'Growth ও Pro প্ল্যানে প্রতিদিন অটোমেটিক ব্যাকআপ নেওয়া হয়।'],
        ['fas fa-headset',    '২৪/৭ সাপোর্ট',           'আমাদের বাংলাদেশী সাপোর্ট টিম সার্বক্ষণিক আপনার পাশে আছে।'],
        ['fas fa-tachometer-alt','৯৯.৯% আপটাইম',       'আমরা ৯৯.৯% আপটাইম গ্যারান্টি দিচ্ছি। আপনার সাইট সবসময় অনলাইন থাকবে।'],
    ]:[
        ['fas fa-bolt',       'LiteSpeed Server',        'Up to 9x faster than Apache with built-in LSCache for blazing WordPress performance.'],
        ['fas fa-shield-alt', 'Imunify360 Security',     'Pro plan includes Imunify360 — real-time malware scanning and automatic threat removal.'],
        ['fas fa-database',   'NVMe SSD Storage',        '10x faster than traditional HDD. Your files load instantly with NVMe solid-state drives.'],
        ['fas fa-sync-alt',   'Automated Backups',       'Growth and Pro plans include daily automated backups so your data is always safe.'],
        ['fas fa-headset',    '24/7 Expert Support',     'Our support team is available around the clock via live chat, ticket, and phone.'],
        ['fas fa-tachometer-alt','99.9% Uptime SLA',     'We guarantee 99.9% uptime with redundant infrastructure and proactive monitoring.'],
    ];
    foreach($why as $w):?>
    <div class="col-lg-4 col-md-6 mb-30">
        <div class="why-card">
            <div class="why-icon"><i class="<?=$w[0]?>"></i></div>
            <h5><?=$w[1]?></h5>
            <p><?=$w[2]?></p>
        </div>
    </div>
    <?php endforeach;?>
    </div>
</div>
</section>

<!-- ═══ FAQ ═══ -->
<section class="faq-area gray-bg pt-100 pb-100 faq-section">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="section-title text-center mb-60">
                <span class="sub-title"><?=$isBD?'সচরাচর জিজ্ঞাসা':'FREQUENTLY ASKED QUESTIONS'?></span>
                <h2 class="title"><?=$isBD?'প্রায়ই জিজ্ঞেস করা প্রশ্নসমূহ':'Common Questions About Web Hosting'?></h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="faq-wrap">
            <?php foreach(array_slice($faqs,0,4) as $i=>$f):?>
            <div class="faq-set">
                <a <?=$i===0?'class="active"':''?> href="#">
                    <?=$f['q']?><i class="fas fa-angle-<?=$i===0?'down':'up'?>"></i>
                </a>
                <div class="content" <?=$i===0?'style="display:block;"':''?>><p><?=$f['a']?></p></div>
            </div>
            <?php endforeach;?>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="faq-wrap">
            <?php foreach(array_slice($faqs,4) as $i=>$f):?>
            <div class="faq-set">
                <a href="#"><?=$f['q']?><i class="fas fa-angle-up"></i></a>
                <div class="content"><p><?=$f['a']?></p></div>
            </div>
            <?php endforeach;?>
            </div>
        </div>
    </div>
</div>
</section>

<!-- Brand -->
<div class="brand-area gradient-bg">
<div class="container"><div class="row brand-active">
    <div class="col-xl-2"><div class="brand-item text-center"><img src="img/brand/cpanel-whm.png" alt="cPanel"></div></div>
    <div class="col-xl-2"><div class="brand-item text-center"><img src="img/brand/plesk.png" alt="Plesk"></div></div>
    <div class="col-xl-2"><div class="brand-item text-center"><img src="img/brand/directadmin.png" alt="DirectAdmin"></div></div>
    <div class="col-xl-2"><div class="brand-item text-center"><img src="img/brand/cloudlinux.png" alt="CloudLinux"></div></div>
    <div class="col-xl-2"><div class="brand-item text-center"><img src="img/brand/ubuntu.png" alt="Ubuntu"></div></div>
    <div class="col-xl-2"><div class="brand-item text-center"><img src="img/brand/windowse.png" alt="Windows"></div></div>
</div></div>
</div>

</main>
<?php include "./inc/footer.php"; ?>

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

<script>
(function(){
var isBD=<?=$isBD?'true':'false'?>;
var toggle=document.getElementById('billingToggle');
var wrap=document.getElementById('pricingWrap');
var lblMo=document.getElementById('lbl-monthly');
var lblYr=document.getElementById('lbl-yearly');
var currentCurr='BDT';
var rates={BDT:1};
var ratesFetched=false;

// Billing toggle
toggle.addEventListener('change',function(){
    if(this.checked){wrap.classList.add('yearly-mode');lblMo.classList.remove('active');lblYr.classList.add('active');}
    else{wrap.classList.remove('yearly-mode');lblYr.classList.remove('active');lblMo.classList.add('active');}
    applyConversion(currentCurr,rates[currentCurr]||1);
});

// Currency
var symbols={BDT:'৳',USD:'$',EUR:'€',GBP:'£',INR:'₹',SAR:'﷼',AED:'د.إ',SGD:'S$'};
function fmt(sym,v){return v>=1000?sym+Math.round(v).toLocaleString():v>=10?sym+Math.round(v):sym+v.toFixed(2);}

function applyConversion(curr,rate){
    currentCurr=curr;
    var sym=symbols[curr]||curr+' ';
    document.querySelectorAll('.geo-price').forEach(function(el){
        var bdt=parseFloat(el.getAttribute('data-bdt'));
        if(!isNaN(bdt))el.innerHTML=fmt(sym,bdt*rate)+'<span style="font-size:16px;color:#aaa;">/mo</span>';
    });
    document.querySelectorAll('.geo-price-yr').forEach(function(el){
        var bdt=parseFloat(el.getAttribute('data-bdt-yr'));
        if(!isNaN(bdt))el.innerHTML=fmt(sym,bdt*rate)+'<span style="font-size:16px;color:#aaa;">/yr</span>';
    });
    var info=document.getElementById('currRateInfo');
    if(info)info.textContent=curr==='BDT'?(isBD?'বেস মুদ্রা — বাংলাদেশ':'Base currency — Bangladesh'):
        '1 BDT = '+rate.toFixed(4)+' '+curr+' (live)';
    ['currSelect','headerCurrSelect'].forEach(function(id){var el=document.getElementById(id);if(el&&el.value!==curr)el.value=curr;});
    try{localStorage.setItem('rn_currency',curr);}catch(e){}
}

function fetchRates(curr){
    if(curr==='BDT'){applyConversion('BDT',1);return;}
    fetch('https://api.exchangerate-api.com/v4/latest/BDT')
        .then(function(r){return r.json();})
        .then(function(d){rates=d.rates||{BDT:1};rates.BDT=1;ratesFetched=true;applyConversion(curr,rates[curr]||1);})
        .catch(function(){var fb={BDT:1,USD:0.0091,EUR:0.0084,GBP:0.0072,INR:0.76,SAR:0.034,AED:0.033,SGD:0.012};rates=fb;applyConversion(curr,fb[curr]||1);});
}

['currSelect','headerCurrSelect'].forEach(function(id){
    var el=document.getElementById(id);
    if(el)el.addEventListener('change',function(){ratesFetched?applyConversion(this.value,rates[this.value]||1):fetchRates(this.value);});
});

// Init
var saved=null;try{saved=localStorage.getItem('rn_currency');}catch(e){}
if(saved){['currSelect','headerCurrSelect'].forEach(function(id){var el=document.getElementById(id);if(el)el.value=saved;});fetchRates(saved);}
else{
    fetch('https://ipapi.co/json/')
        .then(function(r){return r.json();})
        .then(function(d){
            var c=d.currency||'BDT';
            var sel=document.getElementById('currSelect');var valid=false;
            if(sel)for(var i=0;i<sel.options.length;i++)if(sel.options[i].value===c){valid=true;break;}
            var use=valid?c:'BDT';
            ['currSelect','headerCurrSelect'].forEach(function(id){var el=document.getElementById(id);if(el)el.value=use;});
            fetchRates(use);
        })
        .catch(function(){fetchRates(isBD?'BDT':'USD');});
}
})();
</script>

<!-- ── FLOATING LANGUAGE TOGGLE BUTTON ── -->
<button class="lang-toggle-btn" id="langToggleBtn" onclick="toggleLang()">
    <span class="lang-flag" id="langFlag">🇧🇩</span>
    <span id="langBtnText">বাংলা</span>
</button>

<script>
// ══════════════════════════════════════════════════════
// PAGE TRANSLATOR — EN ↔ BN
// Uses data-en / data-bn attributes on all text nodes
// ══════════════════════════════════════════════════════
var currentLang = '<?=$isBD?"bn":"en"?>';

// All translatable strings: [selector, en text, bn text]
var translations = [
    // Breadcrumb
    ['.breadcrumb-content h2',          'Web Hosting Plans',                        'ওয়েব হোস্টিং প্ল্যান'],
    ['.breadcrumb-item.active',         'Web Hosting',                              'ওয়েব হোস্টিং'],

    // Section titles
    ['#pricing .sub-title',             'BEST WEB HOSTING SERVICE',                 'সেরা ওয়েব হোস্টিং সার্ভিস'],
    ['#pricing .section-title .title',  'Choose Your Web Hosting Plan',             'আপনার পছন্দের প্ল্যান বেছে নিন'],
    ['#pricing .section-title p',       'cPanel · LiteSpeed · Free SSL · Free Migration · 99.9% Uptime',
                                        'cPanel · LiteSpeed · ফ্রি SSL · ফ্রি মাইগ্রেশন · ৯৯.৯% আপটাইম'],

    // Currency label
    ['.curr-page-label',                '💱 Currency:',                             '💱 মুদ্রা:'],
    ['#lbl-monthly',                    'Monthly',                                  'মাসিক'],
    ['#lbl-yearly',                     'Yearly',                                   'বার্ষিক'],
    ['.billing-save-badge',             'Save 20%',                                 '২০% সাশ্রয়'],

    // Popular badge
    ['.popular-badge',                  '⭐ Most Popular',                          '⭐ সবচেয়ে জনপ্রিয়'],

    // Get started buttons
    ['#pricingWrap .pricing-btn a',     '+ Get Started',                            '+ শুরু করুন'],

    // Compare section
    ['#pricing .row.mt-60 .sub-title',  'FULL COMPARISON',                          'বিস্তারিত তুলনা'],
    ['#pricing .row.mt-60 .title',      'Plan Comparison',                          'প্ল্যান তুলনা'],

    // Why section
    ['.why-section .sub-title',         'WHY CHOOSE RESELLNOM',                     'আমাদের বিশেষত্ব'],
    ['.why-section .title',             "What Makes Our Hosting Special?",          'কেন ResellNom বেছে নেবেন?'],

    // FAQ section
    ['.faq-section .sub-title',         'FREQUENTLY ASKED QUESTIONS',               'সচরাচর জিজ্ঞাসা'],
    ['.faq-section .title',             'Common Questions About Web Hosting',        'প্রায়ই জিজ্ঞেস করা প্রশ্নসমূহ'],
];

// FAQ translations
var faqEN = [
    ["What is web hosting and how does it work?",
     "Web hosting stores your website's files on a server connected to the internet, making your site accessible to visitors worldwide. ResellNom uses LiteSpeed servers with NVMe SSD for maximum speed."],
    ["Which plan is best for beginners?",
     "Our Basic Plan starting at just ৳99/month is ideal for beginners. It includes 5 GB NVMe SSD storage, cPanel access, LiteSpeed server, and free SSL certificate."],
    ["Do you offer free website migration?",
     "Yes! ResellNom provides 100% free migration assistance with zero website downtime. Our team handles everything for you."],
    ["Is this hosting compatible with WordPress?",
     "Absolutely. All plans are fully optimized for WordPress with LiteSpeed + LSCache. Install WordPress in 1 click via Softaculous."],
    ["Do you offer daily backups?",
     "Growth and Pro plans include fully automated daily backups. The Basic plan includes weekly automated backups."],
    ["What payment methods are accepted?",
     "We accept bKash, Nagad, Rocket, local and international debit/credit cards, and bank transfers."],
    ["Is SSL certificate free?",
     "Yes! All plans include a free auto-renewing Let's Encrypt SSL certificate. Your site will be secured with HTTPS at no extra cost."],
    ["Can I upgrade my plan later?",
     "Yes, upgrade anytime from my.resellnom.com with no downtime and no data loss. Resources scale instantly."],
];
var faqBN = [
    ["ওয়েব হোস্টিং কী এবং এটি কীভাবে কাজ করে?",
     "ওয়েব হোস্টিং হলো এমন একটি সেবা যেখানে আপনার ওয়েবসাইটের ফাইলগুলো একটি সার্ভারে সংরক্ষিত থাকে। ResellNom-এর সার্ভার বাংলাদেশে অবস্থিত তাই লোকাল স্পিড অনেক বেশি।"],
    ["নতুনদের জন্য কোন প্ল্যান সবচেয়ে ভালো?",
     "নতুনদের জন্য আমাদের Basic প্ল্যান মাত্র ৳৯৯/মাস থেকে শুরু। এতে ৫ জিবি NVMe SSD স্টোরেজ, cPanel অ্যাক্সেস এবং LiteSpeed সার্ভার রয়েছে।"],
    ["কি বিনামূল্যে ওয়েবসাইট মাইগ্রেশন করা যাবে?",
     "হ্যাঁ! ResellNom সম্পূর্ণ বিনামূল্যে এবং জিরো ডাউনটাইমে ওয়েবসাইট মাইগ্রেশন করে দেয়।"],
    ["WordPress কি এই হোস্টিংয়ে ভালো কাজ করবে?",
     "অবশ্যই। আমাদের সব প্ল্যানে LiteSpeed + LSCache আছে যা WordPress-কে অনেক দ্রুত করে।"],
    ["কি ডেইলি ব্যাকআপ পাওয়া যাবে?",
     "Growth এবং Pro প্ল্যানে অটোমেটিক ডেইলি ব্যাকআপ আছে। Basic প্ল্যানে সাপ্তাহিক ব্যাকআপ পাওয়া যায়।"],
    ["বাংলাদেশ থেকে কীভাবে পেমেন্ট করব?",
     "bKash, Nagad, Rocket, ডেবিট/ক্রেডিট কার্ড সহ সব ধরনের লোকাল পেমেন্ট গ্রহণ করা হয়।"],
    ["SSL সার্টিফিকেট কি ফ্রি?",
     "হ্যাঁ! সব প্ল্যানে বিনামূল্যে Let's Encrypt SSL সার্টিফিকেট দেওয়া হয় যা অটো-রিনিউ হয়।"],
    ["প্ল্যান আপগ্রেড করা যাবে কি?",
     "হ্যাঁ, যেকোনো সময় my.resellnom.com থেকে প্ল্যান আপগ্রেড করা যাবে। কোনো ডাউনটাইম ছাড়াই।"],
];

// Why section translations
var whyEN = [
    ["LiteSpeed Server",       "Up to 9x faster than Apache with built-in LSCache for blazing WordPress performance."],
    ["Imunify360 Security",    "Pro plan includes Imunify360 — real-time malware scanning and automatic threat removal."],
    ["NVMe SSD Storage",       "10x faster than traditional HDD. Your files load instantly with NVMe solid-state drives."],
    ["Automated Backups",      "Growth and Pro plans include daily automated backups so your data is always safe."],
    ["24/7 Expert Support",    "Our support team is available around the clock via live chat, ticket, and phone."],
    ["99.9% Uptime SLA",       "We guarantee 99.9% uptime with redundant infrastructure and proactive monitoring."],
];
var whyBN = [
    ["LiteSpeed সার্ভার",      "Apache-এর চেয়ে ৯ গুণ দ্রুত। LSCache দিয়ে আপনার সাইট রকেটের মতো লোড হবে।"],
    ["Imunify360 সুরক্ষা",    "Pro প্ল্যানে Imunify360 সিকিউরিটি দেওয়া আছে যা আপনার সাইটকে ম্যালওয়্যার থেকে রক্ষা করে।"],
    ["NVMe SSD স্টোরেজ",      "সাধারণ HDD-এর চেয়ে ১০ গুণ দ্রুত NVMe SSD ব্যবহার করা হয়।"],
    ["অটো ব্যাকআপ",           "Growth ও Pro প্ল্যানে প্রতিদিন অটোমেটিক ব্যাকআপ নেওয়া হয়।"],
    ["২৪/৭ সাপোর্ট",          "আমাদের বাংলাদেশী সাপোর্ট টিম সার্বক্ষণিক আপনার পাশে আছে।"],
    ["৯৯.৯% আপটাইম",          "আমরা ৯৯.৯% আপটাইম গ্যারান্টি দিচ্ছি। আপনার সাইট সবসময় অনলাইন থাকবে।"],
];

function toggleLang() {
    currentLang = currentLang === 'en' ? 'bn' : 'en';
    applyLang(currentLang);
    try { localStorage.setItem('rn_lang', currentLang); } catch(e) {}
}

function applyLang(lang) {
    var isBn = (lang === 'bn');

    // Update button
    document.getElementById('langFlag').textContent    = isBn ? '🇬🇧' : '🇧🇩';
    document.getElementById('langBtnText').textContent = isBn ? 'English' : 'বাংলা';

    // Apply simple selector translations
    translations.forEach(function(t) {
        var els = document.querySelectorAll(t[0]);
        els.forEach(function(el) { el.textContent = isBn ? t[2] : t[1]; });
    });

    // Apply FAQ translations
    var faqSets = document.querySelectorAll('.faq-wrap .faq-set');
    var faqData = isBn ? faqBN : faqEN;
    faqSets.forEach(function(set, i) {
        if (!faqData[i]) return;
        var aTag = set.querySelector('a');
        var pTag = set.querySelector('p');
        var icon = aTag ? aTag.querySelector('i') : null;
        if (aTag) {
            aTag.textContent = faqData[i][0];
            if (icon) aTag.appendChild(icon); // re-attach icon
        }
        if (pTag) pTag.textContent = faqData[i][1];
    });

    // Apply Why section translations
    var whyCards = document.querySelectorAll('.why-card');
    var whyData  = isBn ? whyBN : whyEN;
    whyCards.forEach(function(card, i) {
        if (!whyData[i]) return;
        var h5 = card.querySelector('h5');
        var p  = card.querySelector('p');
        if (h5) h5.textContent = whyData[i][0];
        if (p)  p.textContent  = whyData[i][1];
    });

    // Compare table headers (feature column)
    var compareRowsEN = ['Storage','Bandwidth','Websites','Email Accounts','MySQL Databases','CPU','RAM','Entry Processes','Inodes','Backup','IP Address','Security','Support','Free SSL','LiteSpeed Server','LSCache','cPanel Access','Softaculous','Free Migration','Imunify360','Dedicated IP','Priority Support'];
    var compareRowsBN = ['স্টোরেজ','ব্যান্ডউইথ','ওয়েবসাইট','ইমেইল','MySQL DB','CPU','RAM','এন্ট্রি প্রসেস','Inodes','ব্যাকআপ','IP ঠিকানা','নিরাপত্তা','সাপোর্ট','ফ্রি SSL','LiteSpeed সার্ভার','LSCache','cPanel','Softaculous','ফ্রি মাইগ্রেশন','Imunify360','Dedicated IP','প্রায়োরিটি সাপোর্ট'];
    var compareData = isBn ? compareRowsBN : compareRowsEN;
    document.querySelectorAll('.compare-table tbody tr').forEach(function(tr, i) {
        var firstTd = tr.querySelector('td:first-child');
        if (firstTd && compareData[i]) firstTd.textContent = compareData[i];
    });

    // Compare table heading
    var compareTh = document.querySelector('.compare-table thead th:first-child');
    if (compareTh) compareTh.textContent = isBn ? 'ফিচার' : 'Feature';

    // Price notes
    document.querySelectorAll('.price-per-note').forEach(function(el) {
        if (el.innerHTML.indexOf('20%') !== -1 || el.innerHTML.indexOf('২০%') !== -1) {
            el.innerHTML = isBn
                ? 'প্রতি বছরে <strong style="color:#2acb35;">(২০% ছাড়)</strong>'
                : 'per year <strong style="color:#2acb35;">20% off</strong>';
        } else {
            el.textContent = isBn ? 'প্রতি মাসে' : 'per month';
        }
    });

    // Update html lang attr
    document.documentElement.lang = isBn ? 'bn' : 'en';

    currentLang = lang;
}

// Init on load
window.addEventListener('DOMContentLoaded', function() {
    var saved = null;
    try { saved = localStorage.getItem('rn_lang'); } catch(e) {}
    if (saved && saved !== currentLang) {
        applyLang(saved);
    } else {
        // Show Bengali button for BD visitors, English button otherwise
        var isBDVisitor = (currentLang === 'bn');
        document.getElementById('langFlag').textContent    = isBDVisitor ? '🇬🇧' : '🇧🇩';
        document.getElementById('langBtnText').textContent = isBDVisitor ? 'English' : 'বাংলা';
    }
});
</script>

</body>
</html>