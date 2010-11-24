<?php
require('fpdf.php');

 $user="user";
 $host="localhost";
 $password="";
 $dbname="friendpaper";

 $cxn = mysql_connect($host,$user,$password)
     	  or die ("Couldn't connect to server");
 
 $link = mysql_select_db($dbname, $cxn);

class PDF extends FPDF
{
//Current column
var $col=0;
//Ordinate of column start
var $y0;

var $B;
var $I;
var $U;
var $HREF;

function PDF($orientation='P',$unit='mm',$format='A4')
{
    //Call parent constructor
    $this->FPDF($orientation,$unit,$format);
    //Initialization
    $this->B=0;
    $this->I=0;
    $this->U=0;
    $this->HREF='';
}

function Header()
{
    //Page header
    global $title;
	
	 $user="user";
	 $host="localhost";
	 $password="";
	 $dbname="friendpaper";

	 $cxn = mysql_connect($host,$user,$password)
			  or die ("Couldn't connect to server");
	 
	 $link = mysql_select_db($dbname, $cxn);
 
 // Get current issue
	
	$query_getID = "SELECT issue FROM publications WHERE onlyCol=1";
 	$result_getID = mysql_query($query_getID, $cxn)
     	  or die ("Couldn't execute query 1");

	$userIDr = mysql_fetch_assoc($result_getID);
	
	$currentIssue = $userIDr['issue'];
 
	if ($this->PageNo() == 1)
	{
	
	function makeName($currentIssue)
	{
		if ($currentIssue > 12)
		{
		if ( $i = ($currentIssue % 12) == 0 )
		{
		$i = 12;
		$v = (($currentIssue ) / 12);
		}
		else
		{
		$i = ($currentIssue % 12);
		$v = (($currentIssue - $i) / 12)+1;
		}
		}
		else
		{
		$i = $currentIssue;
		$v = 1;
		}
		
		$str = 'Vol. '.$v.', Issue '.$i;
		
		return $str;
	}
	
	$getString = makeName($currentIssue);
	
	$this->SetFont('Helvetica','B',10);
    $w=$this->GetStringWidth($title)+6;
    $this->SetDrawColor(0,0,0);
    $this->SetFillColor(255,255,255);
    //$this->SetTextColor(220,50,50);
    //$this->SetLineWidth(.1);
	
	//$this->SetX(10);//(210-$w)/2);
    //$this->Cell(0,3,$title,1,0,'L',true);

    $this->SetX(160);//(210-$w)/2);
	$this->Ln(4);    
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.1);
	$this->Line(10, 39, 200, 39);
	$this->Line(10, 46, 200, 46);
	$this->SetFont('Times','',48);
	$this->Cell(0,20,'Mainely News',0,1,'C');
	$this->SetFont('Helvetica','',9);
	$this->MultiCell(0,1,$getString.'     ',0,'R');
	$this->Ln(7);
	$this->MultiCell(0,1,'Announcements & Events      -      Headlines      -      Updates      -      Blogs ',0,'C');
	$this->Ln(5);
    //Save ordinate
    $this->y0=$this->GetY();
	}
	
	else
	{	
	
	$getString = makeName($currentIssue);
	
    $this->SetFont('Helvetica','B',10);
    $w=$this->GetStringWidth($title)+6;
    $this->SetDrawColor(255,255,255);
    $this->SetFillColor(255,255,255);
    //$this->SetTextColor(220,50,50);
    $this->SetLineWidth(0);
	
	$this->SetX(10);//(210-$w)/2);
    $this->Cell(0,3,$title,1,0,'L',true);

    $this->SetX(160);//(210-$w)/2);
    $this->Cell(0,3,$getString.'  -  Page '.$this->PageNo(),1,1,'R',true);
	$this->Ln(4);    
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.1);
	$this->Line(10, 15, 200, 15);
	$this->Ln(10);
    //Save ordinate
    $this->y0=$this->GetY();
	}
}

function Footer()
{
    //Page footer
    /*$this->SetY(-15);
    $this->SetFont('Arial','I',8);
    $this->SetTextColor(128);
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');*/
}

function SetCol($col)
{
    //Set position at a given column
    $this->col=$col;
    $x=10+$col*100;
    $this->SetLeftMargin($x);
    $this->SetX($x);
}

function AcceptPageBreak()
{
    //Method accepting or not automatic page break
    if($this->col<1)
    {
        //Go to next column
        $this->SetCol($this->col+1);
        //Set ordinate to top
        $this->SetY($this->y0);
        //Keep on page
        return false;
    }
    else
    {
        //Go back to first column
        $this->SetCol(0);
        //Page break
        return true;
    }
}

function ChapterTitle($num,$label)
{
    //Title
    $this->SetFont('Arial','',12);
    $this->SetFillColor(200,220,255);
    $this->Cell(0,6,"Chapter $num : $label",0,1,'L',true);
    $this->Ln(4);
    //Save ordinate
    $this->y0=$this->GetY();
}

