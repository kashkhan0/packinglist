<?php

$earthRadius = 6378137;
$earthCircumference = $earthRadius * 2 * M_PI;
$pixelsPerTile = 256;
$projectionOriginOffset = $earthCircumference / 2;
$minLat = -85.0511287798;
$maxLat = 85.0511287798;
$PI = 3.1415926535897932384;


/*
CREATE TABLE  IF NOT EXISTS d1 ( key0, col, ref,  tt real, json STRING);
CREATE INDEX key0 on d1(key0);
CREATE INDEX tt on d1(tt); 
INSERT INTO "d1" VALUES("a@a.com","user","3",121,'{"user":{"password":"aaa", "guid":"6ba32d28-589c-11e6-9565-3c15c2e74670", "name":"John D Rocke", "company":"ACME","username":"a@a.com" }}');
INSERT INTO "d1" VALUES("6ba32d28-589c-11e6-9565-3c15c2e74670","jpg","0",123.4,'{"name":"d2.png"}');
INSERT INTO "d1" VALUES("6ba32d28-589c-11e6-9565-3c15c2e74670","jpg","1",127.4,'{"name":"d3.png"}');
INSERT INTO "d1" VALUES("b@a.com","user","3",122,'{"user":{"password":"aaa", "guid":"1358bcf4-589d-11e6-bf81-3c15c2e74670", "name":"James D Rocke", "company":"ACME","username":"b@a.com" }}');
INSERT INTO "d1" VALUES("1358bcf4-589d-11e6-bf81-3c15c2e74670","jpg","0",123.4,'{"name":"d2.png"}');
INSERT INTO "d1" VALUES("1358bcf4-589d-11e6-bf81-3c15c2e74670","jpg","1",125.4,'{"name":"d3.png"}');
*/


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



function findmany($dbfn, $column="key0", $needle='a', $count=100, $start=-1)
{

  $dbh=  new SQLite3($dbfn);
$stmt = $dbh->prepare('SELECT rowid, * FROM d1 where '.$column.'=:id ORDER by rowid DESC ;');
$stmt->bindValue(':id', $needle, SQLITE3_TEXT);
$result = $stmt->execute();
$out=array();
 
  while($r=$result->fetchArray())
  {
    // print_r($r);
  array_push($out, $r);
  


  }
 
return  $out ;
}

function findall($dbfn, $count=100, $start=-1)
{

  $dbh=  new SQLite3($dbfn);
$stmt = $dbh->prepare('SELECT rowid, * FROM d1  ORDER by rowid DESC LIMIT '.$count.';');
 
$result = $stmt->execute();
$out=array();
  while($r=$result->fetchArray())
  {
    // print_r($r);
      array_push($out, $r);
   
  }
 
return   $out ;
}



function insertone($dbfn, $data)
{
// INSERT INTO "d1" VALUES("a@a.com","user","3",121,'{"user":{"password":"aaa", "guid":"6ba32d28-589c-11e6-9565-3c15c2e74670", "name":"John D Rocke", "company":"ACME","username":"a@a.com" }}');
$djs = json_decode($data, true);
$key0 ="00000000-0000-0000-0000-000000000000";
$col ="0";
$ref ="0";
$tt =microtime(true);
$json ="[]";
if ( isset($djs['key0']) ) $key0 = $djs['key0'];
if ( isset($djs['col']) )  $col = $djs['col'];
if ( isset($djs['ref']) ) $ref = $djs['ref']; 
if ( isset($djs['json']) ) $json = $djs['json'];

$dbh=  new SQLite3($dbfn);
$stmt = $dbh->prepare('INSERT INTO "d1" VALUES(:key0 , :col, :ref, :tt , :js  );');
$stmt->bindValue(':key0', $key0, SQLITE3_TEXT);
$stmt->bindValue(':col', $col, SQLITE3_TEXT);
$stmt->bindValue(':ref', $ref, SQLITE3_TEXT);
$stmt->bindValue(':tt', $tt, SQLITE3_TEXT);
$stmt->bindValue(':js', $json, SQLITE3_TEXT);
$result = $stmt->execute();

 

return "ok";
}



function guid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}


function uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}



function pxpy2lonlat($x, $y, $zoom)

