<?php require_once('database.php');
define('PDF_Minute', 1);

header('Content-Type: text/html; charset=utf-8'); 


function PDF_Api_Generator()
{
global $db;
$SQL = mysqli_query($db,"SELECT * FROM pdf_api WHERE statu = '0' ORDER BY balance ASC");

if(mysqli_num_rows($SQL)!=0)
{
    if($Data = mysqli_fetch_assoc($SQL))
    {
		if($Data['balance'] <=0) 
		{
		$SQL = mysqli_query($db,'UPDATE pdf_api SET statu = 1 WHERE pdf_id='.$Data['pdf_id']);
		if($SQL)
		{
		PDF_Api_Generator();
		}
		}
		else
		{
		//echo $Data['pdf_id'].'<br>';
		$SQL = mysqli_query($db,'UPDATE pdf_api SET balance = '.($Data["balance"]-1).' WHERE pdf_id='.$Data['pdf_id']);
		//echo $Data['balance'] -1;
		return $Data['apikey'];
		}
    }
}else{
  return 'NO RECORD';
}
}


function pdf_error()
{
	if(PDF_Api_Generator() == 'NO RECORD')
{
return false;
}
else{
return true;
}
}
date_default_timezone_set('Europe/Istanbul');

//Kullanıcı pdf kullandığında önce o pdf sisteme kayıt olacak 5 dakika boyunca o pdf görecek kullanıcı 5 dakikanın sonunda yeni pdf talep edebilecek.

function user_pdf_usage($user_id,$PDFType)
{
global $db;
$SQL = mysqli_query($db,"SELECT * FROM pdf_usage WHERE user_id = '".$user_id."' and pdf_type = '".$PDFType."'");

if(mysqli_num_rows($SQL)!=0)
{
    if($Data = mysqli_fetch_assoc($SQL))
    {
     $FirstDate = strtotime($Data['pdf_datetime']);
  	 $LastDate=strtotime("now");
  	 //echo date('Y-m-d H:i:s',$LastDate);
     $Difference = $LastDate - $FirstDate;
	if($Difference >= PDF_Minute*60)
	{
		return 'true';
	}
	else
	{
		return 'false';
	}
    } else { return 'true'; }
}else { return 'true';}
}

function user_save_pdf_usage($UserID,$PDFType,$URL)
{
	//Yeniden yapılacak bu.
global $db;
$SQL = mysqli_query($db,"SELECT * FROM pdf_usage WHERE user_id = '".$UserID."' and pdf_type = '".$PDFType."'"/* ORDER BY pdf_id ASC""*/);
if(mysqli_num_rows($SQL)!=0)
{
/*if($Data = mysqli_fetch_assoc($SQL))
{*/
$SQL = mysqli_query($db,"UPDATE `pdf_usage` SET `pdf_datetime`= '".date('Y-m-d H:i:s')."' WHERE user_id = '".$UserID."' and pdf_type = '".$PDFType."'");
if($SQL)
{
return true;
}
else
{
return false;
}

}
else
{
	$SQL = mysqli_query($db,"INSERT INTO `pdf_usage`(`user_id`, `pdf_url`, `pdf_type`) VALUES (".$UserID.",'".$URL."','".$PDFType."')");}
	if($SQL)
	{
	return true;
	}
	else
	{
	return false;
	}


//}//DATA
}

	if(user_pdf_usage('2','DietVartr') == 'true')
	{
//echo '5 DK geçmiş';
    if(user_save_pdf_usage('2','DietVartr','DietURL'))
    {
    	echo 'OK!';
    	//Yeni pdf indir
    }
	}
	else
	{
		echo 'yasaq var';
		//Sunucudakini göster
		//echo user_pdf_usage('2','DietVar');
	}


//user_pdf_usage('2');











function user_pdf_()
{

$ilksaat="1529929700";//bu ilk saatimiz

$sonsaat=strtotime(date('d.m.Y H:i:s'));//buda şu anki saat olsun

$fark=$sonsaat-$ilksaat;//sondan ilki çıkarıyom direk bize saniyeyi verecek

if($fark >= '300' && $fark <= '360')
{
	echo '5 Dakika'.'<br>';
		echo $fark;

}
else
{
	echo $fark;
}

	//	echo date("d.m.Y H:i:s", $ilksaatstr);
}

//Fark();
?>
