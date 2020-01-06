#!"C:/Program Files/Ampps/php-7.3/php-cgi.exe" -q 
<?php 
    include "db.php";
    $filename = $_FILES['file']['name'];
    move_uploaded_file($_FILES['file']['tmp_name'], "../image/".$filename);

    $title = $db->real_escape_string($_POST['title']);
    $date = $db->real_escape_string($_POST['date']);
    if (!$date) { $date = date('Y-m-d');}
    $description = $db->real_escape_string($_POST['description']);
    $sql = "
    INSERT INTO `books` (`title`, `date`, `description`, `image`) 
    VALUES ('$title', '$date', '$description', '$filename')
    ";
    if (!$db->query($sql)){
        echo "Не удалось выполнить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
        exit(0);
    };
    $bid = $db->insert_id;
//    echo $_POST['author'];
    $count = 0;
    if (!empty($_POST['author'])) {
        foreach ($_POST['author'] as $item) {
            $count++;
            if ($count > 5) { break; };
            $item = $db->real_escape_string($item);
            $sql = "
            insert into `b2a` (`bid`, `aid`) values ('$bid', '$item')
            ";
            $db->query($sql);
        }}
    $count = 0;
    if (!empty($_POST['genre'])) {
        foreach ($_POST['genre'] as $item) {
            $count++;
            if ($count > 5) { break; };
            $item = $db->real_escape_string($item);
            $sql = "
            insert into `b2g` (`bid`, `gid`) values ('$bid', '$item')
            ";
            $db->query($sql);
        }}
    
    echo "add post: id($bid) ".$_POST['author'];
?>