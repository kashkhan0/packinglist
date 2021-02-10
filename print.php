<?php
require('fpdf.php');  
class KPDF extends FPDF
{
function Circle($x, $y, $r, $style='D')
{
    $this->Ellipse($x,$y,$r,$r,$style);
}

function Sector($xc, $yc, $r, $a, $b, $style='FD', $cw=true, $o=90)
{
    $d0 = $a - $b;
    if($cw){
        $d = $b;
        $b = $o - $a;
        $a = $o - $d;
    }else{
        $b += $o;
        $a += $o;
    }
    while($a<0)
        $a += 360;
    while($a>360)
        $a -= 360;
    while($b<0)
        $b += 360;
    while($b>360)
        $b -= 360;
    if ($a > $b)
        $b += 360;
    $b = $b/360*2*M_PI;
    $a = $a/360*2*M_PI;
    $d = $b - $a;
    if ($d == 0 && $d0 != 0)
        $d = 2*M_PI;
    $k = $this->k;
    $hp = $this->h;
    if (sin($d/2))
        $MyArc = 4/3*(1-cos($d/2))/sin($d/2)*$r;
    else
        $MyArc = 0;
    //first put the center
    $this->_out(sprintf('%.2F %.2F m',($xc)*$k,($hp-$yc)*$k));
    //put the first point
    $this->_out(sprintf('%.2F %.2F l',($xc+$r*cos($a))*$k,(($hp-($yc-$r*sin($a)))*$k)));
    //draw the arc
    if ($d < M_PI/2){
        $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                    $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                    $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                    $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                    $xc+$r*cos($b),
                    $yc-$r*sin($b)
                    );
    }else{
        $b = $a + $d/4;
        $MyArc = 4/3*(1-cos($d/8))/sin($d/8)*$r;
        $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                    $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                    $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                    $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                    $xc+$r*cos($b),
                    $yc-$r*sin($b)
                    );
        $a = $b;
        $b = $a + $d/4;
        $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                    $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                    $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                    $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                    $xc+$r*cos($b),
                    $yc-$r*sin($b)
                    );
        $a = $b;
        $b = $a + $d/4;
        $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                    $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                    $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                    $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                    $xc+$r*cos($b),
                    $yc-$r*sin($b)
                    );
        $a = $b;
        $b = $a + $d/4;
        $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                    $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                    $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                    $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                    $xc+$r*cos($b),
                    $yc-$r*sin($b)
                    );
    }
    //terminate drawing
    if($style=='F')
        $op='f';
    elseif($style=='FD' || $style=='DF')
        $op='b';
    else
        $op='s';
    $this->_out($op);
}

function _Arc($x1, $y1, $x2, $y2, $x3, $y3 )
{
    $h = $this->h;
    $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
        $x1*$this->k,
        ($h-$y1)*$this->k,
        $x2*$this->k,
        ($h-$y2)*$this->k,
        $x3*$this->k,
        ($h-$y3)*$this->k));
}

function Arce($x1, $y1, $x2, $y2, $x3, $y3, $style='FD' )
{   
    $k=$this->k;
    $h=$this->h;
    $this->_out(sprintf('%.2F %.2F m',($x1)*$k,($h-$y1)*$k));
    // $this->_out(sprintf('%.2F %.2F l',($x1)*$k,($h-$y1)*$k));
    // $this->_out(sprintf('%.2F %.2F l',($x2)*$k,($h-$y2)*$k));
    // $this->_out(sprintf('%.2F %.2F l',($x3)*$k,($h-$y3)*$k));
    $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
    $x1*$k,
    ($h-$y1)*$k,
    $x2*$k,
    ($h-$y2)*$k,
    $x3*$k,
    ($h-$y3)*$k));

    // $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
    // 0*$k,
    // 0*$k,
    // 100*$k,
    // 0*$k,
    // 100*$k,
    // 200*$k));

    $this->_out(sprintf('%.2F %.2F m',(0)*$k,(0)*$k));
    // $this->_out(sprintf('%.2F %.2F l',($x3)*$k,($y3)*$k));
    // $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
    // $x3*$this->k,
    // ($h-$y3)*$this->k,
    // $x2*$this->k,
    // ($h-$y2)*$this->k,
    // $x1*$this->k,
    // ($h-$y1)*$this->k));


    if($style=='F')
    $op='f';
