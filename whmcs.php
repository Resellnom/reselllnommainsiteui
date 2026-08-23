<?php
// ═══════════════════════════════════════════════════════════
// WHMCS LICENSE PAGE — ResellNom
// PIDs: 2 (Plus No Brand), 3 (Plus Branding), 1 (Pro No Brand)
// ═══════════════════════════════════════════════════════════
$whmcs = 'https://my.resellnom.com';

function whmcsExtract($raw) {
    if (!$raw) return null;
    if (preg_match("/document\.write\('(.+?)'\)/u", $raw, $m)) return trim($m[1]);
    $plain = trim(strip_tags($raw));
    return preg_match('/[0-9]/', $plain) ? $plain : null;
}
function whmcsFetch($whmcs, $pid, $get, $billingcycle = 'monthly', $currency = 1) {
    $cache = sys_get_temp_dir() . '/rn_lic_' . $pid . '_' . md5($get.$billingcycle.$currency) . '.txt';
    if (file_exists($cache) && (time() - filemtime($cache)) < 1800) {
        $v = trim(file_get_contents($cache)); if ($v !== '') return $v;
    }
    $url = $whmcs . '/feeds/productsinfo.php?pid=' . $pid . '&get=' . $get;
    if ($get === 'price') $url .= '&billingcycle=' . $billingcycle . '&currency=' . $currency;
    $ch = curl_init($url);
    curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>6,CURLOPT_SSL_VERIFYPEER=>false,CURLOPT_USERAGENT=>'Mozilla/5.0']);
    $raw = curl_exec($ch); curl_close($ch);
    $val = whmcsExtract($raw);
    if ($val !== null) file_put_contents($cache, $val);
    return $val;
}

$plans = [
    [
        'pid'      => 2,
        'name_fb'  => 'WHMCS Plus — No Branding',
        'clients'  => '250',
        'branding' => false,
        'popular'  => false,
        'color'    => 'basic',
        'badge'    => '',
        'features' => [
            'Up to 250 Active Clients',
            'No Branding (WHMCS line hidden)',
            'Email Support',
            'Host on Your Own Server',
            'All Core WHMCS Modules',
            'Billing & Invoicing',
            'Client Management',
            'Domain Management',
            'Support Ticket System',
            'Automation Rules',
        ],
        'order' => 'https://my.resellnom.com/cart.php?a=add&pid=2',
    ],
    [
        'pid'      => 3,
        'name_fb'  => 'WHMCS Plus — With Branding',
        'clients'  => '250',
        'branding' => true,
        'popular'  => true,
        'color'    => 'popular',
        'badge'    => '⭐ Best Value',
        'features' => [
            'Up to 250 Active Clients',
            'WHMCS Branding (Powered by WHMCS)',
            'Email Support',
            'Host on Your Own Server',
            'All Core WHMCS Modules',
            'Billing & Invoicing',
            'Client Management',
            'Domain Management',
            'Support Ticket System',
            'Automation Rules',
        ],
        'order' => 'https://my.resellnom.com/cart.php?a=add&pid=3',
    ],
    [
        'pid'      => 1,
        'name_fb'  => 'WHMCS Professional — No Branding',
        'clients'  => '500',
        'branding' => false,
        'popular'  => false,
        'color'    => 'pro',
        'badge'    => '🚀 Most Powerful',
        'features' => [
            'Up to 500 Active Clients',
            'No Branding (WHMCS line hidden)',
            'Priority Email Support',
            'Host on Your Own Server',
            'All Core WHMCS Modules',
            'Billing & Invoicing',
            'Advanced Client Management',
            'Domain Management',
            'Support Ticket System',
            'Automation Rules',

        ],
        'order' => 'https://my.resellnom.com/cart.php?a=add&pid=1',
    ],
];

// Fetch name + price for each plan (USD = currency 1)
foreach ($plans as &$p) {
    $p['name']          = whmcsFetch($whmcs, $p['pid'], 'name') ?: $p['name_fb'];
    $raw_price          = whmcsFetch($whmcs, $p['pid'], 'price', 'monthly', 1);
    $p['price_monthly'] = $raw_price ?: '$' . ($p['pid']==2?'25.00':($p['pid']==3?'17.00':'40.00'));
    $p['price_num']     = (float) preg_replace('/[^0-9.]/', '', $p['price_monthly']);
    $p['price_bdt']     = round($p['price_num'] * 130); // approx BDT (live rate applied by JS)
    $p['price_yearly']  = round($p['price_num'] * 12 * 0.130); // 15% annual discount
}
unset($p);

