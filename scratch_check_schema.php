<?php
$db = new PDO('mysql:host=localhost;dbname=samhudi', 'root', '');
$res = $db->query('SELECT count(*) FROM family_members WHERE father_id IS NULL AND mother_id IS NULL')->fetchColumn();
echo "Roots: $res\n";