{

  $CBK = json_decode('[128, 256, 512, 1024, 2048, 4096, 8192, 16384, 32768, 65536, 131072, 262144, 524288, 1048576, 2097152, 4194304, 8388608, 16777216, 33554432, 67108864, 134217728, 268435456, 536870912, 1073741824, 2147483648, 4294967296, 8589934592, 17179869184, 34359738368, 68719476736, 137438953472]');
$CEK = json_decode('[0.7111111111111111, 1.4222222222222223, 2.8444444444444446, 5.688888888888889, 11.377777777777778, 22.755555555555556, 45.51111111111111, 91.02222222222223, 182.04444444444445, 364.0888888888889, 728.1777777777778, 1456.3555555555556, 2912.711111111111, 5825.422222222222, 11650.844444444445, 23301.68888888889, 46603.37777777778, 93206.75555555556, 186413.51111111112, 372827.02222222224, 745654.0444444445, 1491308.088888889, 2982616.177777778, 5965232.355555556, 11930464.711111112, 23860929.422222223, 47721858.844444446, 95443717.68888889, 190887435.37777779, 381774870.75555557, 763549741.5111111]');
$CFK = json_decode('[40.74366543152521, 81.48733086305042, 162.97466172610083, 325.94932345220167, 651.8986469044033, 1303.7972938088067, 2607.5945876176133, 5215.189175235227, 10430.378350470453, 20860.756700940907, 41721.51340188181, 83443.02680376363, 166886.05360752725, 333772.1072150545, 667544.214430109, 1335088.428860218, 2670176.857720436, 5340353.715440872, 10680707.430881744, 21361414.86176349, 42722829.72352698, 85445659.44705395, 170891318.8941079, 341782637.7882158, 683565275.5764316, 1367130551.1528633, 2734261102.3057265, 5468522204.611453, 10937044409.222906, 21874088818.445812, 43748177636.891624]');



    $foo = $CBK[$zoom];
    $lng = ($x - $foo) / $CEK[$zoom];
    $bar = ($y - $foo) / -$CFK[$zoom];
    $blam = 2 *  atan( exp($bar)) - M_PI / 2;
    $lat = $blam / (M_PI / 180);

 return  array('lon'=>$lng, 'lat'=>$lat  );
}
 

   
function lonlat2pxpy($lng,$lat,  $zoom){

  $CBK = json_decode('[128, 256, 512, 1024, 2048, 4096, 8192, 16384, 32768, 65536, 131072, 262144, 524288, 1048576, 2097152, 4194304, 8388608, 16777216, 33554432, 67108864, 134217728, 268435456, 536870912, 1073741824, 2147483648, 4294967296, 8589934592, 17179869184, 34359738368, 68719476736, 137438953472]');
$CEK = json_decode('[0.7111111111111111, 1.4222222222222223, 2.8444444444444446, 5.688888888888889, 11.377777777777778, 22.755555555555556, 45.51111111111111, 91.02222222222223, 182.04444444444445, 364.0888888888889, 728.1777777777778, 1456.3555555555556, 2912.711111111111, 5825.422222222222, 11650.844444444445, 23301.68888888889, 46603.37777777778, 93206.75555555556, 186413.51111111112, 372827.02222222224, 745654.0444444445, 1491308.088888889, 2982616.177777778, 5965232.355555556, 11930464.711111112, 23860929.422222223, 47721858.844444446, 95443717.68888889, 190887435.37777779, 381774870.75555557, 763549741.5111111]');
$CFK = json_decode('[40.74366543152521, 81.48733086305042, 162.97466172610083, 325.94932345220167, 651.8986469044033, 1303.7972938088067, 2607.5945876176133, 5215.189175235227, 10430.378350470453, 20860.756700940907, 41721.51340188181, 83443.02680376363, 166886.05360752725, 333772.1072150545, 667544.214430109, 1335088.428860218, 2670176.857720436, 5340353.715440872, 10680707.430881744, 21361414.86176349, 42722829.72352698, 85445659.44705395, 170891318.8941079, 341782637.7882158, 683565275.5764316, 1367130551.1528633, 2734261102.3057265, 5468522204.611453, 10937044409.222906, 21874088818.445812, 43748177636.891624]');

    $cbk = $CBK[$zoom];

    $x = ( ($cbk + ($lng * $CEK[$zoom])));

    $foo = sin($lat * M_PI / 180);
    if($foo < -0.9999)
        $foo = -0.9999;
    elseif ($foo > 0.9999)
        $foo = 0.9999;

    $y = ( ($cbk + (0.5 *  log((1+$foo)/(1-$foo)) * (-$CFK[$zoom]))));

    return array('px'=>$x, 'py'=>$y);



}
 





function tnow()
{

  return microtime(true); 
}

function thuman()
{
return date("c", microtime(true));
  return microtime(true); 
}



function lonlat2pxpyo( $longitude, $latitude,$zoomLevel)
{
  $EarthRadius = 6378137;
  $MinLatitude = -85.05112878;
  $MaxLatitude = 85.05112878;
  $MinLongitude = -180;
  $MaxLongitude = 180;
  // print("lat/lon:" . $latitude . "/" . $longitude);
    $latitude = Clip($latitude, $MinLatitude, $MaxLatitude);
    $longitude = Clip($longitude, $MinLongitude, $MaxLongitude);
  // print("lat/lon:" . $latitude . "/" . $longitude);
    $x = ($longitude + 180) / 360; 
    $sinLatitude = sin($latitude * pi() / 180);
    $y = 0.5 - log((1 + $sinLatitude) / (1 - $sinLatitude)) / (4 * pi());
    $mapSize = MapSize($zoomLevel);
    // print("mapsize:" . $mapSize);
    $pixelX = Clip($x * $mapSize + 0.5, 0, $mapSize - 1);
    $pixelY = Clip($y * $mapSize + 0.5, 0, $mapSize - 1);
    return array('px' => (int) $pixelX, 'py' => (int) $pixelY);
}


function Clip($n, $minValue, $maxValue)
{
    return min(max($n, $minValue), $maxValue);
}

function MapSize($zoomLevel)
{
    return (int) 256 << $zoomLevel;
}

function lonlat2xy($longitude,$latitude,  $zoomLevel)
{
  $EarthRadius = 6378137;
  $MinLatitude = -85.05112878;
  $MaxLatitude = 85.05112878;
  $MinLongitude = -180;
  $MaxLongitude = 180;
  // print("lat/lon:" . $latitude . "/" . $longitude);
    $latitude = Clip($latitude, $MinLatitude, $MaxLatitude);
    $longitude = Clip($longitude, $MinLongitude, $MaxLongitude);
  // print("lat/lon:" . $latitude . "/" . $longitude);
    $x = ($longitude + 180) / 360; 
    $sinLatitude = sin($latitude * pi() / 180);
    $y = 0.5 - log((1 + $sinLatitude) / (1 - $sinLatitude)) / (4 * pi());
    $mapSize = MapSize($zoomLevel);
    // print("mapsize:" . $mapSize);
    $pixelX = Clip($x * $mapSize + 0.5, 0, $mapSize - 1);
    $pixelY = Clip($y * $mapSize + 0.5, 0, $mapSize - 1);
    return array('x' => (int) $pixelX/256, 'y' => (int) $pixelY/256);
}


