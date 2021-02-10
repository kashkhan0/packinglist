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