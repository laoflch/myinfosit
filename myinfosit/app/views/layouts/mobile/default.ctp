<?php
header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
header('Content-Type: text/html; charset=utf-8');
//header("X-JSON: ".$content_for_layout);

echo $content_for_layout;
?>