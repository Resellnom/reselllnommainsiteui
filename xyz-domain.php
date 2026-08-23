<?php
// ═══════════════════════════════════════════
// VISITOR LANGUAGE DETECTION — server-side
// ═══════════════════════════════════════════
function getVisitorCountry() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    if($ip==='127.0.0.1'||$ip==='::1') return 'BD';
    $cache = sys_get_temp_dir().'/rn_geo_'.md5($ip).'.txt';
    if(file_exists($cache)&&(time()-filemtime($cache))<3600)
        return trim(file_get_contents($cache));
    $ch = curl_init('https://ipapi.co/'.$ip.'/country/');
    curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>3,CURLOPT_SSL_VERIFYPEER=>false,CURLOPT_USERAGENT=>'Mozilla/5.0']);
    $cc = curl_exec($ch); curl_close($ch);
    $cc = ($cc&&strlen($cc)===2)?strtoupper(trim($cc)):'BD';
    file_put_contents($cache,$cc); return $cc;
}
$isBD = (getVisitorCountry()==='BD');

// ═══════════════════════════════════════════
// WHMCS FETCH — domain pricing
// Update pids below with your WHMCS domain PIDs
// ═══════════════════════════════════════════
$whmcs = 'https://my.resellnom.com';

// Domain pricing config — update register/renew prices from WHMCS or hardcode
// WHMCS domain feed: /feeds/productsinfo.php?pid=X&get=price&billingcycle=annually&currency=2
function whmcsDomainPrice($whmcs,$pid,$currency=2){
    $cache = sys_get_temp_dir().'/rn_dom_'.$pid.'_'.$currency.'.txt';
    if(file_exists($cache)&&(time()-filemtime($cache))<1800){
        $v=trim(file_get_contents($cache)); if($v) return $v;
    }
    $ch=curl_init($whmcs.'/feeds/productsinfo.php?pid='.$pid.'&get=price&billingcycle=annually&currency='.$currency);
    curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>5,CURLOPT_SSL_VERIFYPEER=>false]);
    $val=curl_exec($ch); curl_close($ch);
    $val=$val?trim(strip_tags($val)):null;
    if($val) file_put_contents($cache,$val);
    return $val;
}

// .xyz domain extensions config
// Add your WHMCS domain PIDs here — pid=0 means use fallback price
$extensions = [
    ['ext'=>'.xyz',     'pid'=>0, 'reg_bdt'=>'৳149',  'reg_usd'=>'$1.49',  'renew_bdt'=>'৳899',  'renew_usd'=>'$7.99',  'promo'=>true,  'hot'=>true],
    ['ext'=>'.online',  'pid'=>0, 'reg_bdt'=>'৳199',  'reg_usd'=>'$1.99',  'renew_bdt'=>'৳1,299','renew_usd'=>'$11.99', 'promo'=>true,  'hot'=>false],
    ['ext'=>'.site',    'pid'=>0, 'reg_bdt'=>'৳199',  'reg_usd'=>'$1.99',  'renew_bdt'=>'৳1,499','renew_usd'=>'$12.99', 'promo'=>false, 'hot'=>false],
    ['ext'=>'.store',   'pid'=>0, 'reg_bdt'=>'৳299',  'reg_usd'=>'$2.99',  'renew_bdt'=>'৳1,799','renew_usd'=>'$15.99', 'promo'=>false, 'hot'=>false],
    ['ext'=>'.tech',    'pid'=>0, 'reg_bdt'=>'৳299',  'reg_usd'=>'$2.99',  'renew_bdt'=>'৳1,599','renew_usd'=>'$13.99', 'promo'=>false, 'hot'=>false],
    ['ext'=>'.com',     'pid'=>0, 'reg_bdt'=>'৳1,199','reg_usd'=>'$10.99', 'renew_bdt'=>'৳1,399','renew_usd'=>'$12.99', 'promo'=>false, 'hot'=>false],
    ['ext'=>'.net',     'pid'=>0, 'reg_bdt'=>'৳1,299','reg_usd'=>'$11.99', 'renew_bdt'=>'৳1,499','renew_usd'=>'$13.99', 'promo'=>false, 'hot'=>false],
    ['ext'=>'.org',     'pid'=>0, 'reg_bdt'=>'৳999',  'reg_usd'=>'$8.99',  'renew_bdt'=>'৳1,199','renew_usd'=>'$10.99', 'promo'=>false, 'hot'=>false],
    ['ext'=>'.info',    'pid'=>0, 'reg_bdt'=>'৳599',  'reg_usd'=>'$4.99',  'renew_bdt'=>'৳999',  'renew_usd'=>'$8.99',  'promo'=>false, 'hot'=>false],
    ['ext'=>'.io',      'pid'=>0, 'reg_bdt'=>'৳3,499','reg_usd'=>'$29.99', 'renew_bdt'=>'৳3,999','renew_usd'=>'$35.99', 'promo'=>false, 'hot'=>false],
    ['ext'=>'.co',      'pid'=>0, 'reg_bdt'=>'৳1,999','reg_usd'=>'$17.99', 'renew_bdt'=>'৳2,299','renew_usd'=>'$19.99', 'promo'=>false, 'hot'=>false],
    ['ext'=>'.me',      'pid'=>0, 'reg_bdt'=>'৳999',  'reg_usd'=>'$8.99',  'renew_bdt'=>'৳1,299','renew_usd'=>'$11.99', 'promo'=>false, 'hot'=>false],
];

