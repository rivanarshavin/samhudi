<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/style/style.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= base_url('assets/style/forum.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= base_url('assets/style/wizard.css?v=' . time()) ?>">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Libre Baskerville" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/brittany-signature" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&family=Plus+Jakarta+Sans:wght@500;600;700&family=Montserrat:wght@400;800&family=Poppins:ital,wght@0,400;0,600;1,400;1,600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                teal: {
                  950: '#0F211F',
                  900: '#1B3835',
                  800: '#22443F',
                  700: '#2E564F',
                  600: '#3D6C63',
                },
              },
              fontFamily: {
                display: ['"Plus Jakarta Sans"', 'sans-serif'],
                body: ['Inter', 'sans-serif'],
              }
            }
          }
        }
    </script>

    <title>Keluarga Besar H.M Samhudi</title>
</head>
<body class="has-fixed-nav" data-theme="dark">
