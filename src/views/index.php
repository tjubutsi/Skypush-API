<div class="images">
	<?php
		if (isset($_SESSION["userId"])) {
			$uploads = getUploads();
			foreach ($uploads as $upload) {
	?>
		<div class="imageThumbnail" style="background-image: url('/i/t/<?php echo $upload->file; ?>.png');">
			<input type="text" value="<?php echo (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]i/" . $upload->file; ?>">
			<input type="text" value="<?php echo $upload->file; ?>">
			<a class="imageLink" href="/i/<?php echo $upload->file; ?>"></a>
			<div class="imageCopyURL">Copy</div>
			<div class="imageDelete">Delete</div>
		</div>
	<?php 
		}}
	?>
</div>
<script src="js/index.js"></script>
