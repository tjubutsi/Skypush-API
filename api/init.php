<?php
	$data = new stdClass();
	$data->message = bin2hex(random_bytes(16));
	echo json_encode($data);