<?php
$db = new PDO('mysql:host=localhost;dbname=samhudi', 'root', '');
$stmt = $db->query("DESCRIBE family_members");
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($cols as $c) {
    echo $c['Field'] . "\n";
}
