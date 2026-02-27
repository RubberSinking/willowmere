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
  .comic-page img { width: 100%; display: block; }
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
  .archive h2 { font-size: 1rem; color: #7a5c3a; margin-bottom: 16px; font-weight: normal; letter-spacing: 0.05em; text-transform: uppercase; }
  .thumb-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 10px; }
  .thumb { text-align: center; cursor: pointer; }
  .thumb img { width: 100%; border-radius: 6px; aspect-ratio: 3/2; object-fit: cover; border: 2px solid transparent; }
  .thumb img:hover, .thumb img.active { border-color: #a07840; }
  .thumb span { font-size: 0.7rem; color: #a08060; display: block; margin-top: 3px; }
  .empty { text-align: center; color: #a08060; font-style: italic; padding: 60px 20px; }
</style>
</head>
<body>
<header>
  <h1>Chloe in Willowmere</h1>
  <p>A daily comic strip</p>
</header>
<div class="main">
<?php if (!$selected): ?>
  <p class="empty">The first page is coming soon.</p>
<?php else: ?>

  <!-- Navigation -->
  <div class="nav">
    <?php $prev = null; $next = null;
    foreach ($pages as $p) {
      if ($p['page'] === $selected['page'] - 1) $prev = $p;
      if ($p['page'] === $selected['page'] + 1) $next = $p;
    }?>
    <?php if ($prev): ?><a href="?page=<?= $prev['page'] ?>">&#8592; Page <?= $prev['page'] ?></a><?php else: ?><span></span><?php endif; ?>
    <span class="page-indicator">Page <?= $selected['page'] ?> of <?= count($pages) ?></span>
    <?php if ($next): ?><a href="?page=<?= $next['page'] ?>">Page <?= $next['page'] ?> &#8594;</a><?php else: ?><span></span><?php endif; ?>
  </div>

  <!-- Current Page -->
  <div class="comic-page">
    <img src="pages/page-<?= str_pad($selected['page'], 3, '0', STR_PAD_LEFT) ?>.jpg" alt="Page <?= $selected['page'] ?>">
    <div class="page-meta">
      <div class="arc-label"><?= htmlspecialchars($selected['arc_name']) ?></div>
      <div class="page-num">Page <?= $selected['page'] ?></div>
      <div class="page-title"><?= htmlspecialchars($selected['title']) ?></div>
      <?php if (!empty($selected['caption'])): ?>
      <div class="page-caption"><?= htmlspecialchars($selected['caption']) ?></div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Archive Thumbnails -->
  <?php if (count($pages) > 1): ?>
  <div class="archive">
    <h2>All Pages</h2>
    <div class="thumb-grid">
      <?php foreach (array_reverse($pages) as $p): ?>
      <div class="thumb">
        <a href="?page=<?= $p['page'] ?>">
          <img src="pages/page-<?= str_pad($p['page'], 3, '0', STR_PAD_LEFT) ?>.jpg"
               alt="Page <?= $p['page'] ?>"
               <?= $p['page'] === $selected['page'] ? 'class="active"' : '' ?>>
        </a>
        <span>p.<?= $p['page'] ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

<?php endif; ?>
</div>
</body>
</html>
