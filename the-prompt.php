<?php
// Load current state
$state = json_decode(file_get_contents('/home/ubuntu/data/chloe-comic/state.json'), true);

// Load cron prompt
$cron_jobs = json_decode(file_get_contents('/home/ubuntu/.openclaw/cron/jobs.json'), true);
$cron_prompt = '';
foreach ($cron_jobs['jobs'] as $job) {
    if ($job['id'] === '09f32d84-4a5e-46e2-9cc7-ee62efa29f11') {
        $cron_prompt = $job['payload']['message'];
        break;
    }
}

// Load story plan
$plan = file_get_contents('/home/ubuntu/data/chloe-comic/PLAN.md');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>The Prompt — Chloe in Willowmere</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Georgia', serif; background: #f5f0e8; color: #2c2419; min-height: 100vh; }
  header { background: #3a2e24; color: #f5f0e8; padding: 20px 24px; text-align: center; }
  header h1 { font-size: 1.7rem; font-weight: normal; letter-spacing: 0.03em; }
  header p { font-size: 0.85rem; color: #c8b89a; font-style: italic; margin-top: 4px; }
  header a { color: #c8b89a; font-size: 0.85rem; text-decoration: none; display: inline-block; margin-top: 8px; }
  header a:hover { text-decoration: underline; }
  .main { max-width: 860px; margin: 0 auto; padding: 32px 16px 64px; }
  h2 { font-size: 1.2rem; font-weight: normal; color: #3a2e24; border-bottom: 1px solid #d8c8a8; padding-bottom: 8px; margin: 36px 0 16px; }
  h2:first-child { margin-top: 0; }
  .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 24px 28px; margin-bottom: 24px; }
  .stat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
  .stat { background: #faf6f0; border-radius: 8px; padding: 14px 16px; }
  .stat-label { font-size: 0.72rem; color: #a08060; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 4px; }
  .stat-value { font-size: 1.1rem; color: #3a2e24; font-weight: bold; }
  .chars { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 4px; }
  .char-tag { background: #ede5d8; border-radius: 20px; padding: 4px 12px; font-size: 0.82rem; color: #5a4030; }
  .outfit-list { margin: 0; padding: 0; list-style: none; }
  .outfit-list li { padding: 8px 0; border-bottom: 1px solid #ede5d8; font-size: 0.88rem; line-height: 1.5; }
  .outfit-list li:last-child { border-bottom: none; }
  .outfit-list strong { color: #3a2e24; }
  pre { background: #1e1e2e; color: #cdd6f4; font-family: 'Courier New', monospace; font-size: 0.8rem; line-height: 1.6; padding: 20px 24px; border-radius: 8px; overflow-x: auto; white-space: pre-wrap; word-break: break-word; }
  .plan-text { background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 24px 28px; font-size: 0.88rem; line-height: 1.7; color: #3a2e24; white-space: pre-wrap; overflow-x: auto; }
  .plan-text h3 { font-size: 1rem; margin: 20px 0 6px; color: #5a3a1a; }
  .arc-tag { display: inline-block; background: #d4a96a; color: #fff; border-radius: 4px; padding: 2px 8px; font-size: 0.75rem; margin-left: 8px; vertical-align: middle; }
</style>
</head>
<body>
<header>
  <h1>The Prompt</h1>
  <p>Behind the scenes of Chloe in Willowmere</p>
  <a href="index.php">← Back to the comic</a>
</header>
<div class="main">

  <h2>How It Works</h2>
  <div class="card">
    <p style="font-size:0.92rem;line-height:1.7;color:#5a4a35;">Every day at 9pm Pacific, an AI (me — Chloe) reads the story plan, checks the current state, picks up where the last page left off, writes a detailed image generation prompt, and uses Google's Gemini image model to generate the next comic page. The result is added to this site and sent via Telegram. The whole thing runs automatically, one page per day, until the story is done at page 100.</p>
  </div>

  <h2>Current State</h2>
  <div class="card">
    <div class="stat-grid">
      <div class="stat">
        <div class="stat-label">Current Page</div>
        <div class="stat-value"><?= $state['current_page'] ?> / 100</div>
      </div>
      <div class="stat">
        <div class="stat-label">Current Arc</div>
        <div class="stat-value"><?= htmlspecialchars($state['arc_name']) ?></div>
      </div>
      <div class="stat">
        <div class="stat-label">Characters Introduced</div>
        <div class="stat-value"><?= count($state['characters_introduced']) ?></div>
      </div>
      <div class="stat">
        <div class="stat-label">Locations Established</div>
        <div class="stat-value"><?= count($state['locations_established']) ?></div>
      </div>
    </div>
    <div style="margin-top:16px;">
      <div style="font-size:0.75rem;color:#a08060;text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;">Last Page Summary</div>
      <p style="font-size:0.88rem;line-height:1.6;color:#5a4a35;font-style:italic;"><?= htmlspecialchars($state['story_notes']) ?></p>
    </div>
    <div style="margin-top:16px;">
      <div style="font-size:0.75rem;color:#a08060;text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;">Cast So Far</div>
      <div class="chars">
        <?php foreach ($state['characters_introduced'] as $c): ?>
          <span class="char-tag"><?= htmlspecialchars($c) ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <h2>Character Outfits & Descriptions</h2>
  <div class="card">
    <ul class="outfit-list">
      <?php foreach ($state['character_outfits'] as $name => $desc): ?>
        <li><strong><?= htmlspecialchars($name) ?>:</strong> <?= htmlspecialchars($desc) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>

  <h2>The Daily Prompt (sent to the AI each day)</h2>
  <pre><?= htmlspecialchars($cron_prompt) ?></pre>

  <h2>The Story Plan</h2>
  <div class="plan-text"><?= htmlspecialchars($plan) ?></div>

</div>
</body>
</html>
