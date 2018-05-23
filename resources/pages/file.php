<?php

// created a download script for the selected file
if(isset($_GET["page"])) {
    $filepath = "public/files/upload/";

    $filename = htmlspecialchars(trim($_GET["page"]));
    $filename = explode('/', $filename);
    if(basename($filename[1]) == $filename[1]) {
        $absolutePath = $filepath . $filename[1];

        $kkk = filesize($absolutePath);

        if(file_exists($absolutePath) && is_readable($absolutePath)) {

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($filename[1]).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($absolutePath));
            flush(); // Flush system output buffer
            readfile($filename[1]);
        } else {
            return false;
        }
    } else {
        return false;
    }
}