function lonlat2qk( $longitude,$latitude, $zoomLevel)
{
  $xy = lonlat2xy($longitude, $latitude, $zoomLevel);
$tileX =  $xy['x'];
$tileY =  $xy['y'];
  $quadKey = xy2qk($tileX, $tileY, $zoomLevel);
  return $quadKey;
}

function xy2qk($tileX, $tileY, $zoomLevel)
{
  $quadKey = "";
  for ($i = $zoomLevel; $i > 0; $i--)
  {
    $digit = '0';
    $mask = 1 << ($i - 1);
    if (($tileX & $mask) != 0)
    {
      $digit++;
    }
    if (($tileY & $mask) != 0)
    {
      $digit++;
      $digit++;
    }
    $quadKey .= $digit;
  }
  return $quadKey;
}

function charAt($str,$pos) {
  return (substr($str,$pos,1) !== false) ? substr($str,$pos,1) : -1;
} 


function qk2lonlat($quadkey) 
{ 
  $x=0; 
  $y=0; 
  $zoomlevel = strlen($quadkey); 
  //convert quadkey to tile xy coords 
  for ($i = 0; $i < $zoomlevel; $i++) 
  { 
    $factor = pow(2,$zoomlevel-$i-1); 
    switch (charAt($quadkey,$i)) 
    { 
      case '0': 
        break; 
      case '1': 
        $x += $factor; 
        break; 
      case '2': 
        $y += $factor; 
        break; 
      case '3': 
        $x += $factor; 
        $y += $factor; 
        break; 
    } 
  } 
  //convert tileXY into pixel coordinates for top left corners 
  $pixelX = $x*256; 
  $pixelY = $y*256; 
 
  //convert to latitude and longitude coordinates 
  $longitude = $pixelX*360/(256*pow(2,$zoomlevel)) - 180;
  $latitude = asin((exp((0.5 - $pixelY / 256 / pow(2,$zoomlevel)) * 4 * pi()) - 1) / (exp((0.5 - $pixelY / 256 / pow(2,$zoomlevel)) * 4 * pi()) + 1)) * 180 / pi();
  return array('lat' => $latitude, 'lon' => $longitude); 
}










function qk2xy($qk) 
{ 
  $zoom=strlen($qk);  
  $lonlat = qk2lonlat($qk);
  return  lonlat2xy($lonlat['lon'],$lonlat['lat'],$zoom);
}






function qk2qk($qk, $dx,$dy)
{
  $zoom=strlen($qk); 
  $xy = qk2xy($qk) ;
  return  xy2qk($xy['x'] +$dx, $xy['y'] +$dy, $zoom);
}





function corna2qklist($csets, $zoom )
{




// echo  round($xy['x']).' mintx'.   round($mintx) .' maxtx'. round($maxtx).'<br>';
// echo   round($xy['y']).' '.   round($minty).' '. round($maxty).'<br>';
//   ;

if( is_numeric($csets[0][0] ) )
{  

  $xy = lonlat2xy( $csets[0][0],$csets[0][1], $zoom);
  // echo '=='.$csets[0][0][0].' '.$csets[0][0][1].' '.json_encode($xy);
  $mintx = $xy['x'];
  $minty = $xy['y'];
  $maxtx = $xy['x'];
  $maxty = $xy['y'];
  foreach ($csets as   $key => $value) 
    { 
         // echo 'value '.json_encode($value).'<p>';

      $xy = lonlat2xy( $value[0],$value[1], $zoom);

      $mintx = $mintx < $xy['x'] ? $mintx : $xy['x'] ;
      $minty = $minty < $xy['y'] ? $minty : $xy['y'] ;
      $maxtx = $maxtx > $xy['x'] ? $maxtx : $xy['x'] ;
      $maxty = $maxty > $xy['y'] ? $maxty : $xy['y'] ;
  //   echo  round($xy['x']).' mintx'.   round($mintx) .' maxtx'. round($maxtx).'<br>';
  // echo   round($xy['y']).' '.   round($minty).' '. round($maxty).'<br>';
  //    
        // echo 'dxdy '. ($maxtx-$mintx+1) .' ' .($maxty-$minty+1);
    }

}

 
 elseif( is_numeric($csets[0][0][0]))
{



  $xy = lonlat2xy( $csets[0][0][0],$csets[0][0][1], $zoom);
  // echo '=='.$csets[0][0][0].' '.$csets[0][0][1].' '.json_encode($xy);
  $mintx = $xy['x'];
  $minty = $xy['y'];
  $maxtx = $xy['x'];
  $maxty = $xy['y'];



  foreach ($csets as $k0 => $v0) 
  {
    // echo json_encode($v0).'<p>';
    foreach ($v0 as $key => $value) 
    { 
        // echo json_encode($value).'<p>';

      $xy = lonlat2xy( $value[0],$value[1], $zoom);

      $mintx = $mintx < $xy['x'] ? $mintx : $xy['x'] ;
      $minty = $minty < $xy['y'] ? $minty : $xy['y'] ;
      $maxtx = $maxtx > $xy['x'] ? $maxtx : $xy['x'] ;
      $maxty = $maxty > $xy['y'] ? $maxty : $xy['y'] ;
  //   echo  round($xy['x']).' mintx'.   round($mintx) .' maxtx'. round($maxtx).'<br>';
  // echo   round($xy['y']).' '.   round($minty).' '. round($maxty).'<br>';
  //    
        // echo 'dxdy '. ($maxtx-$mintx+1) .' ' .($maxty-$minty+1);
    }
   
  }
}
else
{

  return;
}
  $qk0 = xy2qk($mintx,$minty, $zoom);

 $qklist=array();
$n=0; 



if (($maxty-$minty)<30 || ($maxtx-$mintx) <30)
 

  for ($iy=0; $iy < ($maxty-$minty+2); $iy++) 
  { 
    for ($ix=0; $ix < ($maxtx-$mintx+2); $ix++) 
    { 
      $comma=',';
      if ($n<1) $comma='';
      $n++;
         array_push($qklist, xy2qk($mintx+ $ix,$minty+ $iy, $zoom));
 
    }
    
  }
   else{
    // echo '$mintx.$minty.$maxtx.$maxty '.  $mintx.' '. $minty.' '.$maxtx.' '.$maxty.'<p>';
    // echo 'csets '.json_encode($csets);
   }

 
 
  return $qklist;

}






