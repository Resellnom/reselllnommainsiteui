<?php
/*
|--------------------------------------------------------------------------
| ResellNom Support Payment Page
|--------------------------------------------------------------------------
| File: index.php
|--------------------------------------------------------------------------
*/

$shurjoPaymentUrl = 'https://engine.shurjopayment.com/api/pay-amount-with-link/eyJpdiI6InBJcEk5S2ZzMCt5N3pmbnN3aUErOGc9PSIsInZhbHVlIjoicTZ0MDZxUXlKeUVvRjRaT0ExR29ydz09IiwibWFjIjoiNGEyYjQxMjZjNWQ0MzM2MzU4NmFhNjVmOGNjN2Y2NTMxM2QyNmM1ODliNzBmMzdlNzY5MWE0Mzk1ZGMyYmU1ZSJ9/eyJpdiI6Ik1RS1hTbGIremlLRWpkRS8yb0hpWEE9PSIsInZhbHVlIjoiS1E5Z3pqOGNsWS9CUzB0WG13RFQ4dz09IiwibWFjIjoiMWU5YzJkZTc3Mjc4Njk1NGZlZTBjMzZhNWZmOTY4MmE0NGY1ODg1YTc1OWMyNDViNzk4MjVhM2M1NGQ0NjlkOCJ9/eyJpdiI6IlVLUGNNd1k4eUNxWTk0VXVlbFJXbUE9PSIsInZhbHVlIjoicXc4VUFqRTRDOFJMT2NtU2owaUhNZz09IiwibWFjIjoiMjBiYjgzMjBmM2UxZGNkMDRlMzMxMTIzOTUzZmE3YzUzN2FkNTllNGFjMmI1MTMyNzQ4YjZlMmYxMDI3ZWQwZCJ9/eyJpdiI6IjlybFFYS2pRUXo4OFlxSDRORUtPTUE9PSIsInZhbHVlIjoiZ0dRN0VtTE9sYlZaNmJBMFIvc2ViQT09IiwibWFjIjoiYTUwYmE5NDI1MzdiNDJiY2E4YmIwNjIzMjBjODM4ZDBhMjRjMjMyNjg5ZDlhNjdlMWJjZmMxZTZmZmMzNWE1ZSJ9/eyJpdiI6Im9YT1hsbC9PNHIycGZ3dzFaT2wxNnc9PSIsInZhbHVlIjoiMWtDa2MvY25URVJYVkNYeWh1enJQZz09IiwibWFjIjoiNjE1ZmVjNTE5NDMwYWU0ODcwOGM3NWQ4ZmMzMGY4ODUxNmM5YWI2NjZjZjEwNDhhZGM0MTIyZWQxMmU2Njg1YiJ9';

$cryptoAddress = '0x9c3d4f8aeb2eb9ab499047d43a7e18fd8212d9f8';
?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Support ResellNom</title>

<meta name="description"
content="Support ResellNom through secure payment or crypto donation.">

<style>

