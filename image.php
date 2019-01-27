<?php

if ($_REQUEST['type'] == "newitem") {
	header('Content-type: image/png');
	readfile('images/product/new.png');
	exit;
} elseif ($_REQUEST['type'] == "hotitem") {
	header('Content-type: image/png');
	readfile('images/product/hot.png');
	exit;
} elseif ($_REQUEST['type'] == "soldout") {
	header('Content-type: image/png');
	readfile('images/product/soldout.png');
	exit;
}
if ($_REQUEST['item'] != "") {
	header('Content-type: image/gif');
	if (file_exists('images/items/'.$_REQUEST['item'].'.gif')) {
		readfile('images/items/'.$_REQUEST['item'].'.gif');
	} else {
		readfile('images/items/no_image.gif');
	}
	exit;
}
if ($_REQUEST['monster'] != "") {
	header('Content-type: image/jpeg');
	if (file_exists('images/monsters/'.$_REQUEST['monster'].'.jpg')) {
		readfile('images/monsters/'.$_REQUEST['monster'].'.jpg');
	} else {
		readfile('images/monsters/no_image.gif');
	}
	exit;
}

?>