#!"C:/Program Files/Ampps/php-7.3/php-cgi.exe" -q 
<?php 
    include "db.php";
    if ($result = $db->query("SELECT * FROM authors")) {
        $json['authors'] = $result->fetch_all(MYSQLI_ASSOC);
    };
    if ($result = $db->query("SELECT * FROM genres")) {
        $json['genres'] = $result->fetch_all(MYSQLI_ASSOC);
    };
    echo json_encode($json);
?>