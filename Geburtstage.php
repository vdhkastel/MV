<?php
require('fpdf/fpdf.php');


class PDF extends FPDF
{
//Kopfzeile
function Header()
{
    //Logo HSVRM
    $this->Image('vdhkastel.jpg',10,8,50,0,'','http://www.VdHKastel.de/');
    //Arial fett 15
    $this->SetFont('Arial','B',24);
    //nach rechts gehen
    $this->Cell(80);
    //Titel
    $this->Cell(30,10,'Geburtstage',0,0,'C');
    //Zeilenumbruch
    $this->Ln(10);
	//nach rechts gehen
	$this->Cell(80);
    //Titel
    $this->Cell(30,10,date("Y"),0,0,'C');
	//Zeilenumbruch
    $this->Ln(40);
}

//Fusszeile
function Footer()
{
    //Position 1,5 cm von unten
    $this->SetY(-15);
    //Arial kursiv 8
    $this->SetFont('Arial','I',10);
    
    // Breite für 3-Spalten-Layout berechnen
    $rWidth=40;
    $lWidth=40;
    $mWidth = $this->w - $this->lMargin - $this->rMargin - $rWidth - $lWidth;
    //Copyright info
    $this->Cell($lWidth,10,'© K. Hens, 2013',0,0,'L',0,'http://www.VdHKastel.de/');
    //Seitenzahl
    $this->Cell($mWidth,10,'Seite '.$this->PageNo().'/{nb}',0,0,'C');
    //Zeitstempel
    $this->Cell($rWidth,10,'Stand: '.date('d.m.Y, H:i').' Uhr',0,0,'R');
}
}

//Define german names of months and int currentMonth 
$Monate = array("Januar","Februar","März", "April","Mai","Juni","Juli","August","September",
				"Oktober","November","Dezember");
$currentMonth=1;				

//Open database connection
	$con = mysql_connect("mysql.localhost","db47014","58rara4n");
	mysql_select_db("db47014", $con);
 
//Instanciation of inherited class
$pdf=new PDF();
$pdf->AliasNbPages();
//Disable automatic page break
$pdf->SetAutoPageBreak(false);

//Add first page
$pdf->AddPage('P');      //  P=Portrait, L=Landscape

//set initial y axis position per page
$y_axis_initial = 50;

//Set maximum rows per page
$max = 35;

//Set Row Height
$row_height = 6;

//print "January" on first page
$pdf->SetY($y_axis_initial);
$pdf->SetX(25);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(40, 6, $Monate[$currentMonth-1], 0, 0, 'L');

$y_axis = $y_axis_initial + $row_height;

//print column titles for the actual page
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetY($y_axis);
$pdf->SetX(25);
$pdf->Cell(40, 6, 'Name', 'LTB', 0, 'L', 1);
$pdf->Cell(50, 6, 'Vorname', 'TB', 0, 'L', 1);
$pdf->Cell(50, 6, 'Geburtsdatum', 'TB', 0, 'L', 1);
$pdf->Cell(15, 6, 'Alter', 1, 0, 'C', 1);

$y_axis = $y_axis + $row_height;

//Select the Products you want to show in your PDF file
//$result=mysql_query("SELECT Name, Vorname, DATE_FORMAT(Geburtstag, '%d.%m.%Y') AS Geburtstag, YEAR(NOW()) - YEAR(Geburtstag) - IF(DAYOFYEAR(NOW()) < DAYOFYEAR(CONCAT(YEAR(NOW()),DATE_FORMAT(Geburtstag, '-%m-%d'))),1,0) AS 'Alter' FROM Personen order by DATE_FORMAT( Geburtstag, '%m%d' )", $con);
$result=mysql_query("SELECT Name, Vorname, DATE_FORMAT(Geburtstag, '%d.%m.%Y') AS Geburtstag, YEAR(NOW()) - YEAR(Geburtstag) AS 'Alter' FROM Personen order by DATE_FORMAT( Geburtstag, '%m%d' )", $con);
//initialize counter
$i = 0;


while($row = mysql_fetch_array($result))
{   // read data 
    $Name = utf8_decode($row['Name']);
    $Vorname = utf8_decode($row['Vorname']);
	$Geburtstag = $row['Geburtstag'];
    $Alter = $row['Alter'];
    
	// check for current month
	$month=(int)substr($Geburtstag,-7,2); 
	
	//If the current row is the last one, create new page and print column title
    if ($i >= $max)
    {
        $pdf->AddPage('P');

        //print current month 
		$pdf->SetY($y_axis_initial);
		$pdf->SetX(25);
		$pdf->SetFont('Arial', 'B', 14);
		// update currentMonth if necessary
	    $currentMonth=$month;
		$pdf->Cell(40, 6, $Monate[$currentMonth-1], 0, 0, 'L');

		$y_axis = $y_axis_initial + $row_height;
		
		//print column titles for the current page
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetY($y_axis);
        $pdf->SetX(25);
        $pdf->Cell(40, 6, 'Name', 'LTB', 0, 'L', 1);
        $pdf->Cell(50, 6, 'Vorname', 'TB', 0, 'L', 1);
		$pdf->Cell(50, 6, 'Geburtsdatum', 'TB', 0, 'L', 1);
        $pdf->Cell(15, 6, 'Alter', 1, 0, 'C', 1);

        //Go to next row
        $y_axis = $y_axis + $row_height;

        //Set $i variable to 0 (first row)
        $i = 0;
    }

  
	// check if month is still currentMonth 
	if ($month == $currentMonth)
	{
      // alternating background color
      if ($i % 2)
      {   // odd lines
          $pdf->SetFillColor(232, 232, 232);
      }else{
          //even lines
          $pdf->SetFillColor(255, 255, 255);
      }
      $pdf->SetTextColor(0,0,0);
      $pdf->SetFont('Arial', '', 12);
      $pdf->SetY($y_axis);
      $pdf->SetX(25);
      $pdf->Cell(40, 6, $Name, 'LTB', 0, 'L', 1);
      $pdf->Cell(50, 6, $Vorname, 'TB', 0, 'L', 1);
      $pdf->Cell(50, 6, $Geburtstag, 'TB', 0, 'L', 1);
	  $pdf->Cell(15, 6, $Alter, 1, 0, 'C', 1);

      //Go to next row
      $y_axis = $y_axis + $row_height;
      $i = $i + 1;
	}else{
	  // switch to next month
      $currentMonth=$month;	  
	  //Skip one row
      $y_axis = $y_axis + $row_height;
      $i = $i + 1;
	  
	  // check if at least 5 lines are remaining on this page (otherwise force new page)
	  if (($i+5)<$max)
	  {
	    //print current month 
	    $pdf->SetY($y_axis);
        $pdf->SetX(25);
	    $pdf->SetFont('Arial', 'B', 14);
	    $pdf->Cell(40, 6, $Monate[$currentMonth-1], 0, 0, 'L');

	    $y_axis = $y_axis + $row_height;
	  
	    //print column titles for the current page
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetY($y_axis);
        $pdf->SetX(25);
        $pdf->Cell(40, 6, 'Name', 'LTB', 0, 'L', 1);
        $pdf->Cell(50, 6, 'Vorname', 'TB', 0, 'L', 1);
	    $pdf->Cell(50, 6, 'Geburtsdatum', 'TB', 0, 'L', 1);
        $pdf->Cell(15, 6, 'Alter', 1, 0, 'C', 1);

        //Go to next row
        $y_axis = $y_axis + $row_height;
        $i = $i + 2;
	  }else{ // Force table to next page
		$i = $max;
	  }	  
    }	
  }

mysql_close($con);


$pdf->Output();
?>