// IP Detection
$_rn_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
if (!empty($_SERVER['HTTP_CF_CONNECTING_IP']))       $_rn_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
elseif (!empty($_SERVER['HTTP_X_REAL_IP']))          $_rn_ip = $_SERVER['HTTP_X_REAL_IP'];
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))    $_rn_ip = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
$_gc = sys_get_temp_dir().'/rn_geo_'.md5($_rn_ip).'.txt';
if (file_exists($_gc)&&(time()-filemtime($_gc))<3600) { $_cc=trim(file_get_contents($_gc)); }
elseif ($_rn_ip==='127.0.0.1'||$_rn_ip==='::1') { $_cc='BD'; }
else {
    $_ch=curl_init('https://ipapi.co/'.$_rn_ip.'/country/');
    curl_setopt_array($_ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>3,CURLOPT_SSL_VERIFYPEER=>false,CURLOPT_USERAGENT=>'Mozilla/5.0']);
    $_cc=curl_exec($_ch);curl_close($_ch);
    $_cc=(strlen($_cc??'')==2)?strtoupper(trim($_cc)):'OTHER';
    file_put_contents($_gc,$_cc);
}
$isBD = ($_cc === 'BD');

// FAQs
$faqs = [
    ['q'=>'What is WHMCS and why do I need it?',
     'a'=>'WHMCS (Web Host Manager Complete Solution) is the industry-standard billing and automation platform for web hosting businesses. It handles client management, invoicing, domain registration, provisioning, and support — all in one place. If you run a hosting business, WHMCS is essential.'],
    ['q'=>'What is the difference between Plus and Professional?',
     'a'=>'The Plus license supports up to 250 active clients, while the Professional license supports up to 500. The Professional plan also includes advanced reporting and API access. If you\'re just starting out, Plus is more than enough.'],
    ['q'=>'What does "No Branding" mean?',
     'a'=>'"No Branding" means the "Powered by WHMCS" line is removed from your client area. This gives your hosting business a fully white-labeled, professional appearance. With Branding, the WHMCS credit line remains visible.'],
    ['q'=>'Can I host WHMCS on my own server?',
     'a'=>'Yes! All our WHMCS licenses are "owned installation" licenses — you host WHMCS on your own cPanel/VPS/dedicated server. You get full control over your data and customization.'],
    ['q'=>'How fast is the license activated?',
     'a'=>'WHMCS licenses are activated instantly after payment. You\'ll receive your license key via email within minutes. The key can be entered directly in your WHMCS admin panel.'],
    ['q'=>'Can I upgrade from Plus to Professional later?',
     'a'=>'Yes. You can upgrade your license at any time by contacting our support team. Pricing will be prorated for the remaining billing period.'],
    ['q'=>'Is WHMCS compatible with cPanel/WHM?',
     'a'=>'Absolutely. WHMCS integrates natively with cPanel/WHM, Plesk, DirectAdmin, and many other control panels. It also integrates with 300+ domain registrars, payment gateways, and server modules.'],
    ['q'=>'Do you offer support for WHMCS setup?',
     'a'=>'Yes. Our team can help you install and configure WHMCS on your server. We also offer WHMCS customization, module development, and theme design services.'],
];

