<?php
$content='';$querytext='';$_counter=0;$start='';$end='';

$smarty->assign("start", '');
$smarty->assign("end", '');

/*****************************
 * 
 * limit daily access to 10 requests per IP
 *   sell packets of requests like Domain Dossier
 * 
 * 
 * other sites: word.tips
 * 
 * 
 */

function getmicrotime(){ 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
    } 
srand(getmicrotime());
$pagestart=getmicrotime();

$alphabet='abcdefghijklmnopqrstuvwxyz';
$results_display='none';
$querystart='';$queryend='';$available='';$find='';$Results=[];

$querystart=getmicrotime();
if($_SERVER['SERVER_ADDR']=='169.254.199.220')
	{
	$_mysql = mysqli_connect("localhost", "dcarlton", "samuelmysql", "wwf");
	}
else
	{
	$_mysql = mysqli_connect("localhost", "helpwithfriends_user", "tu-Ukg[^fEsD", "helpwithfriends_enable");
	}
if(mysqli_errno($_mysql))
	{
	echo ": " . mysqli_error($_mysql) . "\n<hr>";
	}
$queryend=getmicrotime();

if(isset($_POST['find'])){$find=strtolower($_POST['find']);}
if(isset($_POST['available'])){$available=strtolower($_POST['available']);}
if(isset($_POST['start'])){$start=strtolower($_POST['start']);}
if(isset($_POST['end'])){$end=strtolower($_POST['end']);}
if(isset($_POST['range'])){$range=preg_replace("/[^0-9\-]/","",$_POST['range']);}else{$range='';}
$_range=$range;
if(strstr($range,'-'))
	{
	list($rb,$re)=explode('-',$range);
	for($i=$rb;$i<=$re;$i++){$Range[]=$i;}
	}
elseif($range)
	{
	$Range[0]=(int)$range;
	$rb=$range;$re=$range;
	}
else
	{
	$rb=2;
	$re=12;
	}

if($start or $end)
	{
	$_l=strlen($start);
	$_l=$_l+strlen($end);
	$rb=$rb-$_l;
	if($rb<0){$rb=0;}
	}

$_available=$available;$wc='';$_wc=0;
if(strstr($available,'?'))
	{
	$available=str_replace('?','',$available);
	$_l1=strlen($_available);
	$_l2=strlen($available);
	$_wc=$_l1-$_l2;
	$debug['lengths']="L1: $_l1, L2: $_l2, WC: $_wc";
	if($_wc>1){$_wc=2;$wc='..';$rb=$rb-2;}
	else{$wc='.';$rb=$rb-1;}
	if($rb<0){$rb=0;}
	
	}
$_c2=0;

$ip=$_SERVER['REMOTE_ADDR'];

$user_infox="<div class=\"col-md-12\" style=\"height:20px\"></div>
<div class=\"col-xs-12 \" style=\"margin-bottom:10px\"><a href=\"#\">Login</a> | <a href=\"#\">Account Info</a></div>
<div class=\"col-xs-3 col-md-3\">User:</div>
<div class=\"col-xs-9 col-md-9 text-left\">Anonymouse [$ip]</div>
<div class=\"col-xs-3 col-md-3\">balance:</div>
<div class=\"col-xs-9 col-md-9 text-left\">20 points</div>
<div class=\"col-xs-1 col-md-3\"></div>
<div class=\"col-xs-12 col-md-9 text-left\" style=\"font-size:10px;\">Anonymous users start each day with 20 points. Each query costs 1 point, wild card queries cost 2 points.</div>
<div class=\"col-md-12\" style=\"height:20px;clear:both\"></div>";
$user_info='';