// Bilingual strings
$t = [
    'title'        => $isBD ? '.XYZ ডোমেইন | সেরা দামে ডোমেইন রেজিস্ট্রেশন | ResellNom'
                            : '.XYZ Domain | Best Price Domain Registration | ResellNom',
    'meta_desc'    => $isBD ? 'ResellNom-এ মাত্র ৳149 থেকে .xyz ডোমেইন রেজিস্ট্রেশন করুন। সেরা দামে .com, .net, .online সহ সব ধরনের ডোমেইন পাওয়া যাচ্ছে।'
                            : 'Register .xyz domain from just ৳149 at ResellNom. Get .com, .net, .online and all domain extensions at the best prices.',
    'hero_badge'   => $isBD ? '🔥 সীমিত সময়ের অফার'      : '🔥 Limited Time Offer',
    'hero_h1'      => $isBD ? 'আপনার স্বপ্নের ডোমেইন খুঁজে নিন'
                            : 'Find Your Perfect Domain',
    'hero_sub'     => $isBD ? '.xyz মাত্র ৳149/বছর থেকে — আজই রেজিস্ট্রেশন করুন!'
                            : '.xyz starting at just ৳149/year — Register today!',
    'search_ph'    => $isBD ? 'আপনার ডোমেইন নাম লিখুন...'  : 'Search your domain name...',
    'search_btn'   => $isBD ? 'খুঁজুন'                     : 'Search',
    'avail_yes'    => $isBD ? '✅ পাওয়া যাচ্ছে!'           : '✅ Available!',
    'avail_no'     => $isBD ? '❌ নেওয়া হয়ে গেছে'         : '❌ Already Taken',
    'avail_err'    => $isBD ? '⚠️ চেক করা সম্ভব হয়নি'    : '⚠️ Could not check',
    'register_btn' => $isBD ? 'রেজিস্ট্রেশন করুন'         : 'Register',
    'reg_price'    => $isBD ? 'রেজিস্ট্রেশন'               : 'Registration',
    'renew_price'  => $isBD ? 'নবায়ন'                     : 'Renewal',
    'per_yr'       => $isBD ? '/বছর'                       : '/yr',
    'promo_txt'    => $isBD ? 'প্রমো'                      : 'PROMO',
    'hot_txt'      => $isBD ? 'হট'                        : 'HOT',
    'all_domains'  => $isBD ? 'সব ডোমেইন এক্সটেনশন'       : 'All Domain Extensions',
    'why_title'    => $isBD ? 'কেন ResellNom থেকে ডোমেইন নেবেন?'
                            : 'Why Register Domain with ResellNom?',
    'faq_title'    => $isBD ? 'সচরাচর জিজ্ঞাসা'           : 'Frequently Asked Questions',
    'bc_home'      => $isBD ? 'হোম'                        : 'Home',
    'bc_page'      => $isBD ? 'ডোমেইন রেজিস্ট্রেশন'       : 'Domain Registration',
    'curr_label'   => $isBD ? '💱 মুদ্রা:'                 : '💱 Currency:',
];
?>
<!doctype html>
<html class="no-js" lang="<?= $isBD?'bn':'en' ?>">
<head>
    <meta charset="utf-8">
    <title><?= $t['title'] ?></title>
    <meta name="description" content="<?= $t['meta_desc'] ?>">
    <meta name="keywords" content="xyz domain, .xyz domain registration, domain registration bd, ডোমেইন রেজিস্ট্রেশন, cheap domain bangladesh, domain buy bd, .com domain, .net domain, resellnom domain, সস্তা ডোমেইন, domain price bd">
    <meta name="author" content="ResellNom">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://resellnom.com/xyz-domain.php">
    <meta property="og:title" content="<?= $t['title'] ?>">
    <meta property="og:description" content="<?= $t['meta_desc'] ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://resellnom.com/xyz-domain.php">
    <meta property="og:image" content="https://resellnom.com/img/bdix-vps-banner.jpg">
    <meta property="og:site_name" content="ResellNom">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $t['title'] ?>">
    <meta name="twitter:description" content="<?= $t['meta_desc'] ?>">
    <script type="application/ld+json">
    {"@context":"https://schema.org","@type":"Product","name":".XYZ Domain Registration","description":"<?= addslashes($t['meta_desc']) ?>","brand":{"@type":"Brand","name":"ResellNom"},"offers":{"@type":"AggregateOffer","lowPrice":"149","priceCurrency":"BDT","offerCount":"<?= count($extensions) ?>"}}
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.min.css">
    <link rel="stylesheet" href="css/fontawesome-all.min.css">
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/odometer.css">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <style>
    /* ── Hero ── */
    .dom-hero {
        background: linear-gradient(135deg,#031a75 0%,#01adef 60%,#146af8 100%);
        padding: 80px 0 60px; text-align: center; position: relative; overflow: hidden;
    }
    .dom-hero::before {
        content:''; position:absolute; inset:0;
        background: url('img/bg/breadcrumb_bg.jpg') center/cover no-repeat;
        opacity:.08;
    }
    .dom-hero-badge {
        display:inline-block; background:rgba(255,255,255,.15);
        border:1px solid rgba(255,255,255,.3); color:#fff;
        font-size:13px; font-weight:700; padding:5px 18px;
        border-radius:30px; margin-bottom:20px; letter-spacing:.5px;
        backdrop-filter:blur(6px);
    }
    .dom-hero h1 {
        font-family:'Rubik',sans-serif; font-size:46px; font-weight:800;
        color:#fff; margin-bottom:14px; line-height:1.2;
    }
    .dom-hero p { color:rgba(255,255,255,.85); font-size:17px; margin-bottom:36px; }
    @media(max-width:576px){ .dom-hero h1{ font-size:28px; } }

    /* ── Search Box ── */
    .dom-search-wrap {
        max-width:680px; margin:0 auto 30px;
        background:rgba(255,255,255,.12);
        border:1px solid rgba(255,255,255,.25);
        border-radius:60px; padding:8px 8px 8px 24px;
        display:flex; align-items:center; gap:10px;
        backdrop-filter:blur(10px);
    }
    .dom-search-wrap i { color:rgba(255,255,255,.7); font-size:16px; }
    .dom-search-wrap input {
        flex:1; background:transparent; border:none; outline:none;
        color:#fff; font-size:16px; font-family:'Roboto',sans-serif;
        min-width:0;
    }
    .dom-search-wrap input::placeholder { color:rgba(255,255,255,.6); }
    .dom-search-btn {
        padding:14px 30px; border-radius:50px; border:none;
        background:linear-gradient(135deg,#fff,#e8f4ff);
        color:#146af8; font-weight:800; font-size:14px;
        cursor:pointer; transition:transform .2s,box-shadow .2s; white-space:nowrap;
        font-family:'Rubik',sans-serif;
    }
    .dom-search-btn:hover { transform:scale(1.04); box-shadow:0 6px 20px rgba(0,0,0,.2); }

    /* Search result */
    .dom-search-result {
        max-width:680px; margin:0 auto; min-height:52px;
        background:rgba(255,255,255,.12); border-radius:14px;
        padding:14px 24px; color:#fff; font-size:15px;
        backdrop-filter:blur(6px); display:none;
        align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;
    }
    .dom-search-result.show { display:flex; }
    .dom-result-name { font-weight:700; font-size:17px; }
    .dom-result-status { font-size:14px; }
    .dom-result-action a {
        background:#fff; color:#146af8; font-weight:700; font-size:13px;
        padding:8px 20px; border-radius:30px; text-decoration:none;
        transition:background .2s;
    }
    .dom-result-action a:hover { background:#e8f4ff; }

    /* ── Currency Bar ── */
    .curr-bar {
        display:flex; align-items:center; justify-content:center;
        gap:10px; margin-bottom:30px; flex-wrap:wrap;
    }
    .curr-bar label { font-size:13px; color:#666; margin:0; }
    .curr-bar-select {
        padding:6px 28px 6px 12px; border:2px solid #01adef;
        border-radius:24px; font-size:13px; font-weight:700;
        color:#01adef; background:#fff; cursor:pointer; outline:none; appearance:none;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='5'%3E%3Cpath d='M0 0l4 5 4-5z' fill='%2301adef'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 10px center;
    }
    .curr-rate-note { font-size:11px; color:#aaa; }

    /* ── Domain Extension Table ── */
    .dom-ext-table { background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 4px 30px rgba(1,40,120,.07); }
    .dom-ext-table thead th {
        background:linear-gradient(135deg,#01adef,#146af8); color:#fff;
        font-family:'Rubik',sans-serif; font-size:13px; padding:14px 20px;
        font-weight:700; text-transform:uppercase; letter-spacing:.4px;
    }
    .dom-ext-table tbody td {
        padding:14px 20px; font-size:14px; color:#444;
        border-bottom:1px solid #f0f4ff; vertical-align:middle;
    }
    .dom-ext-table tbody tr:last-child td { border-bottom:none; }
    .dom-ext-table tbody tr:hover td { background:#f7fbff; }
    .dom-ext-name { font-size:18px; font-weight:800; color:#213e6e; font-family:'Rubik',sans-serif; }
    .dom-price-reg { font-size:18px; font-weight:800; color:#146af8; }
    .dom-price-renew { font-size:13px; color:#999; }
    .dom-promo-badge {
        display:inline-block; background:linear-gradient(135deg,#ff6b35,#ff3d00);
        color:#fff; font-size:10px; font-weight:800;
        padding:2px 8px; border-radius:20px; margin-left:6px;
        text-transform:uppercase; vertical-align:middle;
    }
    .dom-hot-badge {
        display:inline-block; background:linear-gradient(135deg,#01adef,#146af8);
        color:#fff; font-size:10px; font-weight:800;
        padding:2px 8px; border-radius:20px; margin-left:6px;
        text-transform:uppercase; vertical-align:middle;
    }
    .dom-reg-btn {
        padding:9px 20px; background:linear-gradient(135deg,#01adef,#146af8);
        color:#fff; border-radius:30px; font-size:13px; font-weight:700;
        text-decoration:none; display:inline-block; white-space:nowrap;
        transition:transform .2s,box-shadow .2s;
    }
    .dom-reg-btn:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(1,173,239,.35); color:#fff; text-decoration:none; }

    /* ── Why choose us cards ── */
    .why-card {
        background:#fff; border-radius:14px; padding:30px 24px;
        box-shadow:0 4px 24px rgba(1,40,120,.06);
        text-align:center; height:100%;
        transition:transform .2s,box-shadow .2s;
        border-top:3px solid transparent;
    }
    .why-card:hover { transform:translateY(-4px); box-shadow:0 12px 36px rgba(1,173,239,.14); border-top-color:#01adef; }
    .why-icon {
        width:60px; height:60px; border-radius:50%;
        background:linear-gradient(135deg,#e8f7ff,#d0ecff);
        display:flex; align-items:center; justify-content:center;
        font-size:24px; color:#01adef; margin:0 auto 16px;
    }
    .why-card h5 { font-family:'Rubik',sans-serif; color:#213e6e; font-size:15px; font-weight:700; margin-bottom:8px; }
    .why-card p  { font-size:13px; color:#777; line-height:1.7; margin:0; }

    /* Loading spinner */
    .dom-searching { color:rgba(255,255,255,.8); font-size:14px; text-align:center; margin-top:10px; display:none; }
    </style>
</head>
<body>

<button class="scroll-top scroll-to-target" data-target="html"><i class="fas fa-angle-up"></i></button>

<?php include "./inc/header.php"; ?>

<main>

<!-- ── HERO ── -->
<section class="dom-hero">
    <div class="container" style="position:relative;z-index:2;">
        <div class="dom-hero-badge"><?= $t['hero_badge'] ?></div>
        <h1><?= $t['hero_h1'] ?></h1>
        <p><?= $t['hero_sub'] ?></p>

        <!-- Search Box -->
        <div class="dom-search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" id="domainInput" placeholder="<?= $t['search_ph'] ?>" maxlength="63">
            <button class="dom-search-btn" id="domainSearchBtn"><?= $t['search_btn'] ?></button>
        </div>
        <div class="dom-searching" id="domSearching"><i class="fas fa-spinner fa-spin"></i> <?= $isBD?'চেক করা হচ্ছে...':'Checking availability...' ?></div>

        <!-- Results -->
        <div class="dom-search-result" id="domainResult">
            <div>
                <div class="dom-result-name" id="resName"></div>
                <div class="dom-result-status" id="resStatus"></div>
            </div>
            <div class="dom-result-action" id="resAction"></div>
        </div>

        <!-- Quick ext pills -->
        <div class="domain-list mt-20">
            <ul>
                <?php foreach(array_slice($extensions,0,6) as $e): ?>
                <li><?= $e['ext'] ?> <span><?= $isBD?$e['reg_bdt']:$e['reg_usd'] ?></span></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>

<!-- ── DOMAIN EXTENSION TABLE ── -->
<section class="pricing-area gray-bg pt-100 pb-70">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title text-center mb-40">
                    <span class="sub-title"><?= $t['all_domains'] ?></span>
                    <h2 class="title"><?= $isBD?'সেরা দামে সব ডোমেইন এক্সটেনশন':'All Domain Extensions at Best Prices' ?></h2>
                </div>
            </div>
        </div>

        <!-- Currency switcher -->
        <div class="curr-bar">
            <label for="currSelect"><?= $t['curr_label'] ?></label>
            <select id="currSelect" class="curr-bar-select">
                <option value="BDT">৳ BDT — Taka</option>
                <option value="USD">$ USD</option>
                <option value="EUR">€ EUR</option>
                <option value="GBP">£ GBP</option>
                <option value="INR">₹ INR</option>
                <option value="SAR">﷼ SAR</option>
                <option value="AED">د.إ AED</option>
                <option value="SGD">S$ SGD</option>
                <option value="MYR">RM MYR</option>
                <option value="CAD">CA$ CAD</option>
                <option value="AUD">A$ AUD</option>
            </select>
            <span class="curr-rate-note" id="currRateInfo"><?= $isBD?'লাইভ রেট':'Live rate' ?></span>
        </div>

        <!-- Table -->
        <div class="table-responsive dom-ext-table">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th><?= $isBD?'এক্সটেনশন':'Extension' ?></th>
                        <th><?= $t['reg_price'] ?></th>
                        <th><?= $t['renew_price'] ?></th>
                        <th><?= $isBD?'অ্যাকশন':'Action' ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($extensions as $e): ?>
                <tr>
                    <td>
                        <span class="dom-ext-name"><?= $e['ext'] ?></span>
                        <?php if($e['hot']): ?><span class="dom-hot-badge"><?= $t['hot_txt'] ?></span><?php endif; ?>
                        <?php if($e['promo']): ?><span class="dom-promo-badge"><?= $t['promo_txt'] ?></span><?php endif; ?>
                    </td>
                    <td>
                        <div class="dom-price-reg geo-price"
                             data-bdt="<?= (float)preg_replace('/[^0-9.]/','',$e['reg_bdt']) ?>"
                             data-orig-bdt="<?= $e['reg_bdt'] ?>">
                            <?= $isBD?$e['reg_bdt']:$e['reg_usd'] ?>
                        </div>
                        <div class="dom-price-renew"><?= $t['per_yr'] ?></div>
                    </td>
                    <td>
                        <div class="geo-price"
                             data-bdt="<?= (float)preg_replace('/[^0-9.]/','',$e['renew_bdt']) ?>"
                             style="color:#888;font-size:14px;">
                            <?= $isBD?$e['renew_bdt']:$e['renew_usd'] ?>
                        </div>
                        <div class="dom-price-renew"><?= $t['per_yr'] ?></div>
                    </td>
                    <td>
                        <a href="https://my.resellnom.com/cart.php?a=add&domain=register&query=<?= ltrim($e['ext'],'.') ?>"
                           class="dom-reg-btn"><?= $t['register_btn'] ?></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</section>

<!-- ── WHY CHOOSE US ── -->
<section class="pt-80 pb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-title text-center mb-60">
                    <span class="sub-title"><?= $isBD?'আমাদের সুবিধাসমূহ':'OUR ADVANTAGES' ?></span>
                    <h2 class="title"><?= $t['why_title'] ?></h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <?php
            $why = $isBD ? [
                ['fas fa-bolt',        'তাৎক্ষণিক অ্যাক্টিভেশন',    'পেমেন্টের সাথে সাথে আপনার ডোমেইন সক্রিয় হয়ে যাবে। কোনো বিলম্ব নেই।'],
                ['fas fa-shield-alt',  'ICANN অনুমোদিত',             'আমরা একটি ICANN স্বীকৃত ডোমেইন রেজিস্ট্রার। আপনার ডোমেইন সম্পূর্ণ নিরাপদ।'],
                ['fas fa-sync-alt',    'সহজ ট্রান্সফার',             'যেকোনো রেজিস্ট্রার থেকে আপনার ডোমেইন আমাদের কাছে স্থানান্তর করুন সহজেই।'],
                ['fas fa-lock',        'ফ্রি WHOIS প্রাইভেসি',       'আপনার ব্যক্তিগত তথ্য সুরক্ষিত রাখুন বিনামূল্যে WHOIS প্রাইভেসি প্রোটেকশনের মাধ্যমে।'],
                ['fas fa-headset',     '২৪/৭ সাপোর্ট',              'আমাদের বিশেষজ্ঞ দল সপ্তাহের ৭ দিন, ২৪ ঘণ্টা আপনার সেবায় নিয়োজিত।'],
                ['fas fa-tag',         'সেরা দাম',                  'বাজারের সেরা দামে ডোমেইন রেজিস্ট্রেশন। কোনো লুকানো চার্জ নেই।'],
            ] : [
                ['fas fa-bolt',        'Instant Activation',         'Your domain activates immediately after payment. No delays, no waiting.'],
                ['fas fa-shield-alt',  'ICANN Accredited',           'We are an ICANN accredited domain registrar. Your domain is fully secure with us.'],
                ['fas fa-sync-alt',    'Easy Transfer',              'Transfer your domain from any registrar to ResellNom quickly and easily.'],
                ['fas fa-lock',        'Free WHOIS Privacy',         'Keep your personal information private with free WHOIS privacy protection on all domains.'],
                ['fas fa-headset',     '24/7 Support',               'Our expert team is available 24/7 to help with domain registration, transfer, and management.'],
                ['fas fa-tag',         'Best Prices',                'Get the lowest domain prices in the market with no hidden fees or surprise charges.'],
            ];
            foreach($why as $w): ?>
            <div class="col-lg-4 col-md-6 mb-30">
                <div class="why-card">
                    <div class="why-icon"><i class="<?= $w[0] ?>"></i></div>
                    <h5><?= $w[1] ?></h5>
                    <p><?= $w[2] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── FAQ ── -->
<section class="faq-area gray-bg pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-title text-center mb-60">
                    <span class="sub-title">FAQ</span>
                    <h2 class="title"><?= $t['faq_title'] ?></h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="faq-wrap">
                <?php
                $faqs = $isBD ? [
                    ['.xyz ডোমেইন কি?', '.xyz একটি জেনেরিক টপ-লেভেল ডোমেইন (gTLD) যা যেকোনো ব্যক্তি বা ব্যবসা ব্যবহার করতে পারে। এটি সাশ্রয়ী এবং আধুনিক।'],
                    ['.xyz ডোমেইনের দাম কত?', 'ResellNom-এ .xyz ডোমেইন মাত্র ৳149/বছর থেকে শুরু। নবায়ন মূল্য ৳899/বছর।'],
                    ['ডোমেইন কেনার পর কতক্ষণে অ্যাক্টিভ হয়?', 'পেমেন্টের সাথে সাথে ডোমেইন অ্যাক্টিভ হয়ে যায়। সাধারণত ৫-১৫ মিনিটের মধ্যে DNS প্রোপাগেট হয়।'],
                    ['ডোমেইনের সাথে হোস্টিং কি দরকার?', 'হ্যাঁ, ওয়েবসাইট চালু রাখতে হোস্টিং প্রয়োজন। আমাদের ওয়েব হোস্টিং প্ল্যান দেখুন।'],
                    ['ডোমেইন ট্রান্সফার করা যাবে?', 'হ্যাঁ, যেকোনো রেজিস্ট্রার থেকে আমাদের কাছে ডোমেইন ট্রান্সফার করা সম্ভব। সাপোর্টে যোগাযোগ করুন।'],
                ] : [
                    ['What is a .xyz domain?', '.xyz is a generic top-level domain (gTLD) that anyone can register. It\'s modern, affordable, and great for startups, tech brands, and creative projects.'],
                    ['How much does a .xyz domain cost?', 'At ResellNom, .xyz domains start from just ৳149/year for registration. Renewal price is ৳899/year.'],
                    ['How fast does my domain activate?', 'Your domain activates instantly after payment. DNS propagation usually completes within 5-15 minutes globally.'],
                    ['Do I need hosting with my domain?', 'You need hosting to run a website. Check out our affordable web hosting plans that pair perfectly with your domain.'],
                    ['Can I transfer my existing domain?', 'Yes! You can transfer domains from any registrar to ResellNom easily. Contact our support team for assistance.'],
                ];
                foreach($faqs as $i=>$faq): ?>
                <div class="faq-set">
                    <a <?= $i===0?'class="active"':'' ?> href="#"><?= $faq[0] ?><i class="fas fa-angle-<?= $i===0?'down':'up' ?>"></i></a>
                    <div class="content" <?= $i===0?'style="display:block;"':'' ?>>
                        <p><?= $faq[1] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Brand -->
<div class="brand-area gradient-bg">
    <div class="container">
        <div class="row brand-active">
            <div class="col-xl-2"><div class="brand-item text-center"><img src="img/brand/cpanel-whm.png" alt="cPanel"></div></div>
            <div class="col-xl-2"><div class="brand-item text-center"><img src="img/brand/plesk.png" alt="Plesk"></div></div>
            <div class="col-xl-2"><div class="brand-item text-center"><img src="img/brand/directadmin.png" alt="DirectAdmin"></div></div>
            <div class="col-xl-2"><div class="brand-item text-center"><img src="img/brand/cloudlinux.png" alt="CloudLinux"></div></div>
            <div class="col-xl-2"><div class="brand-item text-center"><img src="img/brand/ubuntu.png" alt="Ubuntu"></div></div>
            <div class="col-xl-2"><div class="brand-item text-center"><img src="img/brand/windowse.png" alt="Windows"></div></div>
        </div>
    </div>
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
// ── Domain availability check via WHMCS ──
var searchBtn   = document.getElementById('domainSearchBtn');
var domInput    = document.getElementById('domainInput');
var resultBox   = document.getElementById('domainResult');
var resName     = document.getElementById('resName');
var resStatus   = document.getElementById('resStatus');
var resAction   = document.getElementById('resAction');
var searching   = document.getElementById('domSearching');
var isBD        = <?= $isBD?'true':'false' ?>;

function checkDomain(){
    var raw  = domInput.value.trim().toLowerCase().replace(/https?:\/\//,'').replace(/\//g,'');
    if(!raw) return;
    // Add .xyz if no extension typed
    var domain = raw.indexOf('.')!==-1 ? raw : raw+'.xyz';
    resName.textContent  = domain;
    resStatus.textContent = '';
    resAction.innerHTML  = '';
    resultBox.classList.remove('show');
    searching.style.display = 'block';

    // Use WHMCS domain check endpoint
    var url = 'https://my.resellnom.com/cart.php?a=add&domain=register&query='+encodeURIComponent(domain)+'&domainsonly=1&ajax=1';
    fetch(url)
        .then(function(r){ return r.text(); })
        .then(function(html){
            searching.style.display='none';
            resultBox.classList.add('show');
            // Parse availability from WHMCS response
            var avail = html.toLowerCase().indexOf('unavailable')===-1 &&
                        html.toLowerCase().indexOf('taken')===-1;
            resStatus.textContent = avail
                ? (isBD?'✅ পাওয়া যাচ্ছে!':'✅ Available!')
                : (isBD?'❌ নেওয়া হয়ে গেছে':'❌ Already Taken');
            if(avail){
                resAction.innerHTML = '<a href="https://my.resellnom.com/cart.php?a=add&domain=register&query='+encodeURIComponent(domain)+'" target="_blank">'+(isBD?'রেজিস্ট্রেশন করুন →':'Register Now →')+'</a>';
            } else {
                // Suggest alternatives
                var base = domain.split('.')[0];
                resAction.innerHTML = '<a href="https://my.resellnom.com/cart.php?a=add&domain=register&query='+encodeURIComponent(base+'.xyz')+'" target="_blank">'+(isBD?'বিকল্প দেখুন':'Try Alternatives')+'</a>';
            }
        })
        .catch(function(){
            searching.style.display='none';
            resultBox.classList.add('show');
            resStatus.textContent = isBD?'⚠️ সরাসরি WHMCS-এ চেক করুন':'⚠️ Check on WHMCS';
            resAction.innerHTML = '<a href="https://my.resellnom.com/cart.php?a=add&domain=register&query='+encodeURIComponent(domain)+'" target="_blank">'+(isBD?'চেক করুন →':'Check Now →')+'</a>';
        });
}

searchBtn.addEventListener('click', checkDomain);
domInput.addEventListener('keydown', function(e){ if(e.key==='Enter') checkDomain(); });

// ── Currency conversion ──
var symbols = {BDT:'৳',USD:'$',EUR:'€',GBP:'£',INR:'₹',SAR:'﷼',AED:'د.إ',SGD:'S$',MYR:'RM ',CAD:'CA$',AUD:'A$'};
var rates = {BDT:1};
var ratesFetched = false;
var currentCurr  = 'BDT';

function syncDropdowns(curr){
    ['currSelect','headerCurrSelect'].forEach(function(id){
        var el=document.getElementById(id);
        if(el&&el.value!==curr) el.value=curr;
    });
}
function fmt(sym,v){
    return v>=1000?sym+Math.round(v).toLocaleString():v>=10?sym+Math.round(v):v>=1?sym+v.toFixed(2):sym+v.toFixed(3);
}
function applyConversion(curr,rate){
    currentCurr=curr;
    var sym=symbols[curr]||curr+' ';
    document.querySelectorAll('.geo-price').forEach(function(el){
        var bdt=parseFloat(el.getAttribute('data-bdt'));
        if(!isNaN(bdt)) el.textContent=fmt(sym,bdt*rate);
    });
    var info=document.getElementById('currRateInfo');
    if(info) info.textContent=curr==='BDT'?(isBD?'বেস মুদ্রা':'Base currency'):
        '1 BDT = '+rate.toFixed(4)+' '+curr+' (live)';
    syncDropdowns(curr);
    try{localStorage.setItem('rn_currency',curr);}catch(e){}
}
function fetchRates(curr){
    if(curr==='BDT'){applyConversion('BDT',1);return;}
    fetch('https://api.exchangerate-api.com/v4/latest/BDT')
        .then(function(r){return r.json();})
        .then(function(d){
            rates=d.rates||{BDT:1};rates.BDT=1;ratesFetched=true;
            applyConversion(curr,rates[curr]||1);
        })
        .catch(function(){
            var fb={BDT:1,USD:0.0091,EUR:0.0084,GBP:0.0072,INR:0.76,SAR:0.034,AED:0.033,SGD:0.012,MYR:0.042,CAD:0.012,AUD:0.014};
            rates=fb;applyConversion(curr,fb[curr]||1);
        });
}
['currSelect','headerCurrSelect'].forEach(function(id){
    var el=document.getElementById(id);
    if(el) el.addEventListener('change',function(){
        if(ratesFetched){applyConversion(this.value,rates[this.value]||1);}
        else{fetchRates(this.value);}
    });
});

// Init currency
var saved=null;
try{saved=localStorage.getItem('rn_currency');}catch(e){}
if(saved){syncDropdowns(saved);fetchRates(saved);}
else{
    fetch('https://ipapi.co/json/')
        .then(function(r){return r.json();})
        .then(function(d){
            var c=d.currency||'BDT';
            var sel=document.getElementById('currSelect');
            var valid=false;
            if(sel) for(var i=0;i<sel.options.length;i++) if(sel.options[i].value===c){valid=true;break;}
            var use=valid?c:'BDT';
            syncDropdowns(use);fetchRates(use);
        })
        .catch(function(){fetchRates('BDT');});
}

})();
</script>
</body>
</html>
