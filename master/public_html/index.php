<?php
error_reporting(E_ALL);

$page='';
$_path="/home/helpwithfriends/";
require_once $_path.'/smarty/libs/Smarty.class.php';

$smarty = new Smarty;
$smarty->force_compile = true;  //turn of for live site
#$smarty->debugging = true;
$smarty->setTemplateDir($_path.'/smarty/templates/');
$smarty->compile_dir = $_path.'/smarty/templates_c/';
$smarty->cache_dir = $_path.'/smarty/cache/';
$smarty->caching = true;
$smarty->cache_lifetime = 120;


/* get the url for parsing */
$_request_uri=$_SERVER['REQUEST_URI'];

/* strip off any parameters for parsing the exact page */
if(strstr($_request_uri,'?'))
	{
	list($uri,$toss)=explode('?',$_request_uri);
	}
else{$uri=$_request_uri;}

/* assign the domain and page variables */
list($domain,$_page)=explode('/',$uri);

/* since we aren't using any file extentions, get rid of any and if present, and reload the page */
if(strstr($_page,'.'))
	{
	list($page,$toss)=explode('.',$_page);
	}

if(!$page)
	{
	$page='index';
	}

/* show the result in the debug variable */
$debug['page']=$page;
$debug['POST']=$_POST;
$debug['COOKIES']=$_COOKIE;

/* if there is no template, load the 404 page */
if(!file_exists($_path."/smarty/templates/$page.tpl"))
	{
	$original_page=$uri;
	$page='404';
	}
	

/***
 *  because some pages load wp, and wp uses the $page variable, 
 * so we need to store it and reassign it after the page script loads
 * */
$_page=$page; 
/* now we can run the script for this page */
$show_debug=1;
if(file_exists("inc/$page.php"))
	{
	require_once("inc/$page.php");
	}
/* make sure the $page variable is correct. */
$page=$_page;
if(isset($_template))
	{
	$page=$_template;
	}
$debug['template']=$page;

/* assign the template name */
$template=$page.'.tpl';
/* assign other global variables */
if($show_debug)
	{
	$smarty->assign("debug", getPrintR($debug));
	}
else
	{
	$smarty->assign("debug", '');
	}
/* display the template */
$smarty->display($template);




function getPrintR($array)
	{
	//hold on to the output
	ob_start();
	print_r($array);
	//store the output in a string
	$out =ob_get_contents();
	//delete the output, because we only wanted it in the string
	ob_clean();
	return "<pre style=\"margin-top:0px\">$out</pre>";
	}