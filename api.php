<?php


// Takes raw data from the request
$json = file_get_contents('php://input');

// Converts it into a PHP object
$post = json_decode($json, true);

// if (isset($_POST["action"])){
//     $a = array("status"=>"200 OK", "post"=> $_POST);
//     echo json_encode($a);
//     die();
// }

function insert($dbfn, $text)
{

$dbh=  new SQLite3($dbfn);
$cstmt = $dbh->prepare("CREATE TABLE IF NOT EXISTS d1 ( key0, col, ref, tt real, json STRING);");
$result = $cstmt->execute();
$ts = microtime(true);
$stmt = $dbh->prepare('INSERT INTO "d1" VALUES("key0","col0","ref0",'.$ts.',:id);');
$stmt->bindValue(':id', $text, SQLITE3_TEXT);
$result = $stmt->execute();

return;
}




if ( isset($post["action"]) && $post["action"] == "update") {
    $email = $post["email"]? $post["email"]: "a1@a.com";
    $docname = $post["docname"]? $post["docname"]: "abc1";
    $dbfn = "kvf/".$email."/".$docname.".sqlite";
    $text = json_encode($post["data"]);
    insert($dbfn, $text);
    
   
    echo json_encode(array("dbfn"=>$dbfn, "text"=> $text));
 
}



?>