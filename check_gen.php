<?php
$db = new PDO('mysql:host=localhost;dbname=samhudi', 'root', '');
$stmt = $db->query("DESCRIBE family_members");
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
$has_gen = false;
foreach ($cols as $c) {
    if ($c['Field'] === 'generasi') { $has_gen = true; break; }
}
if (!$has_gen) {
    $db->exec("ALTER TABLE family_members ADD COLUMN generasi INT NULL DEFAULT NULL AFTER gender");
    echo "Column added\n";
} else {
    echo "Column exists\n";
}
