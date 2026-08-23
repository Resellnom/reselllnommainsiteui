<?php
// ═══════════════════════════════════════════════════
// ResellNom Cache Manager — Secure one-click clear
// ═══════════════════════════════════════════════════

// ── SECURITY: Change this token to something secret ──
define('CACHE_TOKEN', 'resellnom2025secret');

$token   = $_GET['token'] ?? '';
$action  = $_GET['action'] ?? '';
$allowed = hash_equals(CACHE_TOKEN, $token);

// Handle clear action
$result = [];
if ($allowed && $action === 'clear') {
    $patterns = [
        'WHMCS Product Names/Prices' => '/tmp/rn_wh_*.txt',
        'WHMCS JSON Products'        => '/tmp/rn_prod_*.json',
        'WHMCS Prices'               => '/tmp/rn_price_*.txt',
        'Geo/IP Detection'           => '/tmp/rn_geo_*.txt',
        'Domain Prices'              => '/tmp/rn_dom_*.txt',
    ];
    foreach ($patterns as $label => $pattern) {
        $files   = glob($pattern) ?: [];
        $deleted = 0;
        foreach ($files as $f) { if (unlink($f)) $deleted++; }
        $result[] = ['label' => $label, 'count' => $deleted, 'pattern' => $pattern];
    }
}