function ChapterBody($txt)
{
    //Read text file
    //$f=fopen($file,'r');
    //$txt=fread($f,filesize($file));
    //fclose($f);
    //Font
    //Output text in a 9 cm width column
    $this->MultiCell(90,4.2,$txt);
    //$this->Ln();
    //Mention
    //$this->SetFont('','I');
    //$this->Cell(0,5,'(end of excerpt)');
    //Go back to first column
    //$this->SetCol(0);
}

function PrintChapter($num,$title,$txt)
{
    //Add chapter
    //$this->AddPage();
    //$this->ChapterTitle($num,$title);
    $this->ChapterBody($txt);
}

function WriteHTML($html)
{
    //HTML parser
    $html=str_replace("\n",' ',$html);
    $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
    foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            //Text
            if($this->HREF)
                $this->PutLink($this->HREF,$e);
            else
                $this->Write(5,$e);
        }
        else
        {
            //Tag
            if($e[0]=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                //Extract attributes
                $a2=explode(' ',$e);
                $tag=strtoupper(array_shift($a2));
                $attr=array();
                foreach($a2 as $v)
                {
                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                        $attr[strtoupper($a3[1])]=$a3[2];
                }
                $this->OpenTag($tag,$attr);
            }
        }
    }
}

function OpenTag($tag,$attr)
{
    //Opening tag
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,true);
    if($tag=='A')
        $this->HREF=$attr['HREF'];
    if($tag=='BR')
        $this->Ln(5);
}

function CloseTag($tag)
{
    //Closing tag
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF='';
}

function SetStyle($tag,$enable)
{
    //Modify style and select corresponding font
    $this->$tag+=($enable ? 1 : -1);
    $style='';
    foreach(array('B','I','U') as $s)
    {
        if($this->$s>0)
            $style.=$s;
    }
    $this->SetFont('',$style);
}

function PutLink($URL,$txt)
{
    //Put a hyperlink
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
}
}

// Get (old) current issue
	
	$query_getID = "SELECT issue FROM publications WHERE onlyCol=1";
 	$result_getID = mysql_query($query_getID, $cxn)
     	  or die ("Couldn't execute query 1");

	$userIDr = mysql_fetch_assoc($result_getID);
	
	$currentIssue = $userIDr['issue'];
	
	$newIssue = $currentIssue + 1;
	
// Increment the issue, since this is a new issue
$query_saveData = "UPDATE publications SET 
						 issue='$newIssue'  
	                     WHERE onlyCol=1";
 	$result_saveData = mysql_query($query_saveData, $cxn)
     	  or die ("Couldn't execute query 1");


$pdf=new PDF();
$title='Mainely News';
$pdf->SetTitle($title);
$pdf->SetAuthor('Group');
$pdf->AddPage();

$pdf->SetFont('Times','',9);

// Birthdays and events ##################################################
$pdf->SetFont('Helvetica','',9);
$pdf->SetFont('','B');
//$pdf->PrintChapter(1,'','_________________________________________________');
//$pdf->Ln();
$pdf->Ln(3);
$pdf->PrintChapter(1,'','Announcements & Events');
$pdf->Ln(1);
$pdf->SetFont('Times','',9);
// Begin to format and print the paper
$query_getData = "SELECT userID, nameFull, event1, event2, event3 FROM userdata";
 	$result_getData = mysql_query($query_getData, $cxn)
     	  or die ("Couldn't execute query 1");
		  
// Print the paragraph updates
while($content = mysql_fetch_assoc($result_getData))
{
	if ($content['nameFull'] != '')
	{
		//$pdf->SetFont('','I');
		//$pdf->PrintChapter(2,'','... '.$content['nameFull']);
		$pdf->SetFont('','');
		if ($content['event1'] != '')
		{
			$pdf->PrintChapter(3,'',$content['event1'].' ... '.$content['nameFull']);
			//$pdf->Ln();
		}
		if ($content['event2'] != '')
		{
			$pdf->PrintChapter(3,'',$content['event2'].' ... '.$content['nameFull']);
			//$pdf->Ln();
		}
		if ($content['event3'] != '')
		{
			$pdf->PrintChapter(3,'',$content['event3'].' ... '.$content['nameFull']);
			//$pdf->Ln();
		}
		//$pdf->Ln();
	}
}

// Headlines ###################################################3


$query_getData = "SELECT userID, nameFull, newsHeadline, newsStory, misc2 FROM userdata";
 	$result_getData = mysql_query($query_getData, $cxn)
     	  or die ("Couldn't execute query 1");
	