elseif($style=='FD' || $style=='DF')
    $op='b';
else
    $op='s';
$this->_out($op);
}



function Ellipse($x, $y, $rx, $ry, $style='D')
{
    if($style=='F')
        $op='f';
    elseif($style=='FD' || $style=='DF')
        $op='B';
    else
        $op='S';
    $lx=4/3*(M_SQRT2-1)*$rx;
    $ly=4/3*(M_SQRT2-1)*$ry;
    $k=$this->k;
    $h=$this->h;
    $this->_out(sprintf('%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
        ($x+$rx)*$k,($h-$y)*$k,
        ($x+$rx)*$k,($h-($y-$ly))*$k,
        ($x+$lx)*$k,($h-($y-$ry))*$k,
        $x*$k,($h-($y-$ry))*$k));
    $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
        ($x-$lx)*$k,($h-($y-$ry))*$k,
        ($x-$rx)*$k,($h-($y-$ly))*$k,
        ($x-$rx)*$k,($h-$y)*$k));
    $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
        ($x-$rx)*$k,($h-($y+$ly))*$k,
        ($x-$lx)*$k,($h-($y+$ry))*$k,
        $x*$k,($h-($y+$ry))*$k));
    $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c %s',
        ($x+$lx)*$k,($h-($y+$ry))*$k,
        ($x+$rx)*$k,($h-($y+$ly))*$k,
        ($x+$rx)*$k,($h-$y)*$k,
        $op));
}