if(isset($_POST['available']) and $_POST['available'])
	{
#	if($wc){decrimentCounter($ip,2);}
#	else{decrimentCounter($ip,1);}
	$Scores=getPoints($available,$start,$end);
	$debug['Scores']=$Scores;
	if($available){$available='['.$available.']';}
	$querytext=sprintf("SELECT * FROM `enable` WHERE `word` REGEXP '^%s%s%s{%s,%s}%s$'",
				mysqli_real_escape_string($_mysql, $start),
				mysqli_real_escape_string($_mysql, $available),
				mysqli_real_escape_string($_mysql, $wc),
				mysqli_real_escape_string($_mysql, $rb),
				mysqli_real_escape_string($_mysql, $re),
				mysqli_real_escape_string($_mysql, $end));
	$query=mysqli_query($_mysql, $querytext);
	if(mysqli_errno($_mysql)){echo ": " . mysqli_error($_mysql) . "\n<hr>$querytext";}
	while($row = mysqli_fetch_assoc($query))
		{
		$match=FALSE;
		$_counter++;
		$word=$row['word'];
		$len=strlen($word);
		$find=$start.$end;
		if(availableLetters($word,$_available,$find)){$Results[$len][]=$word;$_c2++;}
		}
	ksort($Results);
	$results_display='block';
	foreach($Results as $l=>$Len)
		{
		$content.="<div class=\"col-md-12 showcase-item\" style=\"margin-bottom:20px;clear:both;height:auto\"><h4 style=\"font-size:23px;clear:both\">Word Length: $l</h4>";
		foreach($Len as $word)
			{
			$score=wordScore($word);
			$content.="<div class=\"col-xs-4 col-md-2\" style=\"font-size:19px\">$word<sup style=\"font-size:11px\">$score</sup></div>\n";
			}
		$content.="<div style=\"clear:both;height:20px\"></div></div>";
		}
	
	if(!$content)
		{
		$content="
			<div class=\"col-md-12 showcase-item\" style=\"margin-bottom:20px;clear:both;height:auto\">
				<h4 style=\"font-size:23px;clear:both\">
					No Results Found
				</h4>
			</div>";
		}
	$debug['query']="$querytext ($_counter mysql results, $_c2 php results)<br>";
#	$content.=getPrintR($_POST);
#	$content.=getPrintR($Results);
	}




function availableLetters($word,$available,$find='')
	{
	global $debug;
	$available.=$find;
	$Avail=str_split($available);
	$_word=$word;
	$lettercount=0;
	foreach($Avail as $key=>$letter)
		{
		if($letter=='?')
			{
			$lettercount++;
			}
		else
			{
			$_word=preg_replace("/$letter/","",$_word,1);
			}
		}
	if(strlen($_word)==$lettercount)
		{
		return TRUE;
		}
	else
		{
		return FALSE;
		}
	}


function getPoints($alphabet)
	{
	global $_mysql,$debug;
	$Result=[];
	$available=$alphabet;
	$querytext=sprintf("SELECT * FROM `scores`",
				mysqli_real_escape_string($_mysql, $available));
	$query=mysqli_query($_mysql, $querytext);
	if(mysqli_errno($_mysql)){echo ": " . mysqli_error($_mysql) . "\n<hr>$querytext";}
	while($row = mysqli_fetch_assoc($query))
		{
		$letter=strtolower($row['letter']);
		$score=$row['score'];
		$Result[$letter]=$score;
		}
	return $Result;
	}

function wordScore($word)
	{
	global $Scores,$debug,$available,$start,$end;
	$available.=$start.$end;
	$score=0;$_debug='';
	$Word=str_split($word);
	foreach($Word as $letter)
		{
		if(strstr($available,$letter))
			{
			$score+=$Scores[$letter];
			}
		}
	return $score;
	}

function decrimentCounter($ip,$points)
	{
	global $_mysql;
	$querytext=sprintf("SELECT * FROM `access` WHERE `ip`='%s",
				mysqli_real_escape_string($_mysql, $ip));
	$query=mysqli_query($_mysql, $querytext);
	if(mysqli_errno($_mysql)){echo ": " . mysqli_error($_mysql) . "\n<hr>$querytext";}
	if(mysqli_num_rows($query))
		{
		#Update	
		}
	else
		{
		#Insert
		}
	return TRUE;
	}
#$debug['SERVER']=$_SERVER;
$show_debug=0;

$pageend=getmicrotime();
$speed=number_format($pageend-$pagestart,4);
$querycheck=number_format($queryend-$querystart,4);
	
$speedcheck = "Processed in $speed seconds. Mysql query took $querycheck seconds.";
if(!$show_debug){$speedcheck='';}
#$smarty->assign("content", $content);
$smarty->assign("title", 'home');
$smarty->assign("results", $content);
$smarty->assign("results_display", $results_display);
$smarty->assign("speedcheck", $speedcheck);
$smarty->assign("available", $_available);
$smarty->assign("find", $find);
$smarty->assign("range", $_range);
$smarty->assign("start", $start);
$smarty->assign("end", $end);
$smarty->assign("user_info", $user_info);

?>