while($content = mysql_fetch_assoc($result_getData))
{
	if ($content['nameFull'] != '' && $content['newsStory'] != '')
	{
		$pdf->SetFont('Helvetica','',9);
		$pdf->SetFont('','B');
		$pdf->PrintChapter(1,'','_________________________________________________');
		$pdf->Ln();
		
		$pdf->SetFont('','B');
		$pdf->PrintChapter(3,'',$content['newsHeadline']);
		$pdf->SetFont('','I');
		$pdf->SetFont('Times','I',9);

		$pdf->PrintChapter(2,'','by '.$content['nameFull']);
		$pdf->Ln();
		$pdf->SetFont('','');
		//display picture if it exists
		$imageName = 'userimages/'.$content['userID'].'.jpg';
		
		if (file_exists($imageName))
		{
		    $pdf->Image($imageName, null, null, 90);
			$pdf->Ln();
		}
		
		$order = "\\r"; //array("\\r\\n", "\\n", "\\r");
		$newReport = str_replace($order, ' ', $content['newsStory']);
		$newerReport = str_replace("\\n", '*', $newReport);
		$pdf->PrintChapter(2,'',stripslashes($newerReport));
		

	}
}

// recent news ##########################################3
$pdf->SetFont('Helvetica','',9);
$pdf->SetFont('','B');
$pdf->PrintChapter(1,'','_________________________________________________');
$pdf->Ln();
$pdf->PrintChapter(1,'','What we\'ve been up to');
$pdf->Ln();
$pdf->SetFont('Times','',9);
$query_getData = "SELECT * FROM userdata";
 	$result_getData = mysql_query($query_getData, $cxn)
     	  or die ("Couldn't execute query 1");
		  
// Print the paragraph updates
while($content = mysql_fetch_assoc($result_getData))
{
	if ($content['nameFull'] != '')
	{
		$pdf->SetFont('','I');
		$pdf->PrintChapter(2,'',$content['nameFull'].' says...');
		$pdf->SetFont('','');
		
		$order = "\\r"; //array("\\r\\n", "\\n", "\\r");
		$newReport = str_replace($order, ' ', $content['newsMonth']);
		$newerReport = str_replace("\\n", '*', $newReport);
		
		$pdf->PrintChapter(3,'',stripslashes($newerReport));
		$pdf->Ln();
	}
}


// Blog info ######################################################

//$pdf->Ln();
$pdf->SetFont('Helvetica','',9);
$pdf->SetFont('','B');
//$pdf->WriteHTML('__________________________________________________________________________________________________________');
$pdf->PrintChapter(1,'','_________________________________________________');
$pdf->Ln();
//$pdf->Ln();
$pdf->WriteHTML('Read more on our personal blogs');
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Times','',9);
$query_getData = "SELECT nameFull, blogName, blogURL, blogDescription FROM userdata";
 	$result_getData = mysql_query($query_getData, $cxn)
     	  or die ("Couldn't execute query 1");
// Write blog info
while($content = mysql_fetch_assoc($result_getData))
{
	if ($content['nameFull'] != '' && $content['blogName'] != '')
	{
		//$pdf->SetFont('','I');
		$pdf->SetFont('','');
		$pdf->WriteHTML('<i>'.$content['blogName'].'</i> - by '.$content['nameFull'].' - ');
		$pdf->SetFont('','');
		$pdf->WriteHTML('<a href='.$content['blogURL'].'>Visit blog</a><br />');
		$pdf->WriteHTML($content['blogDescription']);
		$pdf->Ln(8);
	}
}

// recommended reading ##################################################
$pdf->SetFont('Helvetica','',9);
$pdf->SetFont('','B');
$pdf->PrintChapter(1,'','_________________________________________________');
$pdf->Ln();
$pdf->PrintChapter(1,'','Recommended Books / Music');
$pdf->Ln();
$pdf->SetFont('Times','',9);
// Begin to format and print the paper
$query_getData = "SELECT nameFull, misc1 FROM userdata";
 	$result_getData = mysql_query($query_getData, $cxn)
     	  or die ("Couldn't execute query 1");
		  
// Print the paragraph updates
while($content = mysql_fetch_assoc($result_getData))
{
	if ($content['nameFull'] != '')
	{
		//$pdf->SetFont('','I');
		//$pdf->PrintChapter(2,'','... '.$content['nameFull']);
		$pdf->SetFont('','');
		if ($content['misc1'] != '')
		{
			$pdf->PrintChapter(3,'',$content['misc1'].' ...recommended by '.$content['nameFull']);
			//$pdf->Ln();
		}
	}
}


$pdf->Ln(10);

 // Get current issue
	
	$query_getID = "SELECT issue FROM publications WHERE onlyCol=1";
 	$result_getID = mysql_query($query_getID, $cxn)
     	  or die ("Couldn't execute query 1");

	$userIDr = mysql_fetch_assoc($result_getID);
	
	$currentIssue = $userIDr['issue'];

$pdf->Output('publications/'.$currentIssue.'.pdf', 'F');
?>