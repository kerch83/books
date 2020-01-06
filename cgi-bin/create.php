#!"C:/Program Files/Ampps/php-7.3/php-cgi.exe" -q 
<?php 
    include "db.php";
    $sql = file_get_contents('create.sql');
    if (! $res = $db->multi_query($sql)){
        echo "Не удалось выполнить запрос: (" . $db->errno . ") " . $db->error;
        exit(0);
    }else{
        while ($db->next_result()) {;}
    };
    $db->autocommit(FALSE);
    $query = "insert into books (title, date, description, image) values (?,NOW(),?,?)";
    $book = $db->prepare($query);
    if (!$book){
        echo "Не удалось выполнить запрос: (" . $db->errno . ") " . $db->error;
        exit(0);
    };
    $book->bind_param('sss', $title, $description, $image);
    $author = $db->prepare("insert into b2a (bid, aid) values (?,?)");
    $author->bind_param('ii', $bid, $aid);
    $genre = $db->prepare("insert into b2g (bid, gid) values (?,?)");
    $genre->bind_param('ii', $bid, $gid);
    for ($i=0; $i<1000; $i++){
        $title = substr(md5(rand()), 0, rand(1,20));
        $description = substr(md5(rand()), 0, rand(1,100));
        $image = substr(md5(rand()), 0, rand(1,10)) . ".jpg";
        $book->execute();
        $bid = $db->insert_id;
        for ($ii=0; $ii<rand(1,6); $ii++){
            $aid = rand(1,9);
            $author->execute();
            $gid = rand(1,9);
            $genre->execute();
        }
    };
    $db->commit();
    header("Location: /");
//    echo 'ok';
?>