function getstyle()
{

return ' <style>
        html, body  {                  margin:20; 
 font-family: verdana,arial,helvetica,sans-serif;
font-size: 10px;
}
.cont{ display: flex;}
.row {  display: flex;flex-direction: row;}
.col {  display: flex;flex-direction: col;}
.box { display: flex;}
.center { display: flex; flex-direction: row; align-items: center; justify-content: center;}
    </style>

';


}


function jsout($arr)
{
  return str_replace(',',', ', json_encode($arr,  JSON_UNESCAPED_SLASHES));
}



function areap($cc)
{
  $ncc=count($cc);

  $s=0;
  for ($i=0; $i < $ncc; $i++) 
  { 

    // print_r($cc[$i][0]);

      // echo $cc[$i][0].','.$cc[$i][1].' '.$cc[($i+1)%$ncc][0].','.$cc[($i+1)%$ncc][1].' <br>';
  $s+=$cc[$i][0]*$cc[($i+1)%$ncc][1]   -  $cc[$i][1] *$cc[($i+1)%$ncc][0];
    # code...
  }

  return 0.5*abs($s);

}

function poly1($coords,$pxmin, $pymin, $z, $style, $label='')
{

  $center= polycenter($coords);

  $pp='';
  $p1='';
  $cc='';
  $nn=0;
$xvg=0;
$yvg=0;
$nn=0;
  foreach ($coords as  $v)
  {
   
    $latitude=$v[1];
    $longitude=$v[0];
    $latnext=$coords[$nn%count($coords)][1];
    $lonnext=$coords[$nn%count($coords)][0];

    $xy=lonlat2pxpy($longitude, $latitude, $z);
    $x=($xy['px']-$pxmin);
    $y=$xy['py']-$pymin;
    $xynext=lonlat2pxpy($lonnext, $latnext, $z);
    $xnext=($xynext['px']-$pxmin);
    $ynext=$xynext['py']-$pymin;

    $p1.='  '.$x.','.$y.' ';

    if ($xvg==0) $xvg =$x;
  if ($yvg==0) $yvg =$y;
  $xvg=($xvg*16+$x )/17;
  $yvg=($yvg*16+$y )/17;

    // $out.= "<p>".$v[0].','.$v[1].' '.LatLon2qk($latitude, $longitude, $z).' ' .$xy['x'].' '.$xy['y'];
    // $cc.='<circle cx="'.$x.'" cy="'.$y.'" r="'.(8+$nn*4).'"  style="fill:rgba(255,0,255,0.4); stroke:rgba(255,255,0,0.8);stroke-width:1px;"/>';
    if ($nn<2)
    {
  $pp.='<line x1="'.$x.'" y1="'.$y.'"  x2="'.$xnext.'" y2="'.$ynext.'"   style="fill:rgba(255,0,255,0.4); stroke:rgba(255,255,0,0.4);stroke-width:14px;"/>';
  

    }

     $nn++;
  }
  $pp.=$cc.''
// .'<polygon points=" '. $p1.' "  style="fill:rgba(255,255,255,0.0); stroke:rgba(255,255,255,0.8);stroke-width:6px;"  />'
  .'<polygon points=" '. $p1.' "  style="'.$style.'"  />';

 
      $pp.='<text  text-anchor="middle"  x="'.$xvg.'" y="'.$yvg.'" style="font-family: helvetica, sans-serif; font-weight: normal; font-style: normal" font-size="44px" fill="#0ff">'.$label.'</text>' ;


  return $pp;

}











