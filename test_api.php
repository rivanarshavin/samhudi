<?php
$ctx = stream_context_create(['http' => ['timeout' => 2]]);
$res = file_get_contents('http://localhost/samhudi/samhudi/familytree/get_family_tree', false, $ctx);
if ($res === false) {
    echo "FAILED: Timeout or error\n";
} else {
    echo "SUCCESS: " . strlen($res) . " bytes\n";
}