function Arc2($xc, $yc, $r, $a, $b, $style='FD', $cw=true, $o=90)
{
    $d0 = $a - $b;
    if($cw){
        $d = $b;
        $b = $o - $a;
        $a = $o - $d;
    }else{
        $b += $o;
        $a += $o;
    }
    while($a<0)
        $a += 360;
    while($a>360)
        $a -= 360;
    while($b<0)
        $b += 360;
    while($b>360)
        $b -= 360;
    if ($a > $b)
        $b += 360;
    $b = $b/360*2*M_PI;
    $a = $a/360*2*M_PI;
    $d = $b - $a;
    if ($d == 0 && $d0 != 0)
        $d = 2*M_PI;
    $k = $this->k;
    $hp = $this->h;
    if (sin($d/2))
        $MyArc = 4/3*(1-cos($d/2))/sin($d/2)*$r;
    else
        $MyArc = 0;
    //first put the center
    $this->_out(sprintf('%.2F %.2F m',($xc)*$k,($hp-$yc)*$k));
    //put the first point
    $this->_out(sprintf('%.2F %.2F m',($xc+$r*cos($a))*$k,(($hp-($yc-$r*sin($a)))*$k)));
    //draw the arc
    if ($d < M_PI/2){
        $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                    $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                    $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                    $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                    $xc+$r*cos($b),
                    $yc-$r*sin($b)
                    );
    }else{
        $b = $a + $d/4;
        $MyArc = 4/3*(1-cos($d/8))/sin($d/8)*$r;
        $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                    $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                    $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                    $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                    $xc+$r*cos($b),
                    $yc-$r*sin($b)
                    );
        $a = $b;
        $b = $a + $d/4;
        $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                    $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                    $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                    $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                    $xc+$r*cos($b),
                    $yc-$r*sin($b)
                    );
        $a = $b;
        $b = $a + $d/4;
        $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                    $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                    $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                    $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                    $xc+$r*cos($b),
                    $yc-$r*sin($b)
                    );
        $a = $b;
        $b = $a + $d/4;
        // $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
        //             $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
        //             $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
        //             $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
        //             $xc+$r*cos($b),
        //             $yc-$r*sin($b)
        //             );

              
    }

    $this->_out(sprintf('%.2F %.2F m',($xc+$r*cos($a))*$k,(($hp-($yc-$r*sin($a)))*$k)));   
    //terminate drawing
    if($style=='F')
        $op='f';
    elseif($style=='FD' || $style=='DF')
        $op='b';
    else
        $op='s';

        $op='s';
    $this->_out($op);
}
 // Draws a Bézier curve (the Bézier curve is tangent to the line between the control points at either end of the curve)
    // Parameters:
    // - x0, y0: Start point
    // - x1, y1: Control point 1
    // - x2, y2: Control point 2
    // - x3, y3: End point
    // - style: Style of rectangule (draw and/or fill: D, F, DF, FD)
    // - line_style: Line style for curve. Array like for SetLineStyle
    // - fill_color: Fill color. Array with components (red, green, blue)
    function Curve($x0, $y0, $x1, $y1, $x2, $y2, $x3, $y3, $style = '', $line_style = null, $fill_color = null) {
        if (!(false === strpos($style, 'F')) && $fill_color) {
            list($r, $g, $b) = $fill_color;
            $this->SetFillColor($r, $g, $b);
        }
        switch ($style) {
            case 'F':
                $op = 'f';
                $line_style = null;
                break;
            case 'FD': case 'DF':
                $op = 'B';
                break;
            default:
                $op = 'S';
                break;
        }
        if ($line_style)
            $this->SetLineStyle($line_style);

        $this->_Point($x0, $y0);
        $this->_Curve($x1, $y1, $x2, $y2, $x3, $y3);
        $this->_out($op);
    }
    // Sets line style
    // Parameters:
    // - style: Line style. Array with keys among the following:
    //   . width: Width of the line in user units
    //   . cap: Type of cap to put on the line (butt, round, square). The difference between 'square' and 'butt' is that 'square' projects a flat end past the end of the line.
    //   . join: miter, round or bevel
    //   . dash: Dash pattern. Is 0 (without dash) or array with series of length values, which are the lengths of the on and off dashes.
    //           For example: (2) represents 2 on, 2 off, 2 on , 2 off ...
    //                        (2,1) is 2 on, 1 off, 2 on, 1 off.. etc
    //   . phase: Modifier of the dash pattern which is used to shift the point at which the pattern starts
    //   . color: Draw color. Array with components (red, green, blue)
    function SetLineStyle($style) {
        extract($style);
        if (isset($width)) {
            $width_prev = $this->LineWidth;
            $this->SetLineWidth($width);
            $this->LineWidth = $width_prev;
        }
        if (isset($cap)) {
            $ca = array('butt' => 0, 'round'=> 1, 'square' => 2);
            if (isset($ca[$cap]))
                $this->_out($ca[$cap] . ' J');
        }
        if (isset($join)) {
            $ja = array('miter' => 0, 'round' => 1, 'bevel' => 2);
            if (isset($ja[$join]))
                $this->_out($ja[$join] . ' j');
        }
        if (isset($dash)) {
            $dash_string = '';
            if ($dash) {
                $tab = explode(',', $dash);
                $dash_string = '';
                foreach ($tab as $i => $v) {
                    if ($i > 0)
                        $dash_string .= ' ';
                    $dash_string .= sprintf('%.2F', $v);
                }
            }
            if (!isset($phase) || !$dash)
                $phase = 0;
            $this->_out(sprintf('[%s] %.2F d', $dash_string, $phase));
        }
        if (isset($color)) {
            list($r, $g, $b) = $color;
            $this->SetDrawColor($r, $g, $b);
        }
    }

      // Draws a line
    // Parameters:
    // - x1, y1: Start point
    // - x2, y2: End point
    // - style: Line style. Array like for SetLineStyle
    function Line($x1, $y1, $x2, $y2, $style = null) {
        if ($style)
            $this->SetLineStyle($style);
        parent::Line($x1, $y1, $x2, $y2);
    }

     /* PRIVATE METHODS */

    // Sets a draw point
    // Parameters:
    // - x, y: Point
    function _Point($x, $y) {
        $this->_out(sprintf('%.2F %.2F m', $x * $this->k, ($this->h - $y) * $this->k));
    }

    // Draws a line from last draw point
    // Parameters:
    // - x, y: End point
    function _Line($x, $y) {
        $this->_out(sprintf('%.2F %.2F l', $x * $this->k, ($this->h - $y) * $this->k));
    }

    // Draws a Bézier curve from last draw point
    // Parameters:
    // - x1, y1: Control point 1
    // - x2, y2: Control point 2
    // - x3, y3: End point
    function _Curve($x1, $y1, $x2, $y2, $x3, $y3) {
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', $x1 * $this->k, ($this->h - $y1) * $this->k, $x2 * $this->k, ($this->h - $y2) * $this->k, $x3 * $this->k, ($this->h - $y3) * $this->k));
    }


}



