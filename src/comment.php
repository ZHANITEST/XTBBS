<?php
include("script.php");
$fname = $_POST['ajax_name'];
$ftext = $_POST['ajax_text'];
function compress_html($html){
	$html = htmlspecialchars($html, ENT_QUOTES, 'UTF-8');
	$html = str_replace("\n",'<br>', $html);
	return $html;
}

if($_POST['ajax_name'] == NULL || $_POST['ajax_name'] == ""){
	$_POST['ajax_name'] = "이름없음";
}else{
	$_POST['ajax_name'] = compress_html($_POST['ajax_name']);
}

$_POST['ajax_text'] = compress_html($_POST['ajax_text']);
if($_POST['ajax_time'] != NULL){
	$time_check = $_POST['ajax_time'] + 5;
	$time_check2 = $_POST['ajax_time'] - 5;
	if(time() <= $time_check || time() <= $time_check2){
		mysql_query("INSERT INTO `comment` (`bbs`,`time`,`time_s`,`writer`,`content`,`ip`) VALUES ('".$_POST['ajax_no']."','".date("Y/m/d H:i:s")."','".time()."','".$_POST['ajax_name']."','".$_POST['ajax_text']."','".$_SERVER['REMOTE_ADDR']."')") or die(mysql_error()); // 댓글 작성
		mysql_query("update bbs set time_s = '".time()."' where no = '".$_POST['ajax_no']."'") or die(mysql_error()); // 스레드 갱신
	}elseif(time() > $time_check){
		mysql_query("INSERT INTO `ban` (`time`,`time_s`,`ip`,`why`) VALUES ('".date("Y/m/d H:i:s")."','".time()."','".$_SERVER['REMOTE_ADDR']."','도배 행위 ".$_POST['ajax_time']."')") or die(mysql_error()); // 아이피 밴
	}
}else{
	$check = mysql_query("SELECT * FROM  `ban` WHERE  `ip` ='".$_SERVER['REMOTE_ADDR']."'") or die(mysql_error());
	$row = mysql_num_rows($check) or die(mysql_error());
	if($row == 0){ // 블랙리스트 확인
		$time_check = time()+5;
		$check = mysql_query("SELECT * FROM  `comment` WHERE  `time_s` >=".$time_check." AND  `ip` =  '".$_SERVER['REMOTE_ADDR']."'"); // 5초 내에 등록한 글이 있는지 확인
		$row = mysql_num_rows($check) or die(mysql_error());
		if($row == 0){
			mysql_query("INSERT INTO `comment` (`bbs`,`time`,`time_s`,`writer`,`content`,`ip`) VALUES ('".$_POST['ajax_no']."','".date("Y/m/d H:i:s")."','".time()."','".$_POST['ajax_name']."','".$_POST['ajax_text']."','".$_SERVER['REMOTE_ADDR']."')") or die(mysql_error()); // 댓글 작성
			mysql_query("update bbs set time_s = '".time()."' where no = '".$_POST['ajax_no']."'") or die(mysql_error()); // 스레드 갱신
		}elseif($row >= 3){
			mysql_query("INSERT INTO `ban` (`time`,`time_s`,`ip`,`why`) VALUES ('".date("Y/m/d H:i:s")."','".time()."','".$_SERVER['REMOTE_ADDR']."','도배 행위')") or die(mysql_error()); // 아이피 밴
		}
	}
}

echo "<div id=content_cm></div>";

?>