function drawsvgrect( $coords, $z, $scale)
{
 // echo json_encode($qklist);
 //  echo json_encode($coords);  
 $qklist= corna2qklist($coords, $z  );
  // if(count($qklist)<1)$qklist= corn2qklist(array($coords), $z  );
 
  // echo json_encode($coords);
  // $svgout.='<g transform="matrix('.$matrix.')"><image xlink:href="/F5T/oc/Flight-Imagery/work/processed'.$imuri.'/small.jpg" x="0px" y="0px"  height="640px" width="962px"  style=" opacity:1;"/></g>';
  $minlonlat = qk2lonlat($qklist[0]) ;
  $minpxy=lonlat2pxpy($minlonlat['lon'], $minlonlat['lat'],$z);

  $pxmin=$minpxy['px'];
  $pymin=$minpxy['py'];

  $xy0 = qk2xy($qklist[0]);
  $qtiles='';

  $maxx=0;
  $maxy=0;
  foreach ($qklist as $qk) 
  {

    $xy = qk2xy($qk);
    $dx = $xy['x']-$xy0['x'];
    $dy = $xy['y']-$xy0['y'];
    // http://t0.tiles.virtualearth.net/tiles/a023010230030.jpeg?g=1398
      // $qtiles.='<image xlink:href=" http://t0.tiles.virtualearth.net/tiles/a0'
      //   .$qk.'.jpeg?g=1398" x="'.($dx*256)
      //   .'px" y="'
      //   .$dy*256
      //   .'px" height="256px" width="256px" style=" opacity:1;"/>';
    $qtiles.='<image xlink:href="/F5T/oc/Flight-Imagery/work/ve/a'
    .$qk.'.jpg" x="'.($dx*256)
    .'px" y="'
    .$dy*256
    .'px" height="256px" width="256px" style=" opacity:1;"/>';
    $maxx=$maxx>$dx*256? $maxx:$dx*256;
    $maxy=$maxy>$dy*256? $maxy:$dy*256;

  }

 $svgout='';

 $ww= $maxx*$scale;
 $hh = $maxy*$scale;
if ($ww<256)$ww=256;
if ($hh<256)$hh=256;

  $svgout='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="'.$ww.'" height="'. $hh.'">
  ';

  $svgout.='<g transform="scale('.$scale.')">';
  $svgout.='<g transform="translate(0,0)"><image xlink:href="/F5T/oc/Flight-Imagery/work/ve/a0230102102223200.jpg" x="0px" y="0px" height="256px" width="256px" style=" opacity:1;"/>';
$svgout.='<image xlink:href="/F5T/oc/Flight-Imagery/work/ve/a0230102102223200.jpg" x="0px" y="0px" height="2560px" width="2560px" style=" opacity:0.21;"/>';
$svgout.=$qtiles;





// echo 'coords'.json_encode($coords);
// echo '======'.abs($coords[0][0][0]);
 if( is_numeric($coords[0][0] ))
 {
// echo 'json_encode($coords)'. json_encode($coords);

  $svgout.=poly1($coords,$pxmin, $pymin, $z, "fill:rgba(220,110,0,0.02); stroke:rgba(255,250,30,0.8);stroke-width:4px;");
 
 }

if( is_numeric($coords[0][0][0] ))
{
 $nf=0;
    foreach ($coords  as $rect) 
  {
    $nf++;
    $xx=$nf/count($coords);
 // echo 'rect'.json_encode($rect);
    // echo 'drawsvgrect xx'.$xx.'<p>';
  $svgout.=poly1($rect,$pxmin, $pymin, $z, "fill:rgba(220,210,0,0.02); stroke:".rainbow($xx).";stroke-width:".(6)."px;", $nf);
  }
}
  
  // $svgout.=poly1($var['coords'],$pxmin, $pymin, $z, "fill:rgba(220,110,0,0.02); stroke:rgba(0,250,230,0.8);stroke-width:2px;");

  $svgout.='</g>';
  // $svgout.='<g transform="translate(1310,80)"><image xlink:href="/F5T/oc/Flight-Imagery/work/processed'.$imuri.'/small.jpg" x="0px" y="0px"  height="640px" width="962px"  style=" opacity:1;"/></g>';
  $svgout.='</g>';
  $svgout.='</svg>
  ';

  return $svgout;

}















function drawsvgrects( $coordslist, $z, $scale)
{
 // echo json_encode($qklist);
 //  echo json_encode($coords); 



 foreach ($coordslist as $key => $coords) {
 
 $qklist= corna2qklist($coords, $z  );
break;
}

 $minlonlat = qk2lonlat($qklist[0]) ;
  $minpxy=lonlat2pxpy($minlonlat['lon'], $minlonlat['lat'],$z);

  $pxmin=$minpxy['px'];
  $pymin=$minpxy['py'];

  $xy0 = qk2xy($qklist[0]);
  $qtiles='';

  $maxx=0;
  $maxy=0;
  foreach ($qklist as $qk) 
  {

    $xy = qk2xy($qk);
    $dx = $xy['x']-$xy0['x'];
    $dy = $xy['y']-$xy0['y'];

    $qtiles.='<image xlink:href="/F5T/oc/Flight-Imagery/work/ve/a'
    .$qk.'.jpg" x="'.($dx*256)
    .'px" y="'
    .$dy*256
    .'px" height="256px" width="256px" style=" opacity:1;"/>';
    $maxx=$maxx>$dx*256? $maxx:$dx*256;
    $maxy=$maxy>$dy*256? $maxy:$dy*256;

  }






 $svgout='';

 $ww= $maxx*$scale;
 $hh = $maxy*$scale;
if ($ww<256)$ww=256;
if ($hh<256)$hh=256;

  $svgout='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="'.$ww.'" height="'. $hh.'">
  ';

  $svgout.='<g transform="scale('.$scale.')">';
  $svgout.='<g transform="translate(0,0)"><image xlink:href="/F5T/oc/Flight-Imagery/work/ve/a0230102102223200.jpg" x="0px" y="0px" height="256px" width="256px" style=" opacity:1;"/>';
$svgout.='<image xlink:href="/F5T/oc/Flight-Imagery/work/ve/a0230102102223200.jpg" x="0px" y="0px" height="2560px" width="2560px" style=" opacity:0.21;"/>';
$svgout.=$qtiles;





$ncol = count($coordslist);
$colc =0;

 

 foreach ($coordslist as $key => $coords) {
$w = 4;

if ($colc < 1) $w=8;
  $colc++;
 
 $qklist= corna2qklist($coords, $z  );
  // if(count($qklist)<1)$qklist= corn2qklist(array($coords), $z  );
 
  // echo json_encode($coords);
  // $svgout.='<g transform="matrix('.$matrix.')"><image xlink:href="/F5T/oc/Flight-Imagery/work/processed'.$imuri.'/small.jpg" x="0px" y="0px"  height="640px" width="962px"  style=" opacity:1;"/></g>';
 $ddd = rainbow($colc/$ncol);

$ddd = rainbow($colc/$ncol);

 
 if( is_numeric($coords[0][0] ))
 {
  $svgout.=poly1($coords,$pxmin, $pymin, $z, "fill:rgba(220,110,0,0.02); stroke:".$ddd.";stroke-width:".$w."px;"); 
 }

if( is_numeric($coords[0][0][0] ))
{
 $nf=0;
    foreach ($coords  as $rect) 
  {
    $nf++;
    $xx=$nf/count($coords);
 // echo 'rect'.json_encode($rect);
    // echo 'drawsvgrect xx'.$xx.'<p>';
  $svgout.=poly1($rect,$pxmin, $pymin, $z, "fill:rgba(220,210,0,0.02); stroke:".rainbow($xx).";stroke-width:".(6)."px;", $nf);
  }
}
  
  // $svgout.=poly1($var['coords'],$pxmin, $pymin, $z, "fill:rgba(220,110,0,0.02); stroke:rgba(0,250,230,0.8);stroke-width:2px;");




}




  $svgout.='</g>';
  // $svgout.='<g transform="translate(1310,80)"><image xlink:href="/F5T/oc/Flight-Imagery/work/processed'.$imuri.'/small.jpg" x="0px" y="0px"  height="640px" width="962px"  style=" opacity:1;"/></g>';
  $svgout.='</g>';
  $svgout.='</svg>
  ';

  return $svgout;

}


