:root{
    --bg:#06080d;
    --card:#0d1119;
    --card2:#111722;
    --border:rgba(255,255,255,.09);
    --text:#f7f8fa;
    --muted:#8d96a5;
    --green:#19e68c;
    --green2:#08b96e;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

html{
    scroll-behavior:smooth;
}

body{
    min-height:100vh;
    font-family:
        Inter,
        ui-sans-serif,
        system-ui,
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        sans-serif;

    background:
        radial-gradient(
            circle at 50% -10%,
            rgba(25,230,140,.13),
            transparent 35%
        ),
        radial-gradient(
            circle at 10% 90%,
            rgba(0,120,255,.07),
            transparent 30%
        ),
        var(--bg);

    color:var(--text);
    display:flex;
    align-items:center;
    justify-content:center;

    padding:24px;

    overflow-x:hidden;
}


/* Background decoration */

body::before{
    content:"";
    position:fixed;

    width:500px;
    height:500px;

    border-radius:50%;

    background:
        radial-gradient(
            circle,
            rgba(25,230,140,.08),
            transparent 65%
        );

    top:-250px;
    left:50%;
    transform:translateX(-50%);

    pointer-events:none;
}


/* Main */

.wrapper{
    width:100%;
    max-width:460px;

    position:relative;
    z-index:2;
}


/* Brand */

.brand{
    text-align:center;
    margin-bottom:22px;
}

.logo{
    width:64px;
    height:64px;

    margin:0 auto 15px;

    display:flex;
    align-items:center;
    justify-content:center;

    border-radius:19px;

    background:
        linear-gradient(
            145deg,
            #1cf29a,
            #079b60
        );

    color:#04120b;

    font-size:20px;
    font-weight:900;

    letter-spacing:-1px;

    box-shadow:
        0 12px 45px rgba(25,230,140,.20);

    position:relative;
}

.logo::after{
    content:"";

    position:absolute;

    inset:1px;

    border-radius:18px;

    border:1px solid rgba(255,255,255,.25);
}

.brand-name{
    font-size:15px;
    font-weight:800;

    letter-spacing:3px;

    color:#fff;
}

.brand-since{
    margin-top:6px;

    color:#6f7888;

    font-size:11px;

    letter-spacing:1.5px;
}


/* Card */

.payment-card{
    background:
        linear-gradient(
            145deg,
            rgba(18,24,34,.97),
            rgba(10,14,21,.98)
        );

    border:1px solid var(--border);

    border-radius:27px;

    padding:30px;

    box-shadow:
        0 30px 90px rgba(0,0,0,.45),
        inset 0 1px 0 rgba(255,255,255,.035);
}


/* Heading */

.heading{
    text-align:center;

    margin-bottom:25px;
}

.heading h1{
    font-size:27px;

    letter-spacing:-.7px;

    margin-bottom:8px;
}

.heading p{
    color:var(--muted);

    font-size:13px;

    line-height:1.6;
}


/* Secure badge */

.secure{
    display:flex;
    align-items:center;
    justify-content:center;

    gap:7px;

    margin-bottom:18px;
}

.secure-dot{
    width:7px;
    height:7px;

    background:var(--green);

    border-radius:50%;

    box-shadow:
        0 0 12px rgba(25,230,140,.8);
}

.secure span{
    color:#9ba4b3;

    font-size:11px;

    letter-spacing:.4px;
}


/* Shurjo button */

.shurjo{
    position:relative;

    display:flex;

    align-items:center;
    justify-content:center;

    gap:11px;

    width:100%;
    height:57px;

    border-radius:15px;

    text-decoration:none;

    color:#03120b;

    font-size:15px;
    font-weight:800;

    background:
        linear-gradient(
            135deg,
            #25f09a,
            #0cc477
        );

    box-shadow:
        0 12px 30px rgba(25,230,140,.16);

    transition:
        transform .2s ease,
        box-shadow .2s ease;
}

.shurjo:hover{
    transform:translateY(-2px);

    box-shadow:
        0 17px 38px rgba(25,230,140,.25);
}

.shurjo:active{
    transform:translateY(0);
}

.shurjo-icon{
    width:25px;
    height:25px;

    display:flex;
    align-items:center;
    justify-content:center;

    border-radius:8px;

    background:rgba(0,0,0,.12);

    font-size:14px;
}


/* Divider */

.divider{
    display:flex;

    align-items:center;

    gap:12px;

    margin:27px 0;

    color:#596273;

    font-size:10px;

    font-weight:700;

    letter-spacing:1.4px;
}

.divider::before,
.divider::after{
    content:"";

    height:1px;

    flex:1;

    background:
        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.10)
        );
}

.divider::after{
    background:
        linear-gradient(
            90deg,
            rgba(255,255,255,.10),
            transparent
        );
}


/* Crypto */

.crypto{
    border:1px solid rgba(255,255,255,.07);

    border-radius:17px;

    background:
        rgba(255,255,255,.018);

    padding:18px;
}

.crypto-head{
    display:flex;

    align-items:center;

    gap:11px;

    margin-bottom:15px;
}

.crypto-icon{
    width:38px;
    height:38px;

    display:flex;

    align-items:center;
    justify-content:center;

    border-radius:11px;

    background:#171d28;

    font-size:18px;
}

.crypto-title strong{
    display:block;

    font-size:13px;

    margin-bottom:3px;
}

.crypto-title span{
    color:#717b8b;

    font-size:10px;
}


/* Wallet */

.wallet-label{
    color:#697384;

    font-size:10px;

    text-transform:uppercase;

    letter-spacing:1px;

    margin-bottom:7px;
}

.wallet{
    position:relative;

    padding:13px 42px 13px 13px;

    background:#070a10;

    border:1px solid rgba(255,255,255,.06);

    border-radius:11px;

    font-family:
        "SFMono-Regular",
        Consolas,
        monospace;

    font-size:10px;

    line-height:1.6;

    color:#b8c0cc;

    word-break:break-all;
}


/* Copy */

.copy{
    width:100%;

    height:43px;

    margin-top:9px;

    border:1px solid rgba(255,255,255,.08);

    border-radius:11px;

    background:#141a24;

    color:#d8dde5;

    cursor:pointer;

    font-size:12px;

    font-weight:700;

    transition:.2s;
}

.copy:hover{
    background:#1a2230;

    border-color:rgba(25,230,140,.25);
}