// $pdf=new PDF_Sector();
$pdf = new KPDF('L','mm','Letter');
$pdf->SetFont('arial', '', 10);
$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,20,5,10', 'phase' => 10, 'color' => array(255, 0, 0));
$style2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255, 0, 0));
$style3 = array('width' => 1, 'cap' => 'round', 'join' => 'round', 'dash' => '2,10', 'color' => array(255, 0, 0));
$style4 = array('L' => 0,
                'T' => array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => '20,10', 'phase' => 10, 'color' => array(100, 100, 255)),
                'R' => array('width' => 0.50, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(50, 50, 127)),
                'B' => array('width' => 0.75, 'cap' => 'square', 'join' => 'miter', 'dash' => '30,10,5,10'));
$style5 = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
$style6 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '0', 'color' => array(0, 255, 0));
$style7 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(200, 200, 0));

$pdf->AddPage();
$xc=105;
$yc=120;
$r=40;
$pdf->SetFillColor(100,255,255);
$pdf->Arc2($xc,$yc,$r,20,100);
// $pdf->SetFillColor(120,255,120);
// $pdf->Sector($xc,$yc,$r,120,250);
// $pdf->SetFillColor(255,120,120);
// $pdf->Sector($xc,$yc,$r,250,20);
// Curve
$pdf->Text(5, 37, 'Curve examples');
// $pdf->Curve(5, 40, 30, 55, 70, 45, 60, 75, null, $style6, array(0, 220, 200));
// $pdf->Curve(5, 40, 70, 75, 150, 45, 100, 75, 'F', $style6, array(200, 0, 200));
// $pdf->Curve(140, 40, 150, 55, 180, 45, 200, 75, 'DF', $style6, array(200, 220, 0));
// $pdf->Arce(100, 100, 150, 100, 150, 50, null);
// $pdf->Arce(150, 50, 150, 0, 100, 0, null);
// $pdf->Arce(100, 100, 150, 100, 150, 50, null);
$pdf->Output();
die();

$get = $_GET;

$url =  isset($get['url']) ? $get['url'] :'http://localhost/scaleprint/a.jpg';
$xmm = isset($get['xmm']) ? $get['xmm'] : 400 ;
$ymm = isset($get['ymm']) ? $get['xmm'] : 300 ;
 
// 215.9 x 279.4
$pw = 279.4;
$ph = 215.9;


$xborder = 20;
$yborder = $xborder;
$xpage = $pw - 2 * $xborder;
$ypage = $ph - 2 * $yborder;
$xpages = ceil($xmm/$xpage);
$ypages = ceil($ymm/$ypage);
 

// // Insert a logo in the top-left corner at 300 dpi
// $pdf->Image('logo.png',10,10,-300);
// // Insert a dynamic image from a URL
$image1 = "a.jpg";
$pdf = new KPDF('L','mm','Letter');
$pdf->SetFont('Arial','',8);


// $pdf->AddPage();
// $pdf->Text(40,10,'Hello World !',1);
// $pdf->Cell(60,10,'=='.$xpages.' '.$ypages,0,1,'C');
 
 

