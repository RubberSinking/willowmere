<!DOCTYPE html>
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
  header nav { margin-top: 12px; display: flex; justify-content: center; gap: 20px; }
  header nav a { color: #c8b89a; font-size: 0.85rem; text-decoration: none; }
  header nav a:hover { text-decoration: underline; }
  .main { max-width: 860px; margin: 0 auto; padding: 32px 16px 64px; }
  h2 { font-size: 1.15rem; font-weight: normal; color: #3a2e24; border-bottom: 1px solid #d8c8a8; padding-bottom: 8px; margin: 36px 0 16px; }
  h2:first-child { margin-top: 0; }
  .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 24px 28px; margin-bottom: 24px; }
  .card p { font-size: 0.92rem; line-height: 1.7; color: #5a4a35; }
  .card p + p { margin-top: 10px; }
  pre { background: #1e1e2e; color: #cdd6f4; font-family: 'Courier New', monospace; font-size: 0.8rem; line-height: 1.6; padding: 20px 24px; border-radius: 8px; overflow-x: auto; white-space: pre-wrap; word-break: break-word; }
  .meta-label { font-size: 0.72rem; color: #a08060; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 6px; }
  .page-card { background: #faf6f0; border-radius: 8px; padding: 14px 16px; margin-bottom: 16px; }
  .page-card .title { font-size: 1rem; color: #3a2e24; margin-bottom: 4px; }
  .page-card .caption { font-size: 0.86rem; line-height: 1.6; color: #5a4a35; font-style: italic; }
</style>
</head>
<body>
<header>
  <h1>The Prompt</h1>
  <p>Behind the scenes of Chloe in Willowmere</p>
  <nav>
    <a href="index.php">← The Comic</a>
    <a href="the-cast.html">The Cast ✦</a>
  </nav>
</header>
<div class="main">

  <h2>How It Works</h2>
  <div class="card">
    <p>Every day at 9pm Pacific, an AI — Chloe — wakes up in an isolated session with no memory of the previous day. She reads a 100-page story plan, checks a state file to find out which page comes next, then writes a detailed multi-panel comic strip prompt and sends it to Google's Gemini image model (gemini-3.1-flash-image-preview, codenamed "Nano Banana 2").</p>
    <p>The generated page is saved to this site, added to the gallery, and sent to Jon via Telegram. Then the session ends. Tomorrow she'll do it again, one page further into the story. The comic runs automatically until page 100.</p>
    <p>Each page prompt includes reference images for every character who appears — avatar portraits used to anchor visual consistency across the 100-page run. The state file tracks which characters have been introduced, their outfit descriptions, established locations, and running gags.</p>
  </div>

  <h2>Sample Prompt — Page 5</h2>
  <div class="page-card">
    <div class="meta-label">Page 5 · Arc 1: The Arrival</div>
    <div class="title">"Good"</div>
    <div class="caption">She found the community garden on her first walk — wooden gate, hand-lettered sign, rows of seedlings in the soft morning light. An elderly man in a navy cardigan was kneeling by the beds, working with quiet concentration. He didn't look up. "Are you lost?" he asked. She thought about it. "I don't think so," she said. He nodded once, still not looking up. "Good."</div>
  </div>
  <pre>Generate the next page of the "Chloe in Willowmere" daily comic strip and publish to web-lab.

Current state:
  current_page: 5
  arc_name: Arc 1: The Arrival
  story_notes: Chloe meets Marco at his bakery and is given the corner table.

Page 5 plan: Chloe's first walk through the neighborhood. She discovers the community garden. Mr. Chen is there, kneeling by a row of seedlings. He doesn't look up. "Are you lost?" "I don't think so." "Good."

Build a detailed 3-4 panel horizontal comic strip prompt. Each panel description includes:
  - Exact visual scene (setting, character positions, expressions, body language)
  - Full verbatim dialogue written into speech bubbles
  - Caption box text if applicable

Image generation:
  - Model: gemini-3.1-flash-image-preview (Nano Banana 2)
  - Characters in this page: Chloe, Mr. Chen
  - Reference images to include:
      reference-page.jpg  (style reference — always include)
      avatar.jpg          (Chloe — always include)
      mrchen-avatar.jpg   (Mr. Chen — appears in this page)
  - Do NOT specify outfits for Chloe — her appearance comes from avatar.jpg
  - Mr. Chen: 70s, retired principal, gray hair, wire-rimmed glasses, navy cardigan and khakis

Prompt style example:
  Panel 1: Wide shot of the Willowmere Community Garden in soft morning light. Wooden gate, hand-lettered sign, rows of seedlings. Chloe (young woman with blonde bob, small blue notebook tucked under one arm) pushes the gate open slowly, looking around with quiet wonder.

  Panel 2: Mr. Chen (elderly man in navy cardigan, wire-rimmed glasses) kneels at a garden bed examining seedlings, back to Chloe. He doesn't look up. Speech bubble from Mr. Chen: "Are you lost?"

  Panel 3: Close on Chloe's face — thoughtful, genuinely considering the question. Speech bubble: "I don't think so."

  Panel 4: Mr. Chen still not looking up, continues working. A single word speech bubble: "Good." Chloe stands in the background, watching him. Caption box at bottom: "She stood there a little longer than necessary."

End prompt with: "Match the illustration style of the reference images exactly."

After generating: save page, create JSON metadata, update state.json, commit and push, send to Telegram with a brief teaser.</pre>

</div>
</body>
</html>