function matrixmult($m1,$m2){
  $r=count($m1);
  $c=count($m2[0]);
  $p=count($m2);
  if(count($m1[0])!=$p){throw new Exception('Incompatible matrixes');}
  $m3=array();
  for ($i=0;$i< $r;$i++){
    for($j=0;$j<$c;$j++){
      $m3[$i][$j]=0;
      for($k=0;$k<$p;$k++){
        $m3[$i][$j]+=$m1[$i][$k]*$m2[$k][$j];
      }
    }
  }
  return($m3);
}

function matrixtransp($m){
  $r=count($m);
  $c=count($m[0]);
  $mt=array();
  for($i=0;$i< $r;$i++){
    for($j=0;$j<$c;$j++){
      $mt[$j][$i]=$m[$i][$j];
    }
  }
  return($mt);
}




function n2imuri($in )
{
$ix=$in%1000;
 
if ($ix==0) {$ix=1;}
$imnum=sprintf('%04d', $ix);
$iy= floor($in/1000);
$dnum = sprintf('%02d', $iy);
return "1".$dnum ."D3200/DSC_".$imnum.'.JPG';

}

function imuri2imnum($imuri )
{
$ix=substr($imuri, -8,4)+0;
$iy = substr($imuri, -20,2)+0;
// echo 'imuri2imnum'. ($ix). ' '. ($iy)."<p>";
return  $ix+$iy*1000;
}



function getmfs($imuri)
{
$ii = imuri2imnum($imuri);

$pp = explode('/',$imuri);
$sd =  $pp[2];
$pwf =  $pp[0].'/'.$pp[1].'/wf00/'.$pp[3] .'/'.$pp[4] ;
 $delays = getdelays($pp[1], substr($pp[4] ,4,4));

 // echo "==getmfs==".$pp[1].jsout($delays);

$nc = intval( substr($pp[4],  4,4) );
$pmf00 =  $pp[0].'/'.$pp[1].'/mf00/'.$pp[3] .'/'.$pp[4] ;
$pmf01 =  $pp[0].'/'.$pp[1].'/mf01/'.$pp[3] .'/'.$pp[4] ;
$pmf02 =  $pp[0].'/'.$pp[1].'/mf02/'.$pp[3] .'/'.$pp[4] ;
$pmf03 =  $pp[0].'/'.$pp[1].'/mf03/'.$pp[3] .'/'.$pp[4] ;
$pmf00 =  $pp[0].'/'.$pp[1].'/mf00/'.n2imuri($ii+$delays[1]) ;
$pmf01 =  $pp[0].'/'.$pp[1].'/mf01/'.n2imuri($ii+$delays[2]) ;
$pmf02 =  $pp[0].'/'.$pp[1].'/mf02/'.n2imuri($ii+$delays[3]) ;
$pmf03 =  $pp[0].'/'.$pp[1].'/mf03/'.n2imuri($ii+$delays[4]);
return  array( $pwf , $pmf00 , $pmf01 , $pmf02 , $pmf03);
}



function getallmf($imuri)
{
$pp = explode('/',$imuri);
$sd =  $pp[2];
$pwf =  $pp[0].'/'.$pp[1].'/wf00/'.$pp[3] .'/'.$pp[4] ;

$nc = intval( substr($pp[4],  4,4) );
$pmf00 =  $pp[0].'/'.$pp[1].'/mf00/'.$pp[3] .'/'.$pp[4] ;
$pmf01 =  $pp[0].'/'.$pp[1].'/mf01/'.$pp[3] .'/'.$pp[4] ;
$pmf02 =  $pp[0].'/'.$pp[1].'/mf02/'.$pp[3] .'/'.$pp[4] ;
$pmf03 =  $pp[0].'/'.$pp[1].'/mf03/'.$pp[3] .'/'.$pp[4] ;
return  array( $pwf , $pmf00 , $pmf01 , $pmf02 , $pmf03);
}
function getnext($imuri)
{
$pp = explode('/',$imuri); 
   // echo 'getnext'. intval( substr($pp[3],  1,2) ).'<p>';

$nc = intval( substr($pp[4],  4,4) )+intval( substr($pp[3],  1,2) )*1000;
$pmf00 =  $pp[0].'/'. $pp[1].'/'.$pp[2].'/'. n2imuri( $nc+1 ) ;
  // echo 'getnext'.$imuri.' to '.$pmf00.'<p>';

return  $pmf00  ;
}



function getocg($imuri)
{
  $rr=array( "clon"=>0,"clat"=>0,"lon"=>0,"lat"=>0);
 $db = new SQLite3("centers.db");
  // echo "getocg {$imuri} ";
  $query = "SELECT json FROM imgs WHERE key0 = '{$imuri}' ORDER BY rowid DESC;;";
  $result = $db->query($query) or die('Query failed');

   while ($row = $result->fetchArray())
  {
     // echo "getocg {$row['json']} ";
    $jj = json_decode($row['json'], true);
    // echo '=centers=  '.$row['json'];
   $rr=$jj;
    
  }

return $rr;

}



