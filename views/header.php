<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="descriptison">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="/public/assets/img/favicon.png" rel="icon">
  <link href="/public/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="/public/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/public/assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="/public/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="/public/assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="/public/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="/public/assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="/public/assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="/public/assets/css/style.css" rel="stylesheet">
</head>
<body>
<header id="header" class="fixed-top d-flex align-items-center" style="background: rgba(2, 5, 161, 0.91)">
	<div class="container d-flex align-items-center">

		<div class="logo mr-auto">
			<h1 class="text-light"><a href="/<?= LANG;?>"><span>Projet annuel</span></a></h1>
			<!-- Uncomment below if you prefer to use an image logo -->
			<!-- <a href="index.html"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
		</div>
	  <nav class="nav-menu d-none d-lg-block">
		<ul>
			<li class="active"><a href="/<?= LANG;?>"><?= translate("Accueil");?></a></li>
			<li><a href="/<?= LANG;?>/entrepot/localisation"><?= translate("Localisation");?></a></li>
			<li class="drop-down"><a href="#"><?= translate("Langage");?></a>
				<ul>
					<?php 
					foreach (POSSIBLE_LANGUAGES as $key => $value) {
						if ($value != LANG) {
							$actualUrl = $_SERVER['REQUEST_URI'];
							$actualUrl = explode('/',$actualUrl);
							if (in_array($actualUrl[1], POSSIBLE_LANGUAGES)) {
								$actualUrl[1] = $value;
								unset($actualUrl[0]);
							}else{
								$actualUrl[0] = $value;
							}
							?>
							<li><a href="/<?= implode('/',$actualUrl);?>"><?= strtoupper($value);?></a></li>
							<?php
						}
					}
					?>
				</ul>
			</li>
		</ul>
	  </nav>
	</div>
</header>