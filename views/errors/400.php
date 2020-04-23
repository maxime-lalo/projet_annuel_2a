<title>Erreur 400</title>
<div>
	<p>Le language demandé n'existe pas</p>
	<?php 
	foreach (POSSIBLE_LANGUAGES as $key => $value) {
		$explodedUrl = explode("/", $url);
		$explodedUrl[0] = $value;
		?>
		<a href="/<?= implode('/',$explodedUrl);?>"><?= $value;?></a>
		<?php
	}
	?>
</div>