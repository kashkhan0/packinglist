<?php
// http://localhost/packing/page.php?num=B28&area=Area&title=Title&itemlist=Gdfc%0Asgsd%0A%20ug%0AB%20ihb
require('fpdf.php');

class PDF extends FPDF
{

function kTable($seq,$num, $area,$header, $data)
{
    // Colors, line width and bold font
    $this->SetFillColor(255,255,255);
    $this->SetTextColor(0);
    $this->SetDrawColor(200);
    $this->SetLineWidth(0.2);

    $this->SetFont('Arial','',30);
    $this->SetXY(10,15);
    $this->Cell(120,16,$area,0,0,'L',false);
    $this->SetFont('Arial','B',50);
    $this->SetXY(10,34);
    $this->Cell(120,16,$header,0,0,'L',false);
    $w = array(190, 35, 40, 45);
    
    $this->Ln();
    $this->Ln();
    $this->SetFillColor(255,255,255);
    $this->SetTextColor(0);
    $this->SetFont('','',30);
    $fill = false;
    $lines = explode("\n",$data);
    
    foreach($lines as $row)
    {
        $this->Cell($w[0],20,$row,'TB',0,'L',$fill);
        $this->Ln();
        $fill = !$fill;
    }
    $this->SetTextColor(127);
    $this->SetFont('Arial','',120);
    $this->SetXY(120,25);
    $this->Cell(80,24,$num,0,0,'R',false);

    $this->SetFillColor(255,33,255);
    $this->SetTextColor(0);
    $this->SetFont('Arial','',10);
    $this->SetXY($this->w * 0.5 - 5,10);
    $this->Cell(10,8,$seq,0,0,'C',false);
}
}


function pp($seq,$n,$area, $t, $d){
    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->kTable($seq,$n,$area,$t, $d);
    $pdf->Output();
}

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

if (isset($_GET['doc'])) {
    $dec =  base64_decode($_GET['doc']);
   
    // ["num=1&area=aaaa&title=a&itemlist=a a&ts=1612900144.054",
    // "num=2&area=bb&title=h&itemlist=h &ts=1612904535.365",
    // "num=3&area=cc&title=c&itemlist=v b&ts=1612900880.1"]
    $json = preg_replace('/[[:cntrl:]]/', '', $dec);
    $dec2 = json_decode($json);
    $pdf = new PDF();
    foreach($dec2 as $js){
        // echo $js.'<br>';
        $pdf->AddPage();
        $d = parsequery($js);
        
        $pdf->kTable($d["itemlist"],$d["num"],$d["area"],$d["title"], $d["itemlist"]);
    }

    $pdf->Output();
    die();
}

if (isset($_GET['title'])) {
    $seq = isset($_GET['seq']) ? $_GET['seq']:"1";
    $a = isset($_GET['area']) ? $_GET['area']:"storage";
    $n = isset($_GET['num']) ? $_GET['num']:"128";
    $t = isset($_GET['title']) ? $_GET['title']:"title";
    $d = isset($_GET['itemlist']) ? $_GET['itemlist']: "a\nb\nc";
    pp($seq,$n,$a,$t, $d);
} else {
    pp("","A28","area","title", "a\nb");
}


?>