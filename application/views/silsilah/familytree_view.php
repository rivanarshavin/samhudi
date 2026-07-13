<?php
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pohon Keluarga — Preview</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Manuale:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url('assets/style/silsilah.css'); ?>">
</head>

<body>

  <div class="frame">
    <div class="title-wrap">
      <h1 class="title">Data Keluarga</h1>
      <div class="subtitle">Keluarga adalah tempat dimana h idup dimulai dan cinta tidak pernah berakhir.</div>
    </div>
    <div class="tree-scroll">
      <div id="tree" aria-live="polite"></div>
    </div>
  </div>

  <div class="info-popup" id="infoPopup" role="dialog" aria-hidden="true">
    <button class="ip-close" id="popupClose" aria-label="Tutup">&#10005;</button>
    <div class="ip-name" id="ipName"></div>
    <div class="ip-lines" id="ipLines"></div>
  </div>
  <script src="<?php echo base_url('assets/js/tree.js'); ?>" data-url="<?php echo site_url('familytree/get_family_tree'); ?>"></script>
</body>

</html>