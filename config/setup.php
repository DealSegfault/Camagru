<?php
include_once('database.php');
if (isset($_SESSION['id']))
    unset($_SESSION);
try {
    $db = new PDO($DB_DSN_LIGHT, $DB_USER, $DB_PSSWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE DATABASE `".$DB_NAME."`";
    $db->exec($sql);
    echo "DB Camagru successfuly created\n";
} catch (PDOException $e) {
    echo $e->getMessage();
    exit(-1);
};
$db->exec("use Camagru");
    $db->exec("CREATE TABLE IF NOT EXISTS users (id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        ban INT(9) DEFAULT 0,
        admin INT(9) DEFAULT 0,
        user VARCHAR(255) NOT NULL,
        mail VARCHAR(255) NOT NULL,
        passwd VARCHAR(255) NOT NULL,
        active VARCHAR(255) NOT NULL)");
    echo "Table 'users' created successfully.\n";
    $db->exec("CREATE TABLE IF NOT EXISTS photos (id_p INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        id_user VARCHAR(255) NOT NULL,
        data VARCHAR(255) NOT NULL,
        like_p VARCHAR(255) NOT NULL,
        time INT(9) DEFAULT 0)");
    echo "Table 'photos' created successfully.\n";
    $db->exec("CREATE TABLE IF NOT EXISTS comment (id_com INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        comment VARCHAR(3000) NOT NULL,
        id_pic INT(9) DEFAULT 0,
        id_user INT(9) DEFAULT 0,
        time INT(9) DEFAULT 0)");
    echo "Table 'comment' created successfully.\n";
    $db->exec("CREATE TABLE IF NOT EXISTS like_p (id_p INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        id_user INT(9) DEFAULT 0)");
    echo "Table 'like_p' created successfully.\n";
?>