for($dy = 0; $dy < $ypages; $dy++) {
    for($dx = 0; $dx < $xpages; $dx++){

    $pdf->AddPage();
    $pdf->SetXY(0,0);
    // $pdf->Text($xborder-5,$yborder-6,'x'.($dx+1).'/'.$xpages,1,'R' );
    // $pdf->Text($xborder-8,$yborder-1,'y'.($dy+1).'/'.$ypages,1,'R' );

    $pdf->SetXY(10,10);
    $pdf->Cell(10,4, 'x'.($dx+1).'/'.$xpages, 0, true, 'R');
    $pdf->SetXY(5,15);
    $pdf->Cell(10,4,'y'.($dy+1).'/'.$ypages, 0, true, 'R');
 
    $x0 = $xborder - $dx * $xpage;
    $y0 = $yborder - $dy * $ypage;

  



    $pdf->SetLineWidth(0.35);
    $pdf->SetDrawColor(222);
    $pdf->Line( $x0,$y0, $x0+$xmm, $y0);
    $pdf->Line( $x0,$y0, $x0, $y0+$ymm);
    $pdf->Line( $x0,$y0+$ymm, $x0+$xmm, $y0+$ymm);
    $pdf->Line( $x0+$xmm,$y0, $x0+$xmm, $y0+$ymm);
    // $pdf->Image('http://chart.googleapis.com/chart?cht=p3&chd=t:60,40&chs=250x100&chl=Hello|World',60,30,$xmm,$ymm,'PNG');
    $pdf->Image($url,$x0,$y0,$xmm);
    $pdf->SetDrawColor(222, 44, 88);
    $pdf->Line( 0, $yborder,$xborder,$yborder);
    $pdf->Line( $xborder, 0,$xborder,$yborder);
    $pdf->Line( $pw - $xborder, $yborder,$pw,$yborder);
    $pdf->Line( $xborder, $ph - $yborder,$xborder,$ph);
    $pdf->Line( $pw - $xborder, $ph - $yborder,$pw,$ph);
    $r = 2;
    $pdf->Circle($xborder,$yborder,$r,'D');
    $pdf->Circle($pw-$xborder,$yborder,$r,'D');
    $pdf->Circle($xborder,$ph-$yborder,$r,'D');
    $pdf->Circle($pw-$xborder,$ph-$yborder,$r,'D');

    $pdf->SetLineWidth(0.2);
    $pdf->SetDrawColor(111);
     for($ex = 0; $ex < $xmm+41; $ex++){
        $xx = $x0 + $ex;
        $yy = $yborder-2;
        if ($xx < $xborder){
            continue;
        }
        if ($ex%10 == 5 ) {
            $yy =  $yborder-3;
        }
        if ($ex%10 == 0 ) {
            $yy =  $yborder-5;
            // $pdf->Text($xx,$yy,''.$ex,1);
            $pdf->Text($xx,$yborder-6,''.$ex,1);
 
            $pdf->Text($xx,$ph-$yborder+8,''.$ex,1);
 
        }

        $pdf->Line( $xx,$yy, $xx, $yborder);
        $pdf->Line( $xx,$ph-$yy, $xx, $ph-$yborder);
     }

     for($ey = 0; $ey < $ymm+41; $ey++){
        $xx = $xborder-2;
        $yy = $y0 + $ey;
        if ($yy < $yborder){
            continue;
        }
        if ($ey%10 == 5 ) {
            $xx =  $xborder-3;
        }
        if ($ey%10 == 0 ) {
            $xx =  $xborder-5;
            $pdf->Text($xx-5,$yy+2.5,''.$ey,1);
            $pdf->Text($pw - $xx ,$yy+2.5,''.$ey,1);
            // $pdf->SetXY(8,$yy);
            // $pdf->Cell(1,4,$ey, 0, true, 'R');
 
        }

        $pdf->Line( $xx,$yy, $xborder, $yy);
        $pdf->Line( $pw-$xx,$yy, $pw-$xborder, $yy);
    }



  }
}


 

// $pdf->Image($image1,10,10,300);

$pdf->Output();
die();
?>