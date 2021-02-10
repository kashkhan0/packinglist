 <?php



function findone($dbfn, $needle)
{

$dbh=  new SQLite3($dbfn);
$stmt = $dbh->prepare('SELECT rowid, * FROM d1 where key0=:id ORDER by rowid DESC;');
 $stmt->bindValue(':id', $needle, SQLITE3_TEXT);
$result = $stmt->execute();

  while($r=$result->fetchArray())
  {
    // print_r($r);
    return $r["json"] ;
   
  }

return "null";
}






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


function initialize($dbfn)
{
$dbh=  new SQLite3($dbfn);
$cstmt = $dbh->prepare("CREATE TABLE IF NOT EXISTS d1 ( key0, col, ref, tt real, json STRING);");
$result = $cstmt->execute();
}

function findlatest($dbfn)
{

$dbh=  new SQLite3($dbfn);
$stmt = $dbh->prepare('SELECT * FROM d1 ORDER by rowid DESC;');
$result = $stmt->execute();
  while($r=$result->fetchArray())
  {
    return $r["json"] ;
  }
return "[]";
}

// http://localhost/packing/doc.php?doc=abcd

$docname = isset($_GET["doc"]) ? $_GET["doc"]: "abc";
$email = isset($_GET["email"]) ? $_GET["email"]: 'a@a.com';
$dbfn = "kvf/".$email."/".$docname.".sqlite";


//$data = array("items"=> array("num=1&area=Area&title=Title&itemlist=Gdfc%0Asgsd%0A%20ug%0AB%20ihb&ts=1612765051", "num=2&area=Area&title=Title&itemlist=Gdfc%0Asgsd%0A%20ug%0AB%20ihb&ts=1612765051") );
// $data = array();
// 
// $text = json_encode($data);
initialize($dbfn);


function parsequery($query){
    $aout = array();
    // echo $query."<br>"; 
    foreach (explode('&', $query) as $chunk) {
        $param = explode("=", $chunk);
    
        if ($param) {

            $k = urldecode($param[0]);
            $v = urldecode($param[1]);
            $aout[$k] = $v;
            if (urldecode($param[0]) == "itemlist") {
                $in = 1;
                $cc = explode("\n",urldecode($param[1]));
                $arr = array();
                foreach ( $cc as $i  ){ 
                    array_push($arr,$i);
                    $in++;
                }
                $aout["items"] = $arr;

            }
        }
    }
    return $aout;
}

$tout = '<tr><th width="100">Number'
.'</th><th width="200">Room'
.'</th><th width="200">Title'
.'</th><th width="200">Items'

.'</th><th> '
.'</th><th>'
.'</th></tr>';
$maxn = 0;
$seq = 0;

$latest = findlatest($dbfn);



    $latestjson = json_decode($latest, true);
   
    foreach($latestjson as $query){
        $js = parsequery($query);
        $seq++;
        $maxn = $maxn <  $js['num'] ?   $js['num'] : $maxn ;
        $comma_separated = implode("<br> ", $js['items']); 
        $tout.= '<tr><td width="100">'
        .($js['num'])  
        .'</td><td width="200">'.($js['area'])  
        .'</td><td width="200">'.($js['title'])  
        .'</td><td width="200">'.($comma_separated)
        // .'</td><td>'.($query)
        // .'</td><td> <input type="submit" name="submit" value="Edit"  class="btn btn-primary"/>'
        .'</td><td><a href ="edit.php?doc='.$docname.'&item='.($seq-1).'">EDIT</a>'
        .'</td><td><a href ="page.php?'.$query.'&seq='.$seq.'">PRINT</a>'
        // .'</td><td>'.($js['itemlist'])
    //    .'</td><td>'.($query)
        
        .'</td></tr>';
    }



header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

$out= '<!doctype html>
<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

<body>

<div class="container">'  
. '<div>'.$latest.'</div><h1>'.$docname.'</h1>'  
. '<table  class="tt4">'.$tout.'';

echo $out;

?>