function triangle($x=0.0)
{

 return max(1.0 - abs($x),0);


}
 

function rainbow($n,$opacity=1.0 )
{

  $x= ($n*3);
  $r=floor(255* (triangle($x ) +  triangle($x -3.0)));
  $g=floor(255*triangle($x-1.0));
  $b=floor(255*triangle($x-2.0));
  return 'rgba('.$r.','.$g.','.$b.', '.$opacity.')';
}

function pastel($n,$opacity=1.0 )
{

  $x= ($n*3);
  $r=155+floor(100* (triangle($x ) +  triangle($x -3.0)));
  $g=155+floor(100*triangle($x-1.0));
  $b=155+floor(100*triangle($x-2.0));
  return 'rgba('.$r.','.$g.','.$b.', '.$opacity.')';
}



function gray($n ,$opacity=1.0 )
{

 
  $r= floor(255*($n));
 
  return 'rgba('.$r.','.$r.','.$r.', '.$opacity.')';
}




function getgeoref($imuri)
{
  $rr=array( );
 $db = new SQLite3("gtime.db");
  
  $query = "SELECT json FROM imgs WHERE key0 = '{$imuri}' ORDER BY rowid DESC;;";
  $result = $db->query($query) or die('Query failed');

   while ($row = $result->fetchArray())
  {
     // echo " {$row['json']} ";
    $jj = json_decode($row['json'], true);
    // echo '=centers=  '.$row['json'];
   array_push($rr,$jj);
    
  }

return $rr;

}



function circle_distance($lon1,$lat1, $lon2,  $lat2) {
  $rad = M_PI / 180;
  return acos(sin($lat2*$rad) * sin($lat1*$rad) + cos($lat2*$rad) * cos($lat1*$rad) * cos($lon2*$rad - $lon1*$rad)) * 6371000;// meters
}

function bearing($lon1,$lat1, $lon2,  $lat2)
{

 $bearing = (rad2deg(atan2(sin(deg2rad($lon2) - deg2rad($lon1)) * cos(deg2rad($lat2)), cos(deg2rad($lat1)) * sin(deg2rad($lat2)) - sin(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($lon2) - deg2rad($lon1)))) + 360) % 360;
 return $bearing;
}


function cornpxpy2lonlat($qpxpy, $zoom)
{

$out= array();
foreach ($qpxpy as $key => $value) {
$ll = pxpy2lonlat($value[0], $value[1], $zoom);
array_push($out, array($ll['lon'],$ll['lat'])  );
  # code...
}
return $out;


}









function mult2($mat,$vec)
{
return array( $mat[0][0]*$vec[0]+$mat[0][1]*$vec[1], $mat[1][0]*$vec[0]+$mat[1][1]*$vec[1]);
}


function scalvec($a,$vec)
{
return  array( $a*$vec[0] , $a*$vec[1]);
}

function addvec($avec,$bvec)
{
return   array( $avec[0]*$bvec[0] , $avec[1]*$vec[1]);
}



function rotvec( $vec, $ang)
{
   $rad =  $ang*M_PI / 180;
   $mat=array(   array( cos($rad),-sin($rad)), array( sin($rad), cos($rad)));

 return   mult2($mat,$vec);

// return   array( $avec[0]*$bvec[0] , $avec[1]*$vec[1]);
}


function polycenter($poly)
{
  $sumx=0;
  $sumy=0;
  foreach ($poly as $key => $value) 
  {
 
    $sumx = $sumx+$value[0];
    $sumy = $sumy+$value[1];
  }
 
  $cc = array($sumx/count($poly),$sumy/count($poly));
  return $cc;
}




function growpoly($poly,$mag)
{
   $center = polycenter($poly);
 

// $q = array($poly[0], $poly[1], $poly[2], $poly[3]);
   $out=$poly;
  for ($i=0; $i < count($poly); $i++) 
  { 
     $out[$i][0]  = ($poly[$i][0]-$center[0])*$mag+$center[0];
    $out[$i][1]  = ($poly[$i][1]-$center[1])*$mag+$center[1];
 
  }
 

 
  return $out;
}






