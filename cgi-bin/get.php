#!"C:/Program Files/Ampps/php-7.3/php-cgi.exe" -q 
<?php 
    include "db.php";
    $sql = "
    SELECT books.id, books.title, books.date,
    (select GROUP_CONCAT(authors.author) as author from b2a,authors where b2a.bid = books.id and b2a.aid = authors.id )
      as author,
    (select GROUP_CONCAT(genres.genre) as genre from b2g,genres where b2g.bid = books.id and b2g.gid = genres.id )
      as genre,
    books.description, books.image 
    FROM books     
    ";
    $where = " where 1=1";
    $author = $_GET["author"];
    if ($author){
        $a = $db->real_escape_string($author);
        $sql .= " left join b2a on books.id = b2a.bid";
        $where .= " and b2a.aid = $a";
    };

    $genre = $_GET["genre"];
    if ($genre){
        $g = $db->real_escape_string($genre);
        $sql .= " left join b2g on books.id = b2g.bid";
        $where .= " and b2g.gid = $g";
    };

    $query = $_GET["query"];
    if ($query){
        $q = $db->real_escape_string($query);
//        $where .= " and MATCH (title,description) AGAINST ('$q'  IN BOOLEAN MODE)";
        $where .= " and (title LIKE '%$q%' OR description LIKE '%$q%')";
    };


    if ($result = $db->query($sql.$where." limit 20")) {
        $json = $result->fetch_all(MYSQLI_ASSOC);
    }else{
        echo $sql.$where;
        echo "Не удалось выполнить запрос: (" . $db->errno . ") " . $db->error;
    };
    echo json_encode($json);
?>