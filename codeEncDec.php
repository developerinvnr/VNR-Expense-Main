<?php
$strcode=array('','0','1','2','3','4','5','6','7','8','9','a','A','b','B','c','C','d','D','e','E','f','F','g','G','h','H','i','I','j','J','k','K','l','L','m','M','n','N','o','O','p','P','q','Q','r','R','s','S','t','T','u','U','v','V','w','W','x','X','y','Y','z','Z','#','@','$','%','^','&','*','_','!','?',' ');
//a function that will split a string into an array chunked with $num lenght.
function strsplt($thetext,$num=1)
{
$arr=array();
while ($i<strlen($thetext))
{
$y=substr($thetext,$i,$num);
if (isset($y))
{
$arr[]=$y;
}
$i=$i+$num;
}
return $arr;
}
//a function that will locate a key of an array
function key_locator($code,$strcode)
{
while (list($key, $val) = each($strcode)) {
    if ($val==$code)
    {
    $x=$key;
}
}
return $x;
}
//a function that will array the sum of key and the random number.
function add_random_key($thetext)
{
global $strcode;
$newcode=array();
$rnd=rand(1,intval(count($strcode))-2);
for ($i=0;$i<strlen($thetext);$i++)
{
$x=key_locator(substr($thetext,$i,1),$strcode);
$temp=$x+$rnd;
if($temp>intval(count($strcode)-1))
{
$temp=$temp-intval(count($strcode)-1);
}
$newcode[]=$temp;
$temp="";
}
$newcode[]=$rnd;
return $newcode;
}
//A function that will convert from key to value from an array
function convert_keyto_value($thetext)
{
global $strcode;
$a=add_random_key($thetext);
while (in_array(count($strcode)-1,$a)==true)
{
$a=add_random_key($thetext);
}
for ($i=0;$i<strlen($thetext)+1;$i++)
{
$output.=$strcode[$a[$i]];
}
return $output;
}
//A function that will remove the random value and back to original value
function derandomized($thetext)
{
global $strcode;;
$arr=strsplt($thetext,intval(strlen($thetext)-1));
for ($x=0;$x<strlen($thetext)-1;$x++)
{
$s=key_locator(substr($arr[0],$x,1),$strcode);
$t=key_locator($arr[1],$strcode);
$newcode=$s-$t;
if ($newcode<0)
{
$newcode=$newcode+intval(count($strcode)-1);
}
if ($newcode==0&&$s<>0)
{
$newcode=count($strcode)-1;
}
$output.=$strcode[$newcode];
}
return $output;
}
//A function that will randomly chunk a string then add them as one string.
function encrypt($thetext)
{
global $strcode;;
$nstr=strsplt($thetext,2);
for ($i=0;$i<count($nstr);$i++)
{
$output.=convert_keyto_value($nstr[$i]);
}
return $output;
}
//A function that will remove the random values from chunked string and regrouped
//them as original string.
function decrypt($thetext)
{
global $strcode;;
$nstr=strsplt($thetext,3);
for ($i=0;$i<count($nstr);$i++)
{
$output.=derandomized($nstr[$i]);
}
return $output;
}
?>