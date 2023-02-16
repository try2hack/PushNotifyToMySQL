<?php

declare(strict_types=1);

$link = mysqli_connect("localhost", "testbug", "1234", "notifty");

if (!$link) {
    die("-1" . mysqli_connect_error());
}

$name = htmlspecialchars($_POST['name']);
$pkg = htmlspecialchars($_POST['pkg']);
$title = htmlspecialchars($_POST['title']);
$text = htmlspecialchars($_POST['text']);
$secrex = htmlspecialchars($_POST['secrex']);

if ($secrex !== "#dekthaihedumb") {
    die("0 Invalid secret key.");
}

$stmt = mysqli_prepare($link, "INSERT INTO testnotify (name,pkg,title,text) VALUES (?,?,?,?)");
mysqli_stmt_bind_param($stmt, "sssss", $name, $pkg, $title, $text);

if (mysqli_stmt_execute($stmt)) {
    echo "1";
} else {
    echo "0" . mysqli_error($link);
}

mysqli_close($link);
