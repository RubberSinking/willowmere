<?php
$pages_dir = __DIR__ . '/pages/';
$pages = [];
foreach (glob($pages_dir . '*.json') as $f) {
    $data = json_decode(file_get_contents($f), true);
    if ($data) $pages[] = $data;
}
usort($pages, fn($a, $b) => $a['page'] - $b['page']);
$latest = !empty($pages) ? end($pages) : null;

$selected = null;
if (isset($_GET['page'])) {
    $num = (int)$_GET['page'];
    foreach ($pages as $p) {
        if ($p['page'] === $num) { $selected = $p; break; }
    }
}
if (!$selected && $latest) $selected = $latest;
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chloe in Willowmere</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Georgia', serif; background: #f5f0e8; color: #2c2419; min-height: 100vh; }
  header { background: #3a2e24; color: #f5f0e8; padding: 20px 24px; text-align: center; }
  header h1 { font-size: 1.7rem; font-weight: normal; letter-spacing: 0.03em; }
  header p { font-size: 0.85rem; color: #c8b89a; font-style: italic; margin-top: 4px; }
  .main { max-width: 860px; margin: 0 auto; padding: 32px 16px 64px; }
  .comic-page { background: #fff; border-radius: 10px; box-shadow: 0 2px 16px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 28px; }
  .comic-page img { width: 100%; display: block; cursor: zoom-in; }
  .lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.92); z-index: 1000; align-items: center; justify-content: center; }
  .lightbox.open { display: flex; }
  .lightbox img { max-width: 96vw; max-height: 96vh; object-fit: contain; border-radius: 4px; }
  .lightbox-close { position: fixed; top: 16px; right: 20px; background: none; border: none; color: #fff; font-size: 2.2rem; cursor: pointer; line-height: 1; opacity: 0.8; }
  .lightbox-close:hover { opacity: 1; }
  .lightbox-nav { position: fixed; top: 50%; transform: translateY(-50%); background: none; border: none; color: #fff; font-size: 3rem; cursor: pointer; opacity: 0.6; padding: 16px; line-height: 1; }
  .lightbox-nav:hover { opacity: 1; }
  .lightbox-prev { left: 8px; }
  .lightbox-next { right: 8px; }
  .lightbox-nav:disabled { opacity: 0.15; cursor: default; }
  .page-meta { padding: 16px 20px 20px; }
  .page-num { font-size: 0.75rem; color: #a08060; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 6px; }
  .page-title { font-size: 1.1rem; color: #3a2e24; margin-bottom: 8px; }
  .page-caption { font-size: 0.9rem; line-height: 1.6; color: #5a4a35; font-style: italic; }
  .nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
  .nav a { font-size: 0.9rem; color: #7a5c3a; text-decoration: none; padding: 8px 16px; border: 1px solid #c8a878; border-radius: 6px; }
  .nav a:hover { background: #ede5d8; }
  .nav .page-indicator { font-size: 0.85rem; color: #a08060; }
  .arc-label { font-size: 0.75rem; background: #ede5d8; color: #7a5c3a; display: inline-block; padding: 3px 10px; border-radius: 12px; margin-bottom: 12px; }
  .archive { margin-top: 40px; }
  .archive { margin-top: 8px; font-size: 0.9rem; }
  .archive a { color: #a07840; text-decoration: none; }
  .archive a:hover { color: #3a2e24; }
  .empty { text-align: center; color: #a08060; font-style: italic; padding: 60px 20px; }
</style>
</head>
<body>
<header>
  <h1>Chloe in Willowmere</h1>
  <p>A daily comic strip &nbsp;·&nbsp; <a href="the-cast.html" style="color:#c8b89a;text-decoration:none;font-style:normal;">The Cast ✦</a> &nbsp;·&nbsp; <a href="the-prompt.php" style="color:#c8b89a;text-decoration:none;font-style:normal;">The Prompt ✦</a></p>
</header>
<div class="main">
<?php if (!$selected): ?>
  <p class="empty">The first page is coming soon.</p>
<?php else: ?>

<?php
  $cur  = $selected['page'];
  $tot  = count($pages);
  $prev_num = $cur > 1 ? $cur - 1 : null;
  $next_num = $cur < $tot ? $cur + 1 : null;
  $first_num = $pages[0]['page'];
  $last_num  = end($pages)['page'];
  $pnum = str_pad($cur, 3, '0', STR_PAD_LEFT);
  $pext = file_exists(__DIR__.'/pages/page-'.$pnum.'.png') ? 'png' : 'jpg';
  $arc   = htmlspecialchars($selected['arc_name']);
  $title_h = htmlspecialchars($selected['title']);
  $caption_h = htmlspecialchars($selected['caption'] ?? '');
?>
  <!-- Navigation -->
  <div class="nav">
    <?= $prev_num ? '<a href="?page='.$prev_num.'">&#8592; Page '.$prev_num.'</a>' : '<span></span>' ?>
    <span class="page-indicator">Page <?= $cur ?> of <?= $tot ?></span>
    <?= $next_num ? '<a href="?page='.$next_num.'">Page '.$next_num.' &#8594;</a>' : '<span></span>' ?>
  </div>

  <!-- Current Page -->
  <div class="comic-page">
    <img src="pages/page-<?= $pnum ?>.<?= $pext ?>" alt="Page <?= $cur ?>">
    <div class="page-meta">
      <div class="arc-label"><?= $arc ?></div>
      <div class="page-num">Page <?= $cur ?></div>
      <div class="page-title"><?= $title_h ?></div>
      <?= $caption_h ? '<div class="page-caption">'.$caption_h.'</div>' : '' ?>
    </div>
  </div>

  <!-- First / Last Page links -->
  <?php if ($tot > 1): ?>
  <div class="archive">
    <a href="?page=<?= $first_num ?>">&#8592; First Page</a>
    &nbsp;&nbsp;
    <a href="?page=<?= $last_num ?>">Last Page &#8594;</a>
  </div>
  <?php endif; ?>

<?php endif; ?>
</div>
<div class="lightbox" id="lb"><button class="lightbox-close" onclick="closeLb()">&times;</button><button class="lightbox-nav lightbox-prev" id="lb-prev" onclick="lbNav(-1)">&#8249;</button><img id="lb-img" src="" alt=""><button class="lightbox-nav lightbox-next" id="lb-next" onclick="lbNav(1)">&#8250;</button></div>
<script>
var lb=document.getElementById("lb"),lbImg=document.getElementById("lb-img");
var lbPages=[], lbIdx=0;
// Build ordered page list from thumbnails
document.querySelectorAll(".thumb a").forEach(function(a){
  var img=a.querySelector("img"); if(img) lbPages.unshift({src:img.src,href:a.href});
});
// Also add current page
var mainImg=document.querySelector(".comic-page img");
if(mainImg){ var cur={src:mainImg.src,href:window.location.href}; lbIdx=lbPages.findIndex(function(p){return p.src===mainImg.src;}); if(lbIdx<0){lbPages.push(cur);lbIdx=lbPages.length-1;} }
function lbOpen(idx){ lbIdx=idx; lbImg.src=lbPages[idx].src; lb.classList.add("open"); lbUpdateNav(); }
function lbUpdateNav(){ document.getElementById("lb-prev").disabled=lbIdx<=0; document.getElementById("lb-next").disabled=lbIdx>=lbPages.length-1; }
function lbNav(dir){ var n=lbIdx+dir; if(n>=0&&n<lbPages.length){lbIdx=n;lbImg.src=lbPages[n].src;lbUpdateNav();} }
if(mainImg) mainImg.addEventListener("click",function(){lbOpen(lbIdx);});
function closeLb(){lb.classList.remove("open");lbImg.src="";}
lb.addEventListener("click",function(e){if(e.target===lb)closeLb();});
document.addEventListener("keydown",function(e){if(e.key==="Escape")closeLb();else if(e.key==="ArrowLeft")lbNav(-1);else if(e.key==="ArrowRight")lbNav(1);});
var lbTouchX=null;
lb.addEventListener("touchstart",function(e){lbTouchX=e.touches[0].clientX;},{passive:true});
lb.addEventListener("touchend",function(e){if(lbTouchX===null)return;var dx=e.changedTouches[0].clientX-lbTouchX;if(Math.abs(dx)>50){lbNav(dx<0?1:-1);}lbTouchX=null;},{passive:true});
</script>
</body>
</html>
