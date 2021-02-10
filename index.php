<?php
function getkv($emailraw = "",$key = "dummy", $storepath = "kvf")
{
  if ($emailraw == "") return;

  if (filter_var($emailraw, FILTER_VALIDATE_EMAIL)) {$email = $emailraw;}

  
  $userpath = $storepath."/".$email;

  $fn = $userpath."/".base64_encode($key).".txt";
  $contents ="";
 $handle = fopen($fn , "r");
 if ($handle) {$contents =fread($handle,filesize($fn));fclose($handle);} 
 
return $contents;
}

function setkv($emailraw = "",$key = "dummy", $txt = "txt",$append = false,$storepath = "kvf")
{
    $email =  "";
  if ($emailraw == "") return;
  if (filter_var($emailraw, FILTER_VALIDATE_EMAIL)) {    $email = $emailraw;  }

  if(!is_dir($storepath)){mkdir (  $storepath );}
  $userpath = $storepath."/".$email;
  if(!is_dir($userpath)){   mkdir (  $userpath );}
  $fn = $userpath."/".base64_encode($key).".txt";
  $handle = fopen($fn , "w");
  if ($handle) {fwrite($handle, $txt); fclose($handle);} 

}



    require('fpdf.php');

class PDF extends FPDF
{
// // Load data
// function LoadData($file)
// {
//     // Read file lines
//     $lines = file($file);
//     $data = array();
//     foreach($lines as $line)
//         $data[] = explode(';',trim($line));
//     return $data;
// }

// Simple table
function BasicTable($header, $data)
{
    // Header
    foreach($header as $col)
        $this->Cell(40,7,$col,1);
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        foreach($row as $col)
            $this->Cell(40,6,$col,1);
        $this->Ln();
    }
}

// Better table
function ImprovedTable($header, $data)
{
    // Column widths
    $w = array(40, 35, 40, 45);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR');
        $this->Cell($w[1],6,$row[1],'LR');
        $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
        $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
        $this->Ln();
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}

// Colored table
function FancyTable($header, $data)
{
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    $w = array(40, 35, 40, 45);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Data
    $fill = false;
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
        $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
        $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
        $this->Ln();
        $fill = !$fill;
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}

function kTable($header, $data)
{
    // Colors, line width and bold font
    $this->SetFillColor(255,255,255);
    $this->SetTextColor(0);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(0.2);
    $this->SetFont('Arial','B',60);
  
   
    // Header
    $w = array(190, 35, 40, 45);
    // for($i=0;$i<count($header);$i++)
    //     $this->Cell($w[$i],7,$header[$i],1,0,'C',true);

    $this->Cell($w[0],18,$header,0,0,'C',true);
    $this->Ln();
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(255,255,255);
    $this->SetTextColor(0);
    $this->SetFont('','B',40);
    // Data
    $fill = false;
    $lines = explode("\n",$data);
    
    foreach($lines as $row)
    {
        $this->Cell($w[0],20,$row,'TB',0,'L',$fill);
        // $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
        // $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
        // $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
        // $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
        $this->Ln();
        $fill = !$fill;
    }
    // Closing line
    // $this->Cell(array_sum($w),0,'','T');
}



}


function ppage(){
$pdf = new PDF();
// Column headings
$header = array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)');
// Data loading
// $data = $pdf->LoadData('countries.txt');

$data =  array( array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)'),array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)') );
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->BasicTable($header,$data);
// $pdf->AddPage();
// $pdf->ImprovedTable($header,$data);
// $pdf->AddPage();
// $pdf->FancyTable($header,$data);
$pdf->Output();

}


function pp($t, $d){
    $pdf = new PDF();
    // Column headings
    $header = array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)');
    // Data loading
    // $data = $pdf->LoadData('countries.txt');
    
    $data =  array( array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)'),array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)') );
  
    $pdf->AddPage();
    $pdf->kTable($t, $d);
    // $pdf->AddPage();
    // $pdf->ImprovedTable($header,$data);
    // $pdf->AddPage();
    // $pdf->FancyTable($header,$data);
    $pdf->Output();
    
    }

    if (isset($_GET['title'])) {

        $t = isset($_GET['title']) ? $_GET['title']:"title";
        $d = isset($_GET['itemlist']) ? $_GET['itemlist']: "a\nb\nc";
        
        pp($t, $d);
            die();


    }

if (isset($_POST['formId'])) {
//     // echo("formid ".$_POST['formId']."<br />");
//     ppage();
$t = isset($_POST['boxn']) ? $_POST['boxn']:"";
$d = isset($_POST['itemlist']) ? $_POST['itemlist']: "";

pp($t, $d);
    die();
}



setkv("a@a.com","bb","ccc");

// echo getkv("a@a.com","bb")."<p>";


echo "<div>";
if (isset($_POST['boxn'])) {
    echo("boxn: " . $_POST['boxn'] . "<br />");
}
if  (isset($_POST['itemlist'])){
    echo json_encode($_POST);
    echo("itemlist: " . $_POST['itemlist'] . "<br />");
    $lines = $_POST['itemlist'];
    $data = explode("\n",$lines);
    echo "===".json_encode($data)."<br>";
    foreach($data as $line)
        echo("line" . $line . "<br />");

        echo("===<br />");
        $data = explode("\n",$lines);
        foreach($data as $line)
            echo("line" . $line . "<br />");

            echo("===<br />");
}
echo "</div>";

?>
<!doctype html>
<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

<body>

<div class="container">
  <!-- Content here -->

<h1>Packing List</h1>

<h2><a href="./?title=bdfvsdf&itemlist=a%0Asdfsd"> basic</a></h2>

<form action="./" method="post" id="my-form">
<div class="form-group"> 
   
   <label for="area">Title</label>
    <input  class="form-control" id="area"  name="area" value="area">
  
</div>
<div class="form-group"> 
   
   <label for="title">Title</label>
    <input  class="form-control" id="title"  name="title" value="title">
  
</div>
<div class="form-group"> 
   
   <label for="itemlist">Item list</label>
   <textarea class="form-control" id="itemlist" rows="10"  name="itemlist">gdfcsgsd</textarea>
   </div>
<div class="form-group"> 
   
   <input id="formId" name="formId" type="hidden" value="1">  
   <p><input type="submit" name="submit" value="Submit"  class="btn btn-primary"/>
   </div>
</form>
</div>
</body>
<script>

console.log('ho');

function processForm(e) {
    if (e.preventDefault) e.preventDefault();
    var textarea = document.getElementById('itemlist');
    var title = document.getElementById('title');
    var area = document.getElementById('area');
var ff =  './page.php?num=B28&area='+area.value+'&title='+ title.value+"&itemlist="+ textarea.value.replace(/\n/g, "%0A")
location.href = ff;
    /* do what you want with the form */

    // You must return false to prevent the default form behavior
    return false;
}

var form = document.getElementById('my-form');
if (form.attachEvent) {
    form.attachEvent("submit", processForm);
} else {
    form.addEventListener("submit", processForm);
}

</script>
</html>
 