<td width="100">
   <input  class="form-control" id="num"  name="num" value="<?php  echo ($maxn+1);  ?>">  
</td><td width="160">
    <input  class="form-control" id="area"  name="area" value="">
</td><td width="160"> 
    <input  class="form-control" id="title"  name="title" value="">
</td><td> 
   <textarea class="form-control" id="itemlist" rows="6"  name="itemlist"></textarea>
   </td><td> 
   <button onclick="process()"   class="btn btn-primary" >Create</button>
   </td><td>
   </td></tr>
   </table>

<div id="demo"></div>

</div>
</body>
<script>

var latest = <?php echo $latest; ?>;
var docname = "<?php echo $docname; ?>";
let email =  "<?php echo $email; ?>";
var e = latest;
console.log("latest");
console.log(latest);
 

function process() {
    // if (e.preventDefault) e.preventDefault();
    console.log('hohohoh');
    updatedoc();
    return false;
    let form = document.querySelector('#my-form');

// Get all field data from the form
// returns a FormData object
// let data = new FormData(form);


  console.log(data);

  return false;


  
// location.href = ff;
    /* do what you want with the form */

    // You must return false to prevent the default form behavior

    return false;
}


async function postData(url = '', data = {}) {
  // Default options are marked with *
  const response = await fetch(url, {
    method: 'POST', // *GET, POST, PUT, DELETE, etc.
    mode: 'cors', // no-cors, *cors, same-origin
    cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
    credentials: 'same-origin', // include, *same-origin, omit
    headers: {
      'Content-Type': 'application/json'
      // 'Content-Type': 'application/x-www-form-urlencoded',
    },
    redirect: 'follow', // manual, *follow, error
    referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
    body: JSON.stringify(data) // body data type must match "Content-Type" header
  });
  return response.json(); // parses JSON response into native JavaScript objects
}



function updatedoc(){
    var textarea = document.getElementById('itemlist');
    var title = document.getElementById('title');
    var area = document.getElementById('area');
    var num = document.getElementById('num');
    var fff =  'num=B28&area='+area.value+'&title='+ title.value+"&itemlist="+ textarea.value.replace(/\n/g, "%0A")
 
    console.log(JSON.stringify(latest))

    var fff =  'num='+num.value+'&area='+area.value+'&title='+ title.value+"&itemlist="+ textarea.value.replace(/\n/g, "%0A")+"&ts="+(Date.now() / 1000)
     
    l2  = latest;
    l2.push(fff);
     
    var ff = {action:"update",docname: docname, email: email, data: l2};

    console.log("sending", JSON.stringify(ff))
    postData('api.php', ff)
  .then(data => {
   // console.log(data); // JSON data parsed by `data.json()` call
    // document.getElementById("demo").innerHTML = JSON.stringify(data);
    location.reload();
  });
 return;

  

    var xmlhttp = new XMLHttpRequest();   // new HttpRequest instance 
xmlhttp.open("POST", "api.php");
xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
xmlhttp.send(JSON.stringify({ "email": "hello@user.com", "response": { "name": "Tester" } }));
xmlhttp.onload = function () {
    document.getElementById("demo").innerHTML = xhr.responseText;
};
  return;

    var xhr = new XMLHttpRequest();
// xhr.open("POST", '/server', true);

// //Send the proper header information along with the request
// xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

// xhr.onreadystatechange = function() { // Call a function when the state changes.
//     if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
//         // Request finished. Do processing here.
//     }
// }
// xhr.send("foo=bar&lorem=ipsum");
// xhr.send(new Int8Array());
// xhr.send(document);


xhr.open("POST", "api.php");
xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
xhr.send(JSON.stringify(ff));
xhr.onload = function () {
    document.getElementById("demo").innerHTML = xhr.responseText;
};
   
}

// var form = document.getElementById('my-form');
// if (form.attachEvent) {
//     form.attachEvent("submit", processForm);
// } else {
//     form.addEventListener("submit", processForm);
// }

</script>

<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
    vertical-align: top;
  padding: 8px;
}
</style>