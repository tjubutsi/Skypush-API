<?php 
	require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/helpers.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Skypush browser</title>
		<link rel="stylesheet" type="text/css" href="css/css.css">
	</head>
	<body>
		<?php
			$uploads = $db->uploads->whereList("isPrivate", 0);
			foreach (array_reverse($uploads) as $upload) {
				echo "<a href=\"/i/" . $upload->file . "\"><img src=\"/i/t/t_" . $upload->file . "\" /></a>";
			}
		?>
	</body>
</html>
