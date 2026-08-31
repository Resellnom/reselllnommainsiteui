<!-- header-area -->
<style>
/* ═══════════════════════════════════════════════
   RESELLNOM — REDESIGNED HEADER
   Fonts: Rubik (headings), Roboto (body)
   Colors: #01adef, #146af8, #213e6e, #303767
═══════════════════════════════════════════════ */

/* ── Top Bar ── */
.rn-topbar {
    background: linear-gradient(90deg, #0d1b40 0%, #1a2c6b 50%, #0d1b40 100%);
    border-bottom: 1px solid rgba(1,173,239,.15);
    padding: 7px 0;
    position: relative;
    z-index: 100;
}
.rn-topbar-inner {
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
}
/* Promo ticker */
.rn-promo {
    display: flex; align-items: center; gap: 10px;
}
.rn-promo-badge {
    background: linear-gradient(135deg, #01adef, #146af8);
    color: #fff; font-size: 10px; font-weight: 800;
    padding: 2px 9px; border-radius: 20px; text-transform: uppercase; letter-spacing: .5px;
    white-space: nowrap;
}
.rn-promo p {
    margin: 0; color: rgba(255,255,255,.85); font-size: 12.5px; font-family: 'Roboto',sans-serif;
}
.rn-promo a {
    color: #01adef; font-weight: 700; text-decoration: none; transition: color .2s;
}
.rn-promo a:hover { color: #fff; }

/* Top-right meta */
.rn-topmeta {
    display: flex; align-items: center; gap: 0; list-style: none; margin: 0; padding: 0;
}
.rn-topmeta li {
    display: flex; align-items: center; gap: 6px;
    color: rgba(255,255,255,.7); font-size: 12px; font-family: 'Roboto',sans-serif;
    padding: 0 14px; border-right: 1px solid rgba(255,255,255,.1);
}
.rn-topmeta li:last-child { border-right: none; padding-right: 0; }
.rn-topmeta li i { color: #01adef; font-size: 12px; }
.rn-topmeta li a { color: rgba(255,255,255,.8); text-decoration: none; transition: color .2s; }
.rn-topmeta li a:hover { color: #01adef; }

/* Currency select */
.rn-curr-wrap { display: flex; align-items: center; gap: 5px; }
.rn-curr-icon { color: #01adef; font-size: 12px; }
.rn-curr-select {
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(1,173,239,.3);
    border-radius: 20px;
    color: rgba(255,255,255,.9);
    font-size: 11.5px; font-weight: 700; font-family: 'Roboto',sans-serif;
    padding: 3px 24px 3px 10px;
    cursor: pointer; outline: none; appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='5'%3E%3Cpath d='M0 0l4 5 4-5z' fill='%2301adef'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 8px center;
    transition: border-color .2s, background .2s;
}
.rn-curr-select option { color: #222; background: #fff; }
.rn-curr-select:focus, .rn-curr-select:hover { border-color: #01adef; background-color: rgba(1,173,239,.12); }

/* ── Main Nav ── */
.rn-navbar {
    background: rgba(255,255,255,.97);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 2px 24px rgba(1,40,120,.08);
    position: relative; z-index: 99;
}
.rn-nav-inner {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0; min-height: 72px;
}
/* Logo */
.rn-logo img { width: 170px; display: block; transition: opacity .2s; }
.rn-logo img:hover { opacity: .85; }

/* Desktop nav links */
.rn-nav-links {
    display: flex; align-items: center; list-style: none; margin: 0; padding: 0; gap: 0; flex: 1; justify-content: center;
}
.rn-nav-links > li { position: relative; }
.rn-nav-links > li > a {
    display: flex; align-items: center; gap: 4px;
    font-family: 'Rubik',sans-serif; font-size: 13.5px; font-weight: 600;
    color: #213e6e; padding: 24px 18px; text-decoration: none;
    text-transform: uppercase; letter-spacing: .4px;
    transition: color .2s; white-space: nowrap;
    position: relative;
}
.rn-nav-links > li > a::after {
    content: ''; position: absolute; bottom: 0; left: 18px; right: 18px; height: 3px;
    background: linear-gradient(90deg,#01adef,#146af8);
    border-radius: 3px 3px 0 0;
    transform: scaleX(0); transform-origin: center;
    transition: transform .25s ease;
}
.rn-nav-links > li:hover > a,
.rn-nav-links > li.active > a { color: #01adef; }
.rn-nav-links > li:hover > a::after,
.rn-nav-links > li.active > a::after { transform: scaleX(1); }

/* Dropdown chevron */
.rn-nav-links > li > a .rn-chev {
    font-size: 9px; opacity: .5; transition: transform .2s;
}
.rn-nav-links > li:hover > a .rn-chev { transform: rotate(180deg); opacity: 1; }

/* Dropdown */
.rn-dropdown {
    position: absolute; top: calc(100% + 4px); left: 0;
    min-width: 220px;
    background: #fff;
    border-top: 3px solid #01adef;
    border-radius: 0 0 10px 10px;
    box-shadow: 0 20px 60px rgba(1,40,120,.12);
    padding: 10px 0; list-style: none; margin: 0;
    opacity: 0; visibility: hidden;
    transform: translateY(8px);
    transition: opacity .2s, transform .2s, visibility .2s;
    z-index: 200;
}
.rn-nav-links > li:hover .rn-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }
.rn-dropdown li a {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 20px; font-size: 13px; font-family: 'Roboto',sans-serif;
    font-weight: 500; color: #444; text-decoration: none;
    transition: background .15s, color .15s;
}
.rn-dropdown li a:hover { background: #f0f7ff; color: #01adef; padding-left: 26px; }
.rn-dropdown li a i { color: #01adef; font-size: 11px; width: 14px; }

/* Divider in dropdown */
.rn-dropdown .rn-drop-divider { height: 1px; background: #f0f0f0; margin: 6px 0; }

/* Nav right */
.rn-nav-right { display: flex; align-items: center; gap: 10px; }
.rn-client-btn {
    display: flex; align-items: center; gap: 7px;
    padding: 10px 20px;
    background: linear-gradient(135deg,#01adef 0%,#146af8 100%);
    color: #fff !important; font-family: 'Rubik',sans-serif;
    font-size: 13px; font-weight: 700;
    border-radius: 30px; text-decoration: none;
    box-shadow: 0 6px 20px rgba(1,173,239,.35);
    transition: transform .2s, box-shadow .2s;
    white-space: nowrap;
}
.rn-client-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(1,173,239,.45);
    color: #fff !important; text-decoration: none;
}
.rn-client-btn i { font-size: 13px; }

/* Dot status */
.rn-status-dot {
    display: flex; align-items: center; gap: 5px;
    font-size: 11px; color: #888; font-family: 'Roboto',sans-serif;
    white-space: nowrap;
}
.rn-status-dot::before {
    content: ''; width: 7px; height: 7px; border-radius: 50%;
    background: #2acb35;
    box-shadow: 0 0 0 2px rgba(42,203,53,.25);
    animation: rnPulse 2s infinite;
}
@keyframes rnPulse {
    0%,100% { box-shadow: 0 0 0 2px rgba(42,203,53,.25); }
    50%      { box-shadow: 0 0 0 5px rgba(42,203,53,.1); }
}

/* Sticky */
.rn-navbar.sticky {
    position: fixed; top: 0; left: 0; width: 100%;
    animation: rnSlideDown .3s ease;
    box-shadow: 0 4px 30px rgba(1,40,120,.12);
}
@keyframes rnSlideDown { from { transform:translateY(-100%); } to { transform:translateY(0); } }

/* Mobile toggle */
.rn-mobile-toggle {
    display: none; background: none; border: 2px solid #01adef;
    border-radius: 8px; padding: 6px 10px; cursor: pointer; color: #01adef; font-size: 18px;
}

/* Mobile menu */
.rn-mobile-menu {
    display: none; position: fixed; inset: 0; z-index: 9999;
}
.rn-mobile-overlay {
    position: absolute; inset: 0; background: rgba(0,0,0,.55); cursor: pointer;
}
.rn-mobile-panel {
    position: absolute; top: 0; left: 0; width: 300px; height: 100%;
    background: #0d1b40; overflow-y: auto;
    transform: translateX(-100%); transition: transform .3s ease;
}
.rn-mobile-menu.open .rn-mobile-panel { transform: translateX(0); }
.rn-mobile-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 20px;
    border-bottom: 1px solid rgba(255,255,255,.08);
}
.rn-mobile-head img { width: 130px; }
.rn-mobile-close {
    background: none; border: none; color: #fff; font-size: 22px; cursor: pointer;
}
.rn-mobile-nav { list-style: none; margin: 0; padding: 10px 0; }
.rn-mobile-nav li a {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 22px; font-size: 14px; font-family: 'Rubik',sans-serif;
    font-weight: 600; color: rgba(255,255,255,.85); text-decoration: none;
    border-bottom: 1px solid rgba(255,255,255,.05);
    transition: color .2s, background .2s;
}
.rn-mobile-nav li a:hover { color: #01adef; background: rgba(1,173,239,.07); }
.rn-mobile-sub { list-style: none; padding: 0; margin: 0; display: none; background: rgba(0,0,0,.2); }
.rn-mobile-sub.open { display: block; }
.rn-mobile-sub li a {
    padding: 10px 22px 10px 36px; font-size: 13px; font-weight: 400;
    color: rgba(255,255,255,.6);
}
.rn-mobile-sub li a:hover { color: #01adef; }
.rn-mobile-footer {
    padding: 20px; border-top: 1px solid rgba(255,255,255,.08); margin-top: 10px;
}

@media(max-width:991px){
    .rn-nav-links, .rn-nav-right .rn-status-dot { display: none; }
    .rn-mobile-toggle { display: block; }
}
@media(max-width:576px){
    .rn-promo { display: none; }
    .rn-topmeta li:not(:last-child) { display: none; }
}
</style>

<header id="rnHeader">

    <!-- ── TOP BAR ── -->
    <div class="rn-topbar">
        <div class="container custom-container">
            <div class="rn-topbar-inner">

                <!-- Promo -->
                <div class="rn-promo d-none d-md-flex">
                    <span class="rn-promo-badge">🔥 Hot Deal</span>
                    <p> Hosting Starting at <strong>৳99/mo</strong> — <a href="https://my.resellnom.com/index.php?rp=/store/we">Order Now →</a></p>
                </div>

                <!-- Meta + Currency -->
                <ul class="rn-topmeta">
                    <li>
                        <i class="far fa-envelope"></i>
                        <a href="mailto:sales@resellnom.com">sales@resellnom.com</a>
                    </li>
                    <li>
                        <i class="fas fa-headset"></i>
                        <a href="https://my.resellnom.com/submitticket.php">24/7 Support</a>
                    </li>
                    <li>
                        <div class="rn-curr-wrap">
                            <i class="fas fa-coins rn-curr-icon"></i>
                            <select id="headerCurrSelect" class="rn-curr-select">
                                <option value="BDT">৳ BDT</option>
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
                        </div>
                    </li>
                </ul>

            </div>
        </div>
    </div>

    <!-- ── MAIN NAV ── -->
    <div class="rn-navbar" id="rnNavbar">
        <div class="container custom-container">
            <div class="rn-nav-inner">

                <!-- Logo -->
                <a href="/" class="rn-logo">
                    <img src="img/logo/logo.png" alt="ResellNom — Web Hosting, VPS, Reseller Bangladesh">
                </a>

                <!-- Desktop Links -->
                <ul class="rn-nav-links">

                    <li class="active"><a href="/">Home</a></li>

                    <li>
                        <a href="#">Hosting <i class="fas fa-chevron-down rn-chev"></i></a>
                        <ul class="rn-dropdown">
                            <li><a href="web-hosting.php"><i class="fas fa-globe"></i>Web Hosting</a></li>
                            <li><a href="https://my.resellnom.com/index.php?rp=/store/reseller"><i class="fas fa-users"></i>Reseller Hosting</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="#">Storage Server <i class="fas fa-chevron-down rn-chev"></i></a>
                        <ul class="rn-dropdown">
                            <li><a href="/bdix-storage.php"><i class="fas fa-server"></i>BDIX Storage VPS</a></li>
                            <li><a href="/ram-optimized-bdix-vps.php"><i class="fas fa-memory"></i>S3 Storage</a></li>
                            <li><a href="/storage-vps.php"><i class="fas fa-microchip"></i>Storage VPS</a></li>
                        </ul>
                    </li>
<li>
                        <a href="#">VPS <i class="fas fa-chevron-down rn-chev"></i></a>
                        <ul class="rn-dropdown">
                            <li><a href="/bdix-vps.php"><i class="fas fa-server"></i>Cloud BDIX VPS</a></li>
                            <li><a href="/ram-optimized-bdix-vps.php"><i class="fas fa-memory"></i>RAM Optimized VPS</a></li>
                            <li><a href="/cpu-optimized-bdix-vps.php"><i class="fas fa-microchip"></i>CPU Optimized VPS</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Dedicated <i class="fas fa-chevron-down rn-chev"></i></a>
                        <ul class="rn-dropdown">
                            <li><a href="contact.php"><i class="fas fa-hdd"></i>BDIX Dedicated</a></li>
                            <li><a href="contact.php"><i class="fas fa-flag-usa"></i>USA Dedicated</a></li>
                            <li><a href="contact.php"><i class="fas fa-globe-asia"></i>Singapore Dedicated</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="#">Company <i class="fas fa-chevron-down rn-chev"></i></a>
                        <ul class="rn-dropdown">
                            <li><a href="about-us.php"><i class="fas fa-info-circle"></i>About Us</a></li>
                            <li><a href="contact.php"><i class="fas fa-envelope"></i>Contact</a></li>
                        </ul>
                    </li>

                </ul>

                <!-- Right Actions -->
                <div class="rn-nav-right">
                    <span class="rn-status-dot d-none d-xl-flex">All Systems Operational</span>
                    <a href="https://my.resellnom.com" class="rn-client-btn">
                        <i class="fas fa-user-circle"></i> Client Area
                    </a>
                    <button class="rn-mobile-toggle" id="rnMobileToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>

</header>

<!-- ── MOBILE MENU ── -->
<div class="rn-mobile-menu" id="rnMobileMenu">
    <div class="rn-mobile-overlay" id="rnMobileOverlay"></div>
    <div class="rn-mobile-panel">
        <div class="rn-mobile-head">
            <img src="img/logo/w_logo.png" alt="ResellNom">
            <button class="rn-mobile-close" id="rnMobileClose"><i class="fas fa-times"></i></button>
        </div>
        <ul class="rn-mobile-nav">
            <li><a href="/">Home</a></li>
            <li>
                <a href="#" class="rn-mob-parent" data-target="mob-hosting">
                    Hosting <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="rn-mobile-sub" id="mob-hosting">
                    <li><a href="web-hosting.php">Web Hosting</a></li>
                    <li><a href="reseller-hosting.php">Reseller Hosting</a></li>
                </ul>
            </li>
            <li>
                <a href="#" class="rn-mob-parent" data-target="mob-vps">
                    VPS <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="rn-mobile-sub" id="mob-vps">
                    <li><a href="/bdix-vps.php">Cloud BDIX VPS</a></li>
                    <li><a href="/ram-optimized-bdix-vps.php">RAM Optimized VPS</a></li>
                    <li><a href="/cpu-optimized-bdix-vps.php">CPU Optimized VPS</a></li>
                </ul>
            </li>
            <li>
                <a href="#" class="rn-mob-parent" data-target="mob-ded">
                    Dedicated <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="rn-mobile-sub" id="mob-ded">
                    <li><a href="contact.php">BDIX Dedicated</a></li>
                    <li><a href="contact.php">USA Dedicated</a></li>
                    <li><a href="contact.php">Singapore Dedicated</a></li>
                </ul>
            </li>
            <li><a href="about-us.php">About Us</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <div class="rn-mobile-footer">
            <a href="https://my.resellnom.com" class="rn-client-btn" style="justify-content:center;">
                <i class="fas fa-user-circle"></i> Client Area
            </a>
        </div>
    </div>
</div>

<script>
(function(){
    // ── Sticky navbar ──
    var navbar = document.getElementById('rnNavbar');
    var topbarH = document.querySelector('.rn-topbar') ? document.querySelector('.rn-topbar').offsetHeight : 40;
    window.addEventListener('scroll', function(){
        if(window.scrollY > topbarH){ navbar.classList.add('sticky'); }
        else { navbar.classList.remove('sticky'); }
    });

    // ── Mobile menu ──
    var menu    = document.getElementById('rnMobileMenu');
    var overlay = document.getElementById('rnMobileOverlay');
    document.getElementById('rnMobileToggle').addEventListener('click', function(){
        menu.style.display = 'block';
        setTimeout(function(){ menu.classList.add('open'); }, 10);
    });
    function closeMenu(){
        menu.classList.remove('open');
        setTimeout(function(){ menu.style.display = 'none'; }, 300);
    }
    document.getElementById('rnMobileClose').addEventListener('click', closeMenu);
    overlay.addEventListener('click', closeMenu);

    // Mobile accordion
    document.querySelectorAll('.rn-mob-parent').forEach(function(el){
        el.addEventListener('click', function(e){
            e.preventDefault();
            var sub = document.getElementById(this.getAttribute('data-target'));
            if(sub){ sub.classList.toggle('open'); }
        });
    });

    // ── Currency system ──
    var symbols = {
        BDT:'৳', USD:'$', EUR:'€', GBP:'£', INR:'₹',
        SAR:'﷼', AED:'د.إ', SGD:'S$', MYR:'RM ', CAD:'CA$', AUD:'A$'
    };
    var rates = {BDT:1};
    var ratesFetched = false;
    var currentCurr  = 'BDT';

    function syncDropdowns(curr){
        ['headerCurrSelect','currSelect'].forEach(function(id){
            var el = document.getElementById(id);
            if(el && el.value !== curr) el.value = curr;
        });
    }
    function applyConversion(curr, rate){
        currentCurr = curr;
        var sym = symbols[curr] || (curr + ' ');
        document.querySelectorAll('.svc-price[data-bdt]').forEach(function(el){
            var bdt = parseFloat(el.getAttribute('data-bdt'));
            if(isNaN(bdt)) return;
            var v = bdt * rate;
            var f = v >= 1000 ? sym + Math.round(v).toLocaleString()
                  : v >= 10  ? sym + Math.round(v)
                  : v >= 1   ? sym + v.toFixed(2)
                  :             sym + v.toFixed(3);
            el.textContent = f;
        });
        var info = document.getElementById('currRateInfo');
        if(info) info.textContent = curr==='BDT' ? 'Base currency' :
            '1 BDT = ' + rate.toFixed(4) + ' ' + curr + ' (live)';
        syncDropdowns(curr);
        try{ localStorage.setItem('rn_currency', curr); }catch(e){}
    }
    function fetchRates(curr){
        if(curr==='BDT'){ applyConversion('BDT',1); return; }
        fetch('https://api.exchangerate-api.com/v4/latest/BDT')
            .then(function(r){ return r.json(); })
            .then(function(d){
                rates = d.rates || {BDT:1}; rates.BDT=1; ratesFetched=true;
                applyConversion(curr, rates[curr]||1);
            })
            .catch(function(){
                var fb={BDT:1,USD:0.0091,EUR:0.0084,GBP:0.0072,INR:0.76,
                        SAR:0.034,AED:0.033,SGD:0.012,MYR:0.042,CAD:0.012,AUD:0.014};
                rates=fb; applyConversion(curr, fb[curr]||1);
            });
    }
    function onCurrChange(curr){
        if(ratesFetched){ applyConversion(curr, rates[curr]||1); }
        else { fetchRates(curr); }
    }
    function attachSelect(id){
        var el = document.getElementById(id);
        if(el) el.addEventListener('change', function(){ onCurrChange(this.value); });
    }

    window.addEventListener('DOMContentLoaded', function(){
        attachSelect('headerCurrSelect');
        attachSelect('currSelect');
        var saved = null;
        try{ saved = localStorage.getItem('rn_currency'); }catch(e){}
        if(saved){ syncDropdowns(saved); fetchRates(saved); }
        else {
            fetch('https://ipapi.co/json/')
                .then(function(r){ return r.json(); })
                .then(function(d){
                    var c = d.currency || 'BDT';
                    var sel = document.getElementById('headerCurrSelect');
                    var valid = false;
                    if(sel) for(var i=0;i<sel.options.length;i++) if(sel.options[i].value===c){ valid=true; break; }
                    var use = valid ? c : 'BDT';
                    syncDropdowns(use); fetchRates(use);
                })
                .catch(function(){ fetchRates('BDT'); });
        }
    });
})();
</script>