function getallgeo($coords, $allmf, $mag=1.0)
{



  $vec01a=($coords[1][0] - $coords[0][0]);
  $vec01b=($coords[1][1] - $coords[0][1]);
  $vec12a=($coords[2][0] - $coords[1][0]);
  $vec12b=($coords[2][1] - $coords[1][1]);

  $c01=array($coords[1][0] - $coords[0][0], $coords[1][1] - $coords[0][1]);
  $c12=array($coords[2][0] - $coords[1][0], $coords[2][1] - $coords[1][1]);
  $c23=array($coords[3][0] - $coords[2][0], $coords[3][1] - $coords[2][1]);
  $c30=array($coords[0][0] - $coords[3][0], $coords[0][1] - $coords[3][1]);

    //    0abc1
    //    defgh
    //    ijklm
    //    3nop2
  $qa=0.25;
  $qb=0.5;
  $qc=0.75;
  $qi=0.33;
  $qd=0.66;
  $pta= array($coords[0][0]+$qa*($c01[0]), $coords[0][1]+$qa*($c01[1])) ;     
  $ptd= array($coords[3][0]+$qd*($c30[0]), $coords[3][1]+$qd*($c30[1])) ;     
  $pti= array($coords[3][0]+$qi*($c30[0]), $coords[3][1]+$qi*($c30[1])) ;     
  $pth= array($coords[1][0]+$qi*($c12[0]), $coords[1][1]+$qi*($c12[1])) ;     
  $ptm= array($coords[1][0]+$qd*($c12[0]), $coords[1][1]+$qd*($c12[1])) ; 

  $cdh = array($pth[0]-$ptd[0],$pth[1]-$ptd[1]) ; 
  $cim = array($ptm[0]-$pti[0],$ptm[1]-$pti[1]) ; 

  $pte= array($ptd[0]+$qa*$cdh[0] ,$ptd[1]+$qa*$cdh[1] ) ;     
  $ptf= array($ptd[0]+$qb*$cdh[0] ,$ptd[1]+$qb*$cdh[1] ) ;     
  $ptg= array($ptd[0]+$qc*$cdh[0] ,$ptd[1]+$qc*$cdh[1] ) ;     
  $ptj= array($pti[0]+$qa*$cim[0] ,$pti[1]+$qa*$cim[1] ) ;     
  $ptk= array($pti[0]+$qb*$cim[0] ,$pti[1]+$qb*$cim[1] ) ;     
  $ptl= array($pti[0]+$qc*$cim[0] ,$pti[1]+$qc*$cim[1] ) ;     


  $qmf00=growpoly(array($ptd, $pte,$ptj,$pti), $mag);
  $qmf01=growpoly(array($pte, $ptf,$ptk,$ptj), $mag);
  $qmf02=growpoly(array($ptf, $ptg,$ptl,$ptk), $mag);
  $qmf03=growpoly(array($ptg, $pth,$ptm,$ptl), $mag);

// echo json_encode($qmf00);

 $quadsall = array($allmf[0]=>$coords,$allmf[1]=>$qmf00,$allmf[2]=>$qmf01,$allmf[3]=>$qmf02,$allmf[4]=>$qmf03);
// $quadsall = array($allmf[0]=>$coords,$allmf[4]=>$qmf00 );

return $quadsall;

}
  


function getdelays($date, $n)
{
  $d = array(1, 2, 3);
  $date = str_replace('/', '', $date);
  $delays=array();
if($date=='20150124' &&  $n<200){$delays=array(0, 27, 9, 9, 27, 0, 0, 0);}
if($date=='20150124' && $n>200){$delays=array(0,85,67,67,85,0,0,0);}
if($date=='20151125' ){$delays=array(-15, 0, 0, 0, 0, 0, 0, 0);}
if($date=='20151107' ){$delays=array(0,-21,19,19,19,0,0,0);}
if($date=='20150104' ){$delays=array(0,2,-1,-4,2,0,0,0);}
return $delays;
}

function getwf($imuri)
{
$ii = imuri2imnum($imuri);

$pp = explode('/',$imuri);
$sd =  $pp[2];
$delays = getdelays($pp[1], substr($pp[4] ,4,4));
$mf = substr($imuri, 10,4);

if ($mf=='wf00') $dd = 0;
if ($mf=='mf00') $dd = 1;
if ($mf=='mf01') $dd = 2;
if ($mf=='mf02') $dd = 3;
if ($mf=='mf03') $dd = 4;

$pwf =  $pp[0].'/'.$pp[1].'/wf00/'.n2imuri($ii-$delays[$dd]) ;


 // echo "<p>==getwf==".$pp[1].jsout($delays).'<p>';
 // echo "<p>==getwf=mf".$mf.'<p>';

$nc = intval( substr($pp[4],  4,4) );

return    $pwf ;
}




function ang($coords)
{
$vecs=array();
$nc =count($coords);
 for ($i=0; $i < $nc; $i++) 
 { 
 
   $v1 = array($coords[($i+1)%$nc][0]-$coords[($i)%$nc][0],$coords[($i+1)%$nc][1]-$coords[($i)%$nc][1]);
array_push($vecs, $v1);
  }
  $angs=array();
 for ($i=0; $i < $nc; $i++) 
 { 
  $v1=$vecs[($i)%$nc];
 $v2=$vecs[($i+1)%$nc];
 $ang = acos(dot($v1, $v2) / (norm($v1) * norm($v2))) *180/3.14159;
array_push($angs, $ang);

 }

return $angs;

}

function norm($vec)
{
    $norm = 0;
    $components = count($vec);

    for ($i = 0; $i < $components; $i++)
        $norm += $vec[$i] * $vec[$i];

    return sqrt($norm);
}

function dot($vec1, $vec2)
{
    $prod = 0;
    $components = count($vec1);

    for ($i = 0; $i < $components; $i++)
        $prod += ($vec1[$i] * $vec2[$i]);

    return $prod;
}
  function crossProduct(&$v1, &$v2, &$vR) {
    $vR[0] =   ( ($v1[1] * $v2[2]) - ($v1[2] * $v2[1]) );
    $vR[1] = - ( ($v1[0] * $v2[2]) - ($v1[2] * $v2[0]) );
    $vR[2] =   ( ($v1[0] * $v2[1]) - ($v1[1] * $v2[0]) );
  }
// use allmf wf georef



function getkv($emailraw = "",$key = "dummy", $storepath = "kvstore";)
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

function setkv($emailraw = "",$key = "dummy", $txt = "txt",$append = false,$storepath = "kvstore";)
{
  if ($emailraw == "") return;
  if (filter_var($emailraw, FILTER_VALIDATE_EMAIL)) {    $email = $emailraw;  }
  $storepath = "kvstore";
  if(!is_dir($storepath)){mkdir (  $storepath );}
  $userpath = $storepath."/".$email;
  if(!is_dir($userpath)){   mkdir (  $userpath );}
  $fn = $userpath."/".base64_encode($key).".txt";
  $handle = fopen($fn , "w");
  if ($handle) {fwrite($handle, $txt); fclose($handle);} 

}

function log2file($outfn, $data){
$outfile = fopen($outfn, "a") ;
fwrite($outfile, $data  );
 fclose($outfile);
}


?>