// Schema FAQs $schema_faqs = array_map(fn($f) => [
    '@type' => 'Question',
    'name' => $f['q'],
    'acceptedAnswer' => [
        '@type' => 'Answer',
        'text' => $f['a']
    ]
], $faqs);
<!doctype html>
<html class="no-js" lang="en">
<head>
<meta charset="utf-8">
<title>WHMCS License | Cheap WHMCS Hosting License | ResellNom</title>
<meta name="description" content="Buy cheap WHMCS license from ResellNom. Plus license from $17/mo, Professional from $40/mo. No Branding available. Instant activation. Host on your own server.">
<meta name="keywords" content="WHMCS license, buy WHMCS license, cheap WHMCS license, WHMCS hosting license, WHMCS owned license, WHMCS no branding, WHMCS plus license, WHMCS professional license, WHMCS Bangladesh, WHMCS reseller, WHMCS billing software">
<meta name="author" content="ResellNom">
<meta name="robots" content="index, follow">
<link rel="canonical" href="https://resellnom.com/whmcs-license.php">
<meta property="og:title" content="WHMCS License — Plus & Professional | ResellNom">
<meta property="og:description" content="Cheap WHMCS hosting licenses with instant activation. Plus from $17/mo, Professional from $40/mo. No branding available.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://resellnom.com/whmcs-license.php">
<meta property="og:image" content="https://resellnom.com/img/bdix-vps-banner.jpg">
<meta property="og:site_name" content="ResellNom">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="WHMCS License | ResellNom">
<meta name="twitter:description" content="Buy WHMCS Plus & Professional licenses. Instant activation. No branding. Host on your server.">
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"FAQPage","mainEntity":<?=json_encode($schema_faqs,JSON_UNESCAPED_UNICODE)?>}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "WHMCS License Plans",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"WHMCS Plus No Branding","description":"Up to 250 active clients, no branding, $25/mo"},
    {"@type":"ListItem","position":2,"name":"WHMCS Plus Branding","description":"Up to 250 active clients, with branding, $17/mo"},
    {"@type":"ListItem","position":3,"name":"WHMCS Professional No Branding","description":"Up to 500 active clients, no branding, $40/mo"}
  ]
}
</script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/animate.min.css">
<link rel="stylesheet" href="css/fontawesome-all.min.css">
<link rel="stylesheet" href="css/flaticon.css">
<link rel="stylesheet" href="css/default.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/responsive.css">
<style>
/* ── WHMCS Hero Banner ── */
.whmcs-hero {
    background: linear-gradient(135deg, #031a75 0%, #0d2d8f 45%, #146af8 100%);
    padding: 80px 0 60px; text-align: center; position: relative; overflow: hidden;
}
.whmcs-hero::before {
    content: ''; position: absolute; inset: 0;
    background: url('img/bg/breadcrumb_bg.jpg') center/cover; opacity: .05;
}
.whmcs-hero-inner { position: relative; z-index: 2; }
.whmcs-hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.25);
    color: #fff; font-size: 13px; font-weight: 700;
    padding: 6px 20px; border-radius: 30px; margin-bottom: 20px;
}
.whmcs-hero h1 { font-family:'Rubik',sans-serif; font-size: 46px; font-weight: 800; color: #fff; margin-bottom: 14px; }
.whmcs-hero p  { color: rgba(255,255,255,.85); font-size: 17px; margin-bottom: 30px; }
.whmcs-trust-bar {
    display: flex; align-items: center; justify-content: center;
    gap: 24px; flex-wrap: wrap; margin-top: 30px;
}
.whmcs-trust-item {
    display: flex; align-items: center; gap: 7px;
    color: rgba(255,255,255,.85); font-size: 13px; font-weight: 600;
}
.whmcs-trust-item i { color: #01adef; font-size: 14px; }
@media(max-width:576px){ .whmcs-hero h1{ font-size:28px; } }

/* ── Billing Toggle ── */
.billing-toggle-wrap { display:flex; align-items:center; justify-content:center; gap:14px; margin-bottom:50px; }
.billing-toggle-wrap span { font-family:'Rubik',sans-serif; font-size:15px; font-weight:600; color:#213e6e; cursor:pointer; }
.billing-toggle-wrap span.active { color:#01adef; }
.billing-save-badge { background:linear-gradient(135deg,#01adef,#146af8); color:#fff; font-size:11px; font-weight:700; padding:3px 10px; border-radius:20px; }
.toggle-switch { position:relative; width:52px; height:28px; cursor:pointer; }
.toggle-switch input { display:none; }
.toggle-track { position:absolute; inset:0; background:#cdd5e0; border-radius:20px; transition:background .3s; }
.toggle-switch input:checked ~ .toggle-track { background:linear-gradient(135deg,#01adef,#146af8); }
.toggle-thumb { position:absolute; top:3px; left:3px; width:22px; height:22px; background:#fff; border-radius:50%; transition:transform .3s; box-shadow:0 2px 6px rgba(0,0,0,.15); }
.toggle-switch input:checked ~ .toggle-thumb { transform:translateX(24px); }

/* ── Currency Bar ── */
.curr-page-bar { display:flex; align-items:center; justify-content:center; gap:10px; margin-bottom:16px; }
.curr-page-label { font-size:13px; color:#888; }
.curr-page-select { padding:5px 28px 5px 12px; border:2px solid #01adef; border-radius:24px; font-size:13px; font-weight:700; color:#01adef; background:#fff; cursor:pointer; outline:none; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='5'%3E%3Cpath d='M0 0l4 5 4-5z' fill='%2301adef'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; }
.curr-rate-info { font-size:11px; color:#aaa; }

/* ── Pricing Cards ── */
.pricing-box { position: relative; }
.pricing-box.popular-box { border: 2px solid #01adef !important; }
.whmcs-plan-badge {
    position: absolute; top: -14px; left: 50%; transform: translateX(-50%);
    background: linear-gradient(135deg,#01adef,#146af8);
    color: #fff; font-size: 11px; font-weight: 800;
    padding: 4px 16px; border-radius: 20px; white-space: nowrap;
    box-shadow: 0 4px 12px rgba(1,173,239,.35);
}
.whmcs-plan-badge.pro-badge { background: linear-gradient(135deg,#303767,#213e6e); }
.pricing-list ul li i { margin-right: 8px; color: #01adef; }
.pricing-list ul li i.fa-times { color: #ddd; }
.price-yearly { display: none; }
.yearly-mode .price-monthly { display: none; }
.yearly-mode .price-yearly  { display: block; }
.price-per-note { font-size: 12px; color: #aaa; margin-top: 4px; }

/* ── Branding indicator ── */
.branding-tag {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; margin-top: 6px;
}
.branding-tag.no-brand { background: #e8fff0; color: #155724; }
.branding-tag.with-brand { background: #fff3cd; color: #856404; }

/* ── Feature highlight cards ── */
.whmcs-feat-card {
    background: #fff; border-radius: 14px; padding: 28px 24px;
    box-shadow: 0 4px 20px rgba(1,40,120,.06);
    border-top: 3px solid transparent;
    transition: transform .2s, box-shadow .2s, border-top-color .2s;
    height: 100%;
}
.whmcs-feat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 36px rgba(1,173,239,.13); border-top-color: #01adef; }
.whmcs-feat-icon { width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg,#e8f7ff,#d0ecff); display: flex; align-items: center; justify-content: center; font-size: 22px; color: #01adef; margin: 0 auto 14px; }
.whmcs-feat-card h5 { font-family:'Rubik',sans-serif; color:#213e6e; font-size:15px; font-weight:700; margin-bottom:7px; text-align:center; }
.whmcs-feat-card p { font-size:13px; color:#777; line-height:1.7; margin:0; text-align:center; }

/* ── Comparison Table ── */
.compare-table { background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 4px 30px rgba(1,40,120,.07); }
.compare-table thead th { background:linear-gradient(135deg,#01adef,#146af8); color:#fff; font-family:'Rubik',sans-serif; font-size:13px; font-weight:700; padding:15px 18px; text-align:center; }
.compare-table thead th:first-child { text-align:left; }
.compare-table thead th.popular-col { background:#f0f8ff; color:#213e6e; border-top:4px solid #01adef; }
.compare-table thead th.popular-col .pop-name { font-size:14px; font-weight:800; color:#213e6e; display:block; }
.compare-table thead th.popular-col .pop-badge { display:inline-block; background:linear-gradient(135deg,#01adef,#146af8); color:#fff; font-size:10px; font-weight:800; padding:2px 8px; border-radius:10px; margin-top:4px; }
.compare-table tbody td { padding:13px 18px; font-size:13px; color:#555; border-bottom:1px solid #f0f4ff; text-align:center; vertical-align:middle; }
.compare-table tbody td:first-child { text-align:left; font-weight:600; color:#213e6e; }
.compare-table tbody tr:hover td { background:#f7fbff; }
.compare-table .fa-check { color:#2acb35; font-size:15px; }
.compare-table .fa-times { color:#ddd; font-size:15px; }
.compare-table .popular-col { background:rgba(1,173,239,.04); }

/* ── Local highlight section ── */
.local-highlight {
    background: linear-gradient(135deg,#031a75 0%,#01adef 100%);
    border-radius: 16px; padding: 40px; color: #fff; position: relative; overflow: hidden;
}
.local-highlight::before {
    content:''; position:absolute; top:-40px; right:-40px;
    width:200px; height:200px; background:rgba(255,255,255,.05);
    border-radius:50%;
}
.local-highlight h3 { font-family:'Rubik',sans-serif; font-size:24px; font-weight:800; margin-bottom:10px; }
.local-highlight p  { color:rgba(255,255,255,.85); font-size:15px; margin-bottom:20px; }
.local-stat { text-align:center; }
.local-stat h4 { font-size:30px; font-weight:800; color:#fff; margin-bottom:2px; }
.local-stat p  { font-size:12px; color:rgba(255,255,255,.7); margin:0; }
.local-btn {
    display:inline-flex; align-items:center; gap:8px;
    background:#fff; color:#146af8; font-weight:800; font-size:14px;
    padding:13px 28px; border-radius:30px; text-decoration:none;
    transition:transform .2s,box-shadow .2s;
}
.local-btn:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,.2); color:#146af8; text-decoration:none; }

/* ── FAQ highlight style ── */
.faq-highlight .faq-set a { font-weight:600; }
.faq-highlight .faq-set a.highlighted { color:#01adef; }
</style>
</head>
<body>
<button class="scroll-top scroll-to-target" data-target="html"><i class="fas fa-angle-up"></i></button>
<?php include "./inc/header.php"; ?>
<main>

<!-- ═══ HERO ═══ -->
<section class="whmcs-hero">
<div class="container whmcs-hero-inner">
    <div class="whmcs-hero-badge">
        <img src="https://www.whmcs.com/images/whmcs-logo.svg" height="18" alt="WHMCS" onerror="this.style.display='none'">
        Official WHMCS Reseller
    </div>
    <h1>WHMCS Hosting Licenses</h1>
    <p>Start your hosting business with the world's #1 billing & automation platform.<br>Instant activation · Own server · No branding available</p>
    <div>
        <a href="#pricing" class="btn" style="margin-right:10px;"><span>+</span> View Plans</a>
        <a href="contact.php" class="btn" style="background:transparent;border:2px solid rgba(255,255,255,.5);color:#fff;">Contact Sales</a>
    </div>
    <div class="whmcs-trust-bar">
        <div class="whmcs-trust-item"><i class="fas fa-bolt"></i> Instant Activation</div>
        <div class="whmcs-trust-item"><i class="fas fa-server"></i> Host on Your Server</div>
        <div class="whmcs-trust-item"><i class="fas fa-tag"></i> No Branding Available</div>
        <div class="whmcs-trust-item"><i class="fas fa-headset"></i> 24/7 Support</div>
        <div class="whmcs-trust-item"><i class="fas fa-shield-alt"></i> Official License</div>
    </div>
</div>
</section>

<!-- ═══ PRICING ═══ -->
<section class="pricing-area gray-bg position-relative pt-100 pb-70" id="pricing">
<div class="container">

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="section-title text-center mb-40">
                <span class="sub-title">WHMCS LICENSE PLANS</span>
                <h2 class="title">Choose Your WHMCS License</h2>
                <p>All licenses include instant activation and are hosted on your own server</p>
            </div>
        </div>
    </div>

    <!-- Currency Bar -->
    <div class="curr-page-bar">
        <span class="curr-page-label">💱 Currency:</span>
        <select id="currSelect" class="curr-page-select">
            <option value="USD">$ USD</option>
            <option value="BDT">৳ BDT</option>
            <option value="EUR">€ EUR</option>
            <option value="GBP">£ GBP</option>
            <option value="INR">₹ INR</option>
            <option value="SAR">﷼ SAR</option>
            <option value="AED">د.إ AED</option>
            <option value="SGD">S$ SGD</option>
        </select>
       
    </div>

    <!-- Billing Toggle -->
    <div class="billing-toggle-wrap">
        <span class="active" id="lbl-monthly">Monthly</span>
        <label class="toggle-switch">
            <input type="checkbox" id="billingToggle">
            <div class="toggle-track"></div>
            <div class="toggle-thumb"></div>
        </label>
        <span id="lbl-yearly">Yearly</span>
        <span class="billing-save-badge">Save 15%</span>
    </div>

    <!-- Pricing Cards -->
    <div class="row pricing-box-wrap justify-content-center" id="pricingWrap">
    <?php foreach($plans as $p): ?>
    <div class="col-lg-4 col-md-6 col-sm-9">
        <div class="pricing-box mb-30 <?=$p['popular']?'popular-box':''?>" style="padding-top:<?=$p['badge']?'36px':'20px'?>;">
            <?php if($p['badge']): ?>
            <div class="whmcs-plan-badge <?=$p['color']==='pro'?'pro-badge':''?>"><?=$p['badge']?></div>
            <?php endif; ?>
            <div class="pricing-head">
                <h6><?=htmlspecialchars($p['name'])?></h6>
                <div class="branding-tag <?=$p['branding']?'with-brand':'no-brand'?>">
                    <i class="fas <?=$p['branding']?'fa-eye':'fa-eye-slash'?>"></i>
                    <?=$p['branding']?'With WHMCS Branding':'No Branding'?>
                </div>
                <div class="pricing-icon services-icon" style="margin-top:12px;">
                    <i class="flaticon-hosting"></i>
                </div>
            </div>
            <div class="pricing-list mb-30">
                <h5>Features</h5>
                <ul>
                    <?php foreach($p['features'] as $f): ?>
                    <li><i class="fas fa-check"></i><?=htmlspecialchars($f)?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <!-- Monthly Price -->
            <div class="price mb-20 price-monthly">
                <h2 class="geo-price" data-usd="<?=$p['price_num']?>"><?=$p['price_monthly']?></h2>
                <p class="price-per-note">per month</p>
            </div>
            <!-- Yearly Price -->
            <div class="price mb-20 price-yearly">
                <h2 class="geo-price-yr" data-usd-yr="<?=$p['price_yearly']?>">$<?=number_format($p['price_yearly'],2)?></h2>
                <p class="price-per-note">per year <strong style="color:#2acb35;">15% off</strong></p>
            </div>
            <div class="pricing-btn">
                <a href="<?=$p['order']?>" class="btn"><span>+</span> Get License</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    </div>

    <!-- ═══ COMPARISON TABLE ═══ -->
    <div class="row justify-content-center mt-60">
        <div class="col-12">
            <div class="section-title text-center mb-40">
                <span class="sub-title">FULL COMPARISON</span>
                <h2 class="title">Plan Comparison</h2>
            </div>
        </div>
        <div class="col-lg-10">
        <div class="table-responsive compare-table">
        <table class="table mb-0">
            <thead><tr>
                <th>Feature</th>
                <?php foreach($plans as $p): ?>
                <th class="<?=$p['popular']?'popular-col':''?>">
                    <?php if($p['popular']): ?>
                    <span class="pop-name"><?=htmlspecialchars($p['name'])?></span>
                    <span class="pop-badge">⭐ Best Value</span>
                    <?php else: ?>
                    <?=htmlspecialchars($p['name'])?>
                    <?php endif; ?>
                </th>
                <?php endforeach; ?>
            </tr></thead>
            <tbody>
            <?php
            $rows = [
                ['Active Clients',      ['250','250','500']],
                ['No Branding',         [true, false, true]],
                ['Host on Own Server',  [true, true,  true]],
                ['Email Support',       [true, true,  true]],
                ['Priority Support',    [false,false, true]],
                ['Billing & Invoicing', [true, true,  true]],
                ['Client Management',   [true, true,  true]],
                ['Domain Management',   [true, true,  true]],
                ['Support Tickets',     [true, true,  true]],
                ['Automation Rules',    [true, true,  true]],
                ['Advanced Reporting',  [false,false, true]],
                ['All Core Modules',    [true, true,  true]],
            ];
            foreach($rows as $r):?>
            <tr>
                <td><?=$r[0]?></td>
                <?php foreach([0,1,2] as $i): ?>
                <td class="<?=$plans[$i]['popular']?'popular-col':''?>">
                    <?php if(is_bool($r[1][$i])): ?>
                    <i class="fas <?=$r[1][$i]?'fa-check':'fa-times'?>"></i>
                    <?php else: ?>
                    <strong><?=$r[1][$i]?></strong>
                    <?php endif; ?>
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

<!-- ═══ WHY WHMCS FEATURES ═══ -->
<section class="pt-80 pb-80">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="section-title text-center mb-60">
                <span class="sub-title">WHAT YOU GET WITH WHMCS</span>
                <h2 class="title">Everything You Need to Run a Hosting Business</h2>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
    <?php $feats = [
        ['fas fa-file-invoice-dollar', 'Automated Billing',     'Auto-generate invoices, send payment reminders, and collect payments. Supports 100+ payment gateways including PayPal, Stripe, and local BD options.'],
        ['fas fa-robot',              'Auto Provisioning',      'Automatically create cPanel accounts, VPS, domains, and SSL certificates when clients order — zero manual work required.'],
        ['fas fa-globe',              'Domain Management',      'Integrates with 300+ domain registrars. Register, transfer, renew, and manage DNS records directly from WHMCS.'],
        ['fas fa-ticket-alt',         'Support Ticket System',  'Built-in helpdesk with departments, priorities, auto-responders, and client portal — professional support from day one.'],
        ['fas fa-users',              'Client Management',      'Full client database with service history, credit management, two-factor auth, affiliate system, and detailed reporting.'],
        ['fas fa-code',               'Developer API',          'Full REST API access (Pro plan) to integrate WHMCS with your custom apps, CRMs, and billing systems.'],
    ];
    foreach($feats as $f):?>
    <div class="col-lg-4 col-md-6 mb-30">
        <div class="whmcs-feat-card">
            <div class="whmcs-feat-icon"><i class="<?=$f[0]?>"></i></div>
            <h5><?=$f[1]?></h5>
            <p><?=$f[2]?></p>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
</div>
</section>

<!-- ═══ LOCAL HIGHLIGHT (BD) ═══ -->
<?php if($isBD): ?>
<section class="pt-0 pb-80">
<div class="container">
    <div class="local-highlight">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <h3>🇧🇩 বাংলাদেশ থেকে WHMCS লাইসেন্স কিনুন</h3>
                <p>ResellNom বাংলাদেশের একটি বিশ্বস্ত WHMCS রিসেলার। BDT তে পেমেন্ট করুন — bKash, Nagad, Rocket, বা ব্যাংক ট্রান্সফারে। তাৎক্ষণিক অ্যাক্টিভেশন এবং বাংলা সাপোর্ট পাওয়া যায়।</p>
                <div class="row mb-20">
                    <div class="col-4 local-stat"><h4>৳<?=number_format($plans[1]['price_bdt'])?></h4><p>থেকে শুরু</p></div>
                    <div class="col-4 local-stat"><h4>তাৎক্ষণিক</h4><p>অ্যাক্টিভেশন</p></div>
                    <div class="col-4 local-stat"><h4>২৪/৭</h4><p>সাপোর্ট</p></div>
                </div>
                <a href="contact.php" class="local-btn"><i class="fas fa-headset"></i> সাপোর্টে যোগাযোগ করুন</a>
            </div>
            <div class="col-lg-5">
                <div style="background:rgba(255,255,255,.1);border-radius:14px;padding:24px;">
                    <h5 style="color:#fff;margin-bottom:16px;font-family:'Rubik',sans-serif;">✅ বাংলাদেশে পেমেন্ট পদ্ধতি</h5>
                    <ul style="list-style:none;padding:0;margin:0;">
                        <?php foreach(['bKash','Nagad','Rocket','ব্যাংক ট্রান্সফার','ডেবিট/ক্রেডিট কার্ড','PayPal'] as $pm):?>
                        <li style="color:rgba(255,255,255,.85);padding:6px 0;border-bottom:1px solid rgba(255,255,255,.1);font-size:14px;">
                            <i class="fas fa-check" style="color:#a6e3a1;margin-right:8px;"></i><?=$pm?>
                        </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<?php else: ?>
<!-- International highlight -->
<section class="pt-0 pb-80">
<div class="container">
    <div class="local-highlight">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h3>🌍 International Clients Welcome</h3>
                <p>ResellNom serves clients across Bangladesh, USA, Europe, Middle East, and Southeast Asia. Pay in USD via PayPal or international card. Instant activation worldwide.</p>
                <div class="row mb-20">
                    <div class="col-4 local-stat"><h4>$<?=number_format($plans[1]['price_num'],0)?></h4><p>Starting price/mo</p></div>
                    <div class="col-4 local-stat"><h4>Instant</h4><p>Activation</p></div>
                    <div class="col-4 local-stat"><h4>24/7</h4><p>Support</p></div>
                </div>
                <a href="contact.php" class="local-btn"><i class="fas fa-headset"></i> Contact Sales</a>
            </div>
            <div class="col-lg-4">
                <div style="background:rgba(255,255,255,.1);border-radius:14px;padding:24px;">
                    <h5 style="color:#fff;margin-bottom:16px;font-family:'Rubik',sans-serif;">✅ Payment Methods</h5>
                    <ul style="list-style:none;padding:0;margin:0;">
                        <?php foreach(['PayPal','Credit / Debit Card','Bank Transfer','Stripe','Payoneer'] as $pm):?>
                        <li style="color:rgba(255,255,255,.85);padding:6px 0;border-bottom:1px solid rgba(255,255,255,.1);font-size:14px;">
                            <i class="fas fa-check" style="color:#a6e3a1;margin-right:8px;"></i><?=$pm?>
                        </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<?php endif; ?>

<!-- ═══ FAQ ═══ -->
<section class="faq-area gray-bg pt-100 pb-100">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="section-title text-center mb-60">
                <span class="sub-title">WHMCS LICENSE FAQ</span>
                <h2 class="title">Frequently Asked Questions</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="faq-wrap faq-highlight">
                <?php foreach(array_slice($faqs,0,4) as $i=>$f):?>
                <div class="faq-set">
                    <a <?=$i===0?'class="active highlighted"':''?> href="#">
                        <?=$f['q']?><i class="fas fa-angle-<?=$i===0?'down':'up'?>"></i>
                    </a>
                    <div class="content" <?=$i===0?'style="display:block;"':''?>>
                        <p><?=$f['a']?></p>
                    </div>
                </div>
                <?php endforeach;?>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="faq-wrap faq-highlight">
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
<script src="js/slick.min.js"></script>
<script src="js/wow.min.js"></script>
<script src="js/plugins.js"></script>
<script src="js/main.js"></script>
<script src="js/custom.js"></script>

<script>
(function(){
var toggle  = document.getElementById('billingToggle');
var wrap    = document.getElementById('pricingWrap');
var lblMo   = document.getElementById('lbl-monthly');
var lblYr   = document.getElementById('lbl-yearly');
var currentCurr = 'USD';
var rates   = {USD:1};
var ratesFetched = false;

toggle.addEventListener('change', function(){
    wrap.classList.toggle('yearly-mode', this.checked);
    lblMo.classList.toggle('active', !this.checked);
    lblYr.classList.toggle('active', this.checked);
    applyConversion(currentCurr, rates[currentCurr]||1);
});

var symbols = {USD:'$',BDT:'৳',EUR:'€',GBP:'£',INR:'₹',SAR:'﷼',AED:'د.إ',SGD:'S$'};
function fmt(sym,v){ return v>=1000?sym+Math.round(v).toLocaleString():v>=10?sym+v.toFixed(2):sym+v.toFixed(2); }

function applyConversion(curr, rate) {
    currentCurr = curr;
    var sym = symbols[curr] || curr+' ';
    // Monthly
    document.querySelectorAll('.geo-price').forEach(function(el){
        var usd = parseFloat(el.getAttribute('data-usd'));
        if(!isNaN(usd)) el.innerHTML = fmt(sym, usd * rate) + '<span>/mo</span>';
    });
    // Yearly
    document.querySelectorAll('.geo-price-yr').forEach(function(el){
        var usd = parseFloat(el.getAttribute('data-usd-yr'));
        if(!isNaN(usd)) el.innerHTML = fmt(sym, usd * rate) + '<span>/yr</span>';
    });
    var info = document.getElementById('currRateInfo');
    if(info) info.textContent = curr==='USD' ? 'Base currency' : '1 USD = '+rate.toFixed(4)+' '+curr+' (live)';
    ['currSelect','headerCurrSelect'].forEach(function(id){
        var el=document.getElementById(id); if(el&&el.value!==curr)el.value=curr;
    });
    try{localStorage.setItem('rn_currency',curr);}catch(e){}
}

function fetchRates(curr) {
    if(curr==='USD'){ applyConversion('USD',1); return; }
    fetch('https://api.exchangerate-api.com/v4/latest/USD')
        .then(function(r){return r.json();})
        .then(function(d){
            rates=d.rates||{USD:1}; rates.USD=1; ratesFetched=true;
            applyConversion(curr, rates[curr]||1);
        })
        .catch(function(){
            var fb={USD:1,BDT:110,EUR:0.92,GBP:0.79,INR:83,SAR:3.75,AED:3.67,SGD:1.34};
            rates=fb; applyConversion(curr, fb[curr]||1);
        });
}

['currSelect','headerCurrSelect'].forEach(function(id){
    var el=document.getElementById(id);
    if(el) el.addEventListener('change',function(){
        ratesFetched ? applyConversion(this.value,rates[this.value]||1) : fetchRates(this.value);
    });
});

// Init — BD visitors default to BDT, others USD
var isBD = <?=$isBD?'true':'false'?>;
var saved = null;
try{saved=localStorage.getItem('rn_currency');}catch(e){}
var initCurr = saved || (isBD ? 'BDT' : 'USD');
['currSelect','headerCurrSelect'].forEach(function(id){
    var el=document.getElementById(id); if(el)el.value=initCurr;
});
fetchRates(initCurr);

})();
</script>
</body>
</html>