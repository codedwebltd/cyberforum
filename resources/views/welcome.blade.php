<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Backblaze Upload Previews</title>
  <style>
    :root{
      --card-bg:#fff;
      --card-border:#e6e6e6;
      --muted:#666;
      --accent:#0b74de;
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    }
    body{background:#f6f8fa; padding:24px; color:#1b1b1b;}
    .grid{display:grid; gap:18px; grid-template-columns: repeat(auto-fit,minmax(320px,1fr));}
    .card{background:var(--card-bg); border:1px solid var(--card-border); border-radius:10px; padding:14px; box-shadow:0 2px 6px rgba(20,20,20,0.03);}
    h1{font-size:20px; margin:0 0 10px;}
    .meta{font-size:13px; color:var(--muted); margin:8px 0;}
    .actions{margin-top:10px; display:flex; gap:8px; flex-wrap:wrap;}
    .btn{display:inline-block; padding:8px 12px; border-radius:8px; text-decoration:none; font-weight:600; font-size:14px;}
    .btn-primary{background:var(--accent); color:white;}
    .btn-ghost{background:transparent; color:var(--accent); border:1px solid var(--card-border);}
    iframe, .img-preview{width:100%; border-radius:8px; border:1px solid #ddd;}
    .img-preview{display:block; max-height:360px; object-fit:contain; cursor:pointer;}
    /* modal for full-size image */
    .modal{position:fixed; inset:0; display:none; align-items:center; justify-content:center; background:rgba(0,0,0,0.6); z-index:1000;}
    .modal.open{display:flex;}
    .modal img{max-width:94vw; max-height:94vh; border-radius:6px;}
    .small{font-size:13px; color:var(--muted);}
  </style>
</head>
<body>
  <h1>Uploaded Files — Preview & Download</h1>
  <div class="grid">
    <!-- PDF Card -->
    <section class="card" aria-labelledby="pdf-title">
      <h2 id="pdf-title" style="font-size:16px; margin:0 0 8px;">CHIBUIKE CHRISTOPHER PORTFOLIO.pdf</h2>
      <div class="meta">
        Path: <span class="small">social/test/999/2025-09-14_22-07-55_6nHZ3lCr.pdf</span><br/>
        Size: <span class="small">31.40 KB</span> &nbsp; • &nbsp; Type: <span class="small">application/pdf</span>
      </div>

      <!-- Embedded PDF. If the embed is blocked by the server or browser, the fallback link is provided -->
      <div style="margin-top:10px;">
        <iframe
  src="https://docs.google.com/viewer?url=https://f005.backblazeb2.com/file/westernkits/social/test/999/2025-09-14_22-07-55_6nHZ3lCr.pdf&embedded=true"
  width="100%"
  height="480"
  style="border:1px solid #ddd; border-radius:8px;"
  allowfullscreen>
</iframe>

      </div>

      <div class="actions">
        <a class="btn btn-primary" href="https://f005.backblazeb2.com/file/westernkits/social/test/999/2025-09-14_22-07-55_6nHZ3lCr.pdf" target="_blank" rel="noopener noreferrer">Open PDF in new tab</a>
        <a class="btn btn-ghost" href="https://f005.backblazeb2.com/file/westernkits/social/test/999/2025-09-14_22-07-55_6nHZ3lCr.pdf" download="CHIBUIKE_CHRISTOPHER_PORTFOLIO.pdf">Download PDF</a>
      </div>
      <p class="small" style="margin-top:10px;">Original Name: CHIBUIKE CHRISTOPHER PORTFOLIO.pdf</p>
    </section>

    <!-- Image Card -->
    <section class="card" aria-labelledby="img-title">
      <h2 id="img-title" style="font-size:16px; margin:0 0 8px;">1754813299_ads.jpg</h2>
      <div class="meta">
        Path: <span class="small">social/test/999/2025-09-14_22-10-47_3w2dMZle.jpg</span><br/>
        Size: <span class="small">92.17 KB</span> &nbsp; • &nbsp; Type: <span class="small">image/jpeg</span>
      </div>

      <!-- Image preview (click to open full size modal) -->
      <img
        src="https://f005.backblazeb2.com/file/westernkits/social/test/999/2025-09-14_22-10-47_3w2dMZle.jpg"
        alt="1754813299_ads.jpg preview"
        class="img-preview"
        id="imgPreview"
        loading="lazy"
      />

      <div class="actions">
        <a class="btn btn-primary" href="https://f005.backblazeb2.com/file/westernkits/social/test/999/2025-09-14_22-10-47_3w2dMZle.jpg" target="_blank" rel="noopener noreferrer">Open full image</a>
        <a class="btn btn-ghost" href="https://f005.backblazeb2.com/file/westernkits/social/test/999/2025-09-14_22-10-47_3w2dMZle.jpg" download="1754813299_ads.jpg">Download image</a>
      </div>
      <p class="small" style="margin-top:10px;">Original Name: 1754813299_ads.jpg</p>
    </section>
  </div>

  <!-- Simple image modal -->
  <div id="modal" class="modal" role="dialog" aria-modal="true" aria-label="Full-size image view">
    <img id="modalImg" src="" alt="Full size" />
  </div>

  <script>
    // Image modal behavior
    (function(){
      const preview = document.getElementById('imgPreview');
      const modal = document.getElementById('modal');
      const modalImg = document.getElementById('modalImg');

      if(preview){
        preview.addEventListener('click', function(){
          modalImg.src = preview.src;
          modal.classList.add('open');
        });
      }

      // close modal on click outside image or on ESC
      modal.addEventListener('click', function(e){
        if(e.target === modal || e.target === modalImg) {
          modal.classList.remove('open');
        }
      });

      document.addEventListener('keydown', function(e){
        if(e.key === 'Escape') modal.classList.remove('open');
      });
    })();
  </script>
</body>
</html>