// List current cache files
$cache_files = [];
foreach (glob('/tmp/rn_*.{txt,json}', GLOB_BRACE) ?: [] as $f) {
    $cache_files[] = [
        'file'    => basename($f),
        'size'    => filesize($f),
        'age_min' => round((time() - filemtime($f)) / 60),
        'preview' => strlen(file_get_contents($f)) < 200 ? htmlspecialchars(file_get_contents($f)) : htmlspecialchars(substr(file_get_contents($f), 0, 150)) . '...',
    ];
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Cache Manager — ResellNom</title>
<meta name="robots" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Segoe UI',sans-serif;background:#f0f4ff;min-height:100vh;padding:30px 16px;}
.wrap{max-width:860px;margin:0 auto;}
.card{background:#fff;border-radius:14px;box-shadow:0 4px 24px rgba(1,40,120,.08);padding:28px 32px;margin-bottom:24px;}
h1{font-size:22px;color:#213e6e;margin-bottom:4px;display:flex;align-items:center;gap:10px;}
h1 span{font-size:14px;font-weight:400;color:#aaa;}
.subtitle{color:#888;font-size:13px;margin-bottom:24px;}
/* Buttons */
.btn-clear{display:inline-flex;align-items:center;gap:8px;padding:13px 28px;background:linear-gradient(135deg,#dc3545,#c82333);color:#fff;border-radius:30px;text-decoration:none;font-weight:700;font-size:14px;transition:transform .2s,box-shadow .2s;}
.btn-clear:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(220,53,69,.35);color:#fff;text-decoration:none;}
.btn-view{display:inline-flex;align-items:center;gap:8px;padding:13px 28px;background:linear-gradient(135deg,#01adef,#146af8);color:#fff;border-radius:30px;text-decoration:none;font-weight:700;font-size:14px;transition:transform .2s,box-shadow .2s;margin-left:10px;}
.btn-view:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(1,173,239,.35);color:#fff;text-decoration:none;}
/* Result */
.result-row{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-radius:8px;margin-bottom:8px;font-size:14px;}
.result-row.ok{background:#e8fff0;color:#155724;}
.result-row.empty{background:#f8f9fa;color:#999;}
.result-count{font-weight:800;font-size:18px;min-width:30px;text-align:center;}
/* Cache table */
table{width:100%;border-collapse:collapse;font-size:13px;}
table th{background:linear-gradient(135deg,#01adef,#146af8);color:#fff;padding:11px 14px;text-align:left;font-weight:700;}
table td{padding:10px 14px;border-bottom:1px solid #f0f4ff;vertical-align:top;color:#555;}
table tr:last-child td{border-bottom:none;}
table tr:hover td{background:#f7fbff;}
.tag-age{background:#e8f4ff;color:#01adef;font-size:11px;font-weight:700;padding:2px 8px;border-radius:10px;}
.tag-fresh{background:#e8fff0;color:#155724;font-size:11px;font-weight:700;padding:2px 8px;border-radius:10px;}
.preview-text{font-family:monospace;font-size:11px;color:#888;word-break:break-all;max-width:340px;}
/* Security */
.lock-form{display:flex;gap:10px;align-items:center;flex-wrap:wrap;}
.lock-input{padding:11px 16px;border:2px solid #e0e7ff;border-radius:30px;font-size:14px;outline:none;width:260px;transition:border-color .2s;}
.lock-input:focus{border-color:#01adef;}
.lock-btn{padding:11px 24px;background:linear-gradient(135deg,#01adef,#146af8);color:#fff;border:none;border-radius:30px;font-weight:700;font-size:14px;cursor:pointer;}
.alert{padding:14px 20px;border-radius:10px;font-size:14px;margin-bottom:20px;}
.alert-danger{background:#fff0f0;border:1px solid #ffcdd2;color:#c62828;}
.alert-success{background:#e8fff0;border:1px solid #c8e6c9;color:#1b5e20;}
.stat-bar{display:flex;gap:16px;flex-wrap:wrap;margin-bottom:20px;}
.stat{background:#f0f4ff;border-radius:10px;padding:14px 20px;text-align:center;flex:1;min-width:120px;}
.stat-num{font-size:24px;font-weight:800;color:#01adef;}
.stat-label{font-size:12px;color:#888;margin-top:2px;}
</style>
</head>
<body>
<div class="wrap">

    <!-- Header -->
    <div class="card">
        <h1>🗄️ Cache Manager <span>ResellNom</span></h1>
        <p class="subtitle">Manage server-side cache for WHMCS pricing, product names, and geo-detection data.</p>

        <?php if (!$allowed): ?>
        <!-- Auth Form -->
        <form method="GET" class="lock-form">
            <input type="text" name="token" class="lock-input" placeholder="Enter security token..." autocomplete="off" required>
            <input type="hidden" name="action" value="view">
            <button type="submit" class="lock-btn">🔐 Unlock</button>
        </form>
        <?php if (isset($_GET['token']) && !$allowed): ?>
        <div class="alert alert-danger" style="margin-top:14px;">❌ Invalid token. Access denied.</div>
        <?php endif; ?>

        <?php else: ?>
        <!-- Stats -->
        <div class="stat-bar">
            <div class="stat">
                <div class="stat-num"><?=count($cache_files)?></div>
                <div class="stat-label">Cache Files</div>
            </div>
            <div class="stat">
                <div class="stat-num"><?=array_sum(array_column($cache_files,'size'))?> B</div>
                <div class="stat-label">Total Size</div>
            </div>
            <div class="stat">
                <div class="stat-num"><?=count(array_filter($cache_files,fn($f)=>$f['age_min']<30))?></div>
                <div class="stat-label">Fresh (&lt;30m)</div>
            </div>
            <div class="stat">
                <div class="stat-num"><?=count(array_filter($cache_files,fn($f)=>$f['age_min']>=30))?></div>
                <div class="stat-label">Expired</div>
            </div>
        </div>

        <!-- Action Buttons -->
        <a href="?token=<?=urlencode(CACHE_TOKEN)?>&action=clear" class="btn-clear"
           onclick="return confirm('Clear all cache? Page will reload fresh data from WHMCS on next visit.')">
            🗑️ Clear All Cache
        </a>
        <a href="?token=<?=urlencode(CACHE_TOKEN)?>&action=view" class="btn-view">
            🔄 Refresh View
        </a>
        <?php endif; ?>
    </div>

    <?php if ($allowed): ?>

    <!-- Clear Results -->
    <?php if ($action === 'clear' && !empty($result)): ?>
    <div class="card">
        <h2 style="font-size:17px;color:#213e6e;margin-bottom:16px;">✅ Cache Cleared</h2>
        <?php foreach ($result as $r): ?>
        <div class="result-row <?=$r['count']>0?'ok':'empty'?>">
            <span><?=$r['label']?> <small style="opacity:.6;">(<?=$r['pattern']?>)</small></span>
            <span class="result-count"><?=$r['count']?> deleted</span>
        </div>
        <?php endforeach; ?>
        <p style="margin-top:14px;font-size:13px;color:#888;">✅ Next page visit will fetch fresh data from WHMCS automatically.</p>
    </div>
    <?php endif; ?>

    <!-- Cache File List -->
    <div class="card">
        <h2 style="font-size:17px;color:#213e6e;margin-bottom:16px;">📁 Current Cache Files (<?=count($cache_files)?>)</h2>
        <?php if (empty($cache_files)): ?>
        <p style="color:#aaa;font-size:14px;">No cache files found. They will be created on the next page visit.</p>
        <?php else: ?>
        <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>File</th>
                    <th>Age</th>
                    <th>Size</th>
                    <th>Preview</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cache_files as $cf): ?>
            <tr>
                <td><code style="font-size:12px;"><?=$cf['file']?></code></td>
                <td>
                    <?php if ($cf['age_min'] < 30): ?>
                    <span class="tag-fresh">⚡ <?=$cf['age_min']?>m ago</span>
                    <?php else: ?>
                    <span class="tag-age">🕐 <?=$cf['age_min']?>m ago</span>
                    <?php endif; ?>
                </td>
                <td><?=$cf['size']?> B</td>
                <td><div class="preview-text"><?=$cf['preview']?></div></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>

    <!-- Quick Links -->
    <div class="card">
        <h2 style="font-size:17px;color:#213e6e;margin-bottom:16px;">🔗 Quick Cache-Clear Links</h2>
        <p style="font-size:13px;color:#888;margin-bottom:14px;">Bookmark these links to clear cache anytime:</p>
        <?php
        $links = [
            ['Clear All Cache',           '?token='.urlencode(CACHE_TOKEN).'&action=clear',  '#dc3545'],
            ['View Cache Status',         '?token='.urlencode(CACHE_TOKEN).'&action=view',   '#01adef'],
        ];
        foreach ($links as $l): ?>
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;flex-wrap:wrap;">
            <span style="min-width:180px;font-size:13px;font-weight:600;color:#213e6e;"><?=$l[0]?></span>
            <code style="background:#f0f4ff;padding:6px 12px;border-radius:8px;font-size:12px;flex:1;word-break:break-all;">
                https://resellnom.com/clear-cache.php<?=$l[1]?>
            </code>
        </div>
        <?php endforeach; ?>
        <div style="margin-top:16px;padding:14px;background:#fff8e1;border-radius:10px;font-size:13px;color:#856404;">
            ⚠️ <strong>Security tip:</strong> Change <code>CACHE_TOKEN</code> in the file to a unique secret string before uploading to your server.
        </div>
    </div>

    <?php endif; ?>

</div>
</body>
</html>