.copy.success{
    background:rgba(25,230,140,.10);

    color:#36ed9e;

    border-color:rgba(25,230,140,.25);
}


/* Warning */

.notice{
    display:flex;

    gap:8px;

    margin-top:13px;

    color:#737d8d;

    font-size:10px;

    line-height:1.55;
}

.notice-icon{
    color:#d9a52e;

    flex-shrink:0;
}


/* Footer */

.footer{
    text-align:center;

    margin-top:18px;
}

.footer-main{
    color:#626c7b;

    font-size:10px;

    letter-spacing:.5px;
}

.footer-main b{
    color:#8993a3;
}

.support{
    margin-top:7px;

    color:#4f5867;

    font-size:10px;
}


/* Mobile */

@media(max-width:500px){

    body{
        padding:15px;
    }

    .payment-card{
        padding:23px;

        border-radius:23px;
    }

    .heading h1{
        font-size:24px;
    }

    .logo{
        width:59px;
        height:59px;
    }
}

</style>

</head>

<body>


<div class="wrapper">


    <!-- BRAND -->

    <div class="brand">

        <div class="logo">
            RN
        </div>

        <div class="brand-name">
            RESELLNOM
        </div>

        <div class="brand-since">
            SINCE 2021
        </div>

    </div>


    <!-- PAYMENT CARD -->

    <div class="payment-card">


        <div class="heading">

            <h1>
                Support ResellNom
            </h1>

            <p>
                Choose your preferred payment method
                to support our services.
            </p>

        </div>


        <!-- Secure -->

        <div class="secure">

            <div class="secure-dot"></div>

            <span>
                Secure payment experience
            </span>

        </div>


        <!-- ShurjoPayment -->

        <a
            href="<?php echo htmlspecialchars(
                $shurjoPaymentUrl,
                ENT_QUOTES,
                'UTF-8'
            ); ?>"
            class="shurjo"
            target="_blank"
            rel="noopener noreferrer"
        >

            <span class="shurjo-icon">
                ↗
            </span>

            Pay with ShurjoPayment

        </a>


        <!-- Divider -->

        <div class="divider">
            OR CRYPTO
        </div>


        <!-- Crypto -->

        <div class="crypto">


            <div class="crypto-head">

                <div class="crypto-icon">
                    ₿
                </div>

                <div class="crypto-title">

                    <strong>
                        Crypto Payment
                    </strong>

                    <span>
                        BTC • BEP20 / BSC
                    </span>

                </div>

            </div>


            <div class="wallet-label">
                Wallet Address
            </div>


            <div
                class="wallet"
                id="wallet"
            >
                <?php echo htmlspecialchars(
                    $cryptoAddress,
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>
            </div>


            <button
                type="button"
                class="copy"
                id="copyButton"
                onclick="copyWallet()"
            >
                Copy Wallet Address
            </button>


            <div class="notice">

                <span class="notice-icon">
                    ⚠
                </span>

                <span>
                    Please verify the asset and network
                    before sending. Transactions sent through
                    an unsupported network may be permanently lost.
                </span>

            </div>


        </div>


    </div>


    <!-- FOOTER -->

    <div class="footer">

        <div class="footer-main">
            <b>RESELLNOM</b> · Trusted since 2021
        </div>

        <div class="support">
            WhatsApp Support · 01336126969 · Text Only
        </div>

    </div>


</div>


<script>

function copyWallet(){

    const wallet =
        document
        .getElementById('wallet')
        .innerText
        .trim();

    const button =
        document.getElementById('copyButton');


    function success(){

        button.classList.add('success');

        button.innerText =
            '✓ Wallet Address Copied';

        setTimeout(function(){

            button.classList.remove('success');

            button.innerText =
                'Copy Wallet Address';

        },2000);

    }


    if(
        navigator.clipboard &&
        window.isSecureContext
    ){

        navigator.clipboard
            .writeText(wallet)
            .then(success)
            .catch(function(){

                fallbackCopy(wallet);

            });

    }else{

        fallbackCopy(wallet);

    }

}


function fallbackCopy(text){

    const textarea =
        document.createElement('textarea');

    textarea.value = text;

    textarea.style.position =
        'fixed';

    textarea.style.opacity = '0';

    document.body.appendChild(textarea);

    textarea.select();

    try{

        document.execCommand('copy');

        const button =
            document.getElementById('copyButton');

        button.classList.add('success');

        button.innerText =
            '✓ Wallet Address Copied';

        setTimeout(function(){

            button.classList.remove('success');

            button.innerText =
                'Copy Wallet Address';

        },2000);

    }catch(e){

        alert(
            'Copy failed. Please copy the wallet address manually.'
        );

    }

    document.body.removeChild(textarea);

}

</script>


</body>
</html>