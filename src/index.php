<?php
include_once("script.php");
/*
# 적용되어 있는 함수 목록입니다.
xss 필터
상단 부분
BB코드 자동 닫기
BB코드 적용
페이지 목록
IE에서의 글작성
검색
최신 BBS 불러오기
신규 스레드 작성 페이지
신규 스레드 등록
*/
if($_GET['ajax_load'] != 1){
?>
<div id="header">
	<div style="position:absolute;  text-align:left;">
		<form method="get">
		<a href="index.php"><img src="img/top_logo.png" border=0></a>
			<fieldset class="srch" style="border:0;">
				<legend>검색영역</legend>
				<select name="serch_option">
						<? if($_GET['serch_option'] == "title"){
							echo "
							<option selected value=\"title\" title=\"제목으로 검색합니다.\">제목</option>
							<option value=\"content\" title=\"내용으로 검색합니다.\">내용</option>
							<option value=\"comment\" title=\"스레드 댓글로 검색합니다.\">댓글</option>";
						}elseif($_GET['serch_option'] == "cotent"){
							echo "
							<option  value=\"title\" title=\"제목으로 검색합니다.\">제목</option>
							<option selected value=\"content\" title=\"내용으로 검색합니다.\">내용</option>
							<option value=\"comment\" title=\"스레드 댓글로 검색합니다.\">댓글</option>";
						}elseif($_GET['serch_option'] == "comment"){
							echo "
							<option  value=\"title\" title=\"제목으로 검색합니다.\">제목</option>
							<option value=\"content\" title=\"내용으로 검색합니다.\">내용</option>
							<option selected value=\"comment\" title=\"스레드 댓글로 검색합니다.\">댓글</option>";
						}else{
							echo "
							<option selected value=\"title\" title=\"제목으로 검색합니다.\">제목</option>
							<option value=\"content\" title=\"내용으로 검색합니다.\">내용</option>
							<option value=\"comment\" title=\"스레드 댓글로 검색합니다.\">댓글</option>";
						}
						?>
				</select>
				<input type="text" accesskey="s" name="serch" value="<? echo $_GET['serch']; ?>" class="keyword">
				<input type="image" alt="검색" src="img/btn_srch.gif">
			</fieldset>
		</form>
	</div>
	<span id="new_thread"><a href='./index.php?active=1' title="새로운 스레드를 개설할 수 있습니다." style="text-decoration:none; color:#ffffff;">스레드 개설하기</a></span>
</div>
<?
}
?>
<?
if($_GET['ajax_load'] != 1){
?>
<div id="content">
<? } ?>
<?
function xss_filter($content){ //xss 필터
	 $content = preg_replace('/(<)(|\/)(\!|\?|html|head|title|meta|body|script|style|base|noscript|
	  form|input|select|option|optgroup|textarea|button|label|fieldset|legend|iframe|embed|object|param|
	  frameset|frame|noframes|basefont|applet| isindex|xmp|plaintext|listing|bgsound|marquee|blink|
	  noembed|comment|xml)/i', '&lt;$3', $content);
	 
	 $content = preg_replace_callback("/([^a-z])(o)(n)/i", 
	  create_function('$matches', 'if($matches[2]=="o") $matches[2] = "&#111;";
	  else $matches[2] = "&#79;"; return $matches[1].$matches[2].$matches[3];'), $content);
 
	return $content;
}
?>
<?
function close_bbcode($msg_content){ // BB코드의 태그 닫기가 안된 경우 태그를 자동으로 닫아줍니다.
	while(substr_count($msg_content, "[img]") > substr_count($msg_content, "[/img]")){
		$msg_content = "".$msg_content."[/img]";
	}
	while(substr_count($msg_content, "[b]") > substr_count($msg_content, "[/b]")){
		$msg_content = "".$msg_content."[/b]";
	}
	while(substr_count($msg_content, "[red]") > substr_count($msg_content, "[/red]")){
		$msg_content = "".$msg_content."[/red]";
	}
	while(substr_count($msg_content, "[blue]") > substr_count($msg_content, "[/blue]")){
		$msg_content = "".$msg_content."[/blue]";
	}
	while(substr_count($msg_content, "[green]") > substr_count($msg_content, "[/green]")){
		$msg_content = "".$msg_content."[/green]";
	}
	while(substr_count($msg_content, "[u]") > substr_count($msg_content, "[/u]")){
		$msg_content = "".$msg_content."[/u]";
	}
	while(substr_count($msg_content, "[i]") > substr_count($msg_content, "[/i]")){
		$msg_content = "".$msg_content."[/i]";
	}
	while(substr_count($msg_content, "[sup]") > substr_count($msg_content, "[/sup]")){
		$msg_content = "".$msg_content."[/sup]";
	}
	while(substr_count($msg_content, "[sub]") > substr_count($msg_content, "[/sub]")){
		$msg_content = "".$msg_content."[/sub]";
	}
	while(substr_count($msg_content, "[overline]") > substr_count($msg_content, "[/overline]")){
		$msg_content = "".$msg_content."[/overline]";
	}
	while(substr_count($msg_content, "[s]") > substr_count($msg_content, "[/s]")){
		$msg_content = "".$msg_content."[/s]";
	}
	while(substr_count($msg_content, "[cite]") > substr_count($msg_content, "[/cite]")){
		$msg_content = "".$msg_content."[/cite]";
	}
	
	return $msg_content;
}

function bbcode($msg_content){ // BBCODE 적용
	$msg_content = close_bbcode($msg_content); //BBCODE 닫기 필터
	$msg_content = xss_filter($msg_content); // XSS 필터
	
	$msg_content = str_replace("[img]","<img src=\"", $msg_content);
	$msg_content = str_replace("[/img]","\" border=0>", $msg_content);

	$msg_content = str_replace("[b]","<b>", $msg_content);
	$msg_content = str_replace("[/b]","</b>", $msg_content);

	$msg_content = str_replace("[red]","<font color=\"red\">", $msg_content);
	$msg_content = str_replace("[/red]","</font>", $msg_content);
	
	$msg_content = str_replace("[blue]","<font color=\"blue\">", $msg_content);
	$msg_content = str_replace("[/blue]","</font>", $msg_content);
	
	$msg_content = str_replace("[green]","<font color=\"green\">", $msg_content);
	$msg_content = str_replace("[/green]","</font>", $msg_content);

	$msg_content = str_replace("[u]","<u>", $msg_content);
	$msg_content = str_replace("[/u]","</u>", $msg_content);
	
	$msg_content = str_replace("[i]","<i>", $msg_content);
	$msg_content = str_replace("[/i]","</i>", $msg_content);
	
	$msg_content = str_replace("[sup]","<sup>", $msg_content);
	$msg_content = str_replace("[/sup]","</sup>", $msg_content);
	
	$msg_content = str_replace("[sub]","<sub>", $msg_content);
	$msg_content = str_replace("[/sub]","</sub>", $msg_content);
	
	$msg_content = str_replace("[overline]","<span style=\"text-decoration:overline;\">", $msg_content);
	$msg_content = str_replace("[/overline]","</span>", $msg_content);
	
	$msg_content = str_replace("[s]","<strike>", $msg_content);
	$msg_content = str_replace("[/s]","</strike>", $msg_content);
	
	$msg_content = str_replace("[cite]","<cite>", $msg_content);
	$msg_content = str_replace("[/cite]","</cite>", $msg_content);
	
	return $msg_content;
}

function page($page, $max){ // 페이지 목록
global $dir;
	$s1 = floor($page / 10) * 100;
	$max = floor(($max - 1) / 100);
	while($s1 <= $max){
		$s1++;
		$s2 = $s1 - 1;
		if($s1 == $page+1){
			echo "<a href='http://".$_SERVER['HTTP_HOST']."/".$dir."index.php?bbs=".$_GET['bbs']."&page=".$s2."' style=\"text-decoration:none;\"><b>".$s1."</b></a>";
		}else{
			echo "<a href='http://".$_SERVER['HTTP_HOST']."/".$dir."index.php?bbs=".$_GET['bbs']."&page=".$s2."' style=\"text-decoration:none;\">".$s1."</a>";
		}
	}
}
	
function ie($num, $name, $text){ // IE에서의 글 작성
global $dir;
	function compress_html($html){
		$html = htmlspecialchars($html, ENT_QUOTES, 'UTF-8');
		$html = str_replace("\n",'<br>', $html);
		return $html;
	}
	if($name == NULL || $name == ""){
		$name = "이름없음";
	}else{
		$name = compress_html($name);
	}

	$text = compress_html($text);

	mysql_query("INSERT INTO `comment` (`bbs`,`time`,`writer`,`content`,`ip`) VALUES ('".$num."','".date("Y/m/d H:i:s")."','".$name."','".$text."','".$_SERVER['REMOTE_ADDR']."')") or die(mysql_error()); // 댓글 작성
	mysql_query("update bbs set time_s = '".time()."' where no = '".$num."'") or die(mysql_error()); // 스레드 갱신
	
	echo "<p style=\"font-size:15pt; font-weight:bold;\">글이 작성되었습니다.</p>";
	echo "<div id=\"write_button\">";
	
	echo "<div style=\"margin-top:20px; margin-bottom:20px;\"><a id=\"more_thread\" href='index.php' style=\"text-decoration:none;\">돌아가기</a></div>";

	echo "</div>";
}

 function serch($item, $serch_option){ // 검색
 Global $mobile_on;
 global $dir;
 
	if($serch_option == "title"){
		$bbslist = mysql_query("SELECT * FROM  `bbs` WHERE  `subject` LIKE  '%".$item."%'");
	}elseif($serch_option == "content"){
		$bbslist = mysql_query("SELECT * FROM  `bbs` WHERE  `content` LIKE  '%".$item."%'");
	}elseif($serch_option == "comment"){
		$comlist = mysql_query("SELECT * FROM  `comment` WHERE  `content` LIKE  '%".$item."%'");
	}
	if($serch_option == "title" || $serch_option == "content"){
		while($bbsdata = mysql_fetch_array($bbslist)){
			$comment_list = mysql_query("SELECT * FROM  `comment` WHERE  `bbs` =".$bbsdata['no']." ORDER BY  `no` DESC");
			$comment_num = mysql_num_rows($comment_list) + 1;
			$bbsdata['subject'] = mb_substr($bbsdata['subject'],0,30,'utf-8');
			$msg = "<a href='http://".$_SERVER['HTTP_HOST']."/".$dir."index.php?bbs=".$bbsdata['no']."' style=\"text-decoration:none;\"><b><font color=\"red\" size=\"5\">".$bbsdata['subject']."</font></b></a> 글번호:".$bbsdata['no']." <b>(".$comment_num.")</b>";
			$msg .= "<p style=\"font-size:11pt;\"><span style=\"font-size:12pt; font-weight:bold;\">".$bbsdata['writer']."</span><br>";
			$msg_content = "".$bbsdata['content']."</p><p style=\"font-size:11px; margin-bottom:0px; color:gray;\">".$bbsdata['time']." l <b>ID:".mb_substr(md5("".$bbsdata['no']."".$bbsdata['ip'].""),0,12,'utf-8')."</b></p>";
			// BBCODE 적용
			$msg_content = bbcode($msg_content);
			$msg .= $msg_content;
			
			$no = 2;
			while($comment_data = mysql_fetch_array($comment_list)){
				if($no >= 7){
					break;
				}else{
					$color = $no % 2;
					
					if($color == 0){
						$msg .= "<div id=\"comment_box\" style=\"background-color:#f2f2f2;margin-top:15px; border:1px solid #ddd; padding:10px;\">";
					}else{
						$msg .= "<div id=\"comment_box\" style=\"margin-top:15px; border:1px solid #ddd; padding:10px;\">";
					}
					$msg .= "<span style=\"font-size:12pt; font-weight:bold;\">".$comment_data['writer']."</span><br>";
					$msg_content = "<span style=\"padding-left:15px;margin-top:0px;\">".$comment_data['content']."</span>";	
					
					// BBCODE 적용
					$msg_content = bbcode($msg_content);
					$msg .= $msg_content;
					
					$msg .= "<p style=\"font-size:11px; margin-bottom:0px; color:gray;\">".$comment_data['time']." l <b>ID:".mb_substr(md5("".$bbsdata['no']."".$comment_data['ip'].""),0,12,'utf-8')."</b></p></div>";
					$no++;
				}
			}
			
			echo $msg;
			
			echo "<div style=\"margin-top:20px; margin-bottom:20px;\"><a id=\"more_thread\" href='http://".$_SERVER['HTTP_HOST']."/".$dir."index.php?bbs=".$bbsdata['no']."' style=\"text-decoration:none;\">이 스레드 더 보기</a></div>";

			?>
			<hr noshade>
			<?
		}
	}elseif($serch_option == "comment"){
		while($comdata = mysql_fetch_array($comlist)){
			$bbslist = mysql_query("SELECT * FROM  `bbs` WHERE  `no` =".$comdata['bbs']."");
			$bbsdata = mysql_fetch_array($bbslist);
			
			$comment_list = mysql_query("SELECT * FROM  `comment` WHERE  `bbs` =".$comdata['bbs']." ORDER BY  `no` DESC");
			$comment_num = mysql_num_rows($comment_list) + 1;
			$bbsdata['subject'] = mb_substr($bbsdata['subject'],0,30,'utf-8');
			$msg = "<a href='http://".$_SERVER['HTTP_HOST']."/".$dir."index.php?bbs=".$bbsdata['no']."' style=\"text-decoration:none;\"><b><font color=\"red\" size=\"5\">".$bbsdata['subject']."</font></b></a> 글번호:".$bbsdata['no']." <b>(".$comment_num.")</b><br>";
			$msg .= "<div id=\"comment_box\" style=\"border:1px solid #ddd; padding:10px;\">";
			$msg .= "<span style=\"font-size:12pt; font-weight:bold;\">".$comdata['writer']."</span><br>";
			$msg_content = "<span style=\"padding-left:15px;margin-top:0px; font-size:11pt;\">".$comdata['content']."</span>";
			// BBCODE 적용
			$msg_content = bbcode($msg_content);
			$msg .= $msg_content;
			$msg .= "<p style=\"font-size:11px; margin-bottom:0px; color:gray;\">".$comdata['time']." l  <b>ID:".mb_substr(md5("".$bbsdata['no']."".$comdata['ip'].""),0,12,'utf-8')."</b></p></div>"; 
			
			echo $msg;
			
			echo "<div style=\"margin-top:20px; margin-bottom:20px;\"><a id=\"more_thread\" href='http://".$_SERVER['HTTP_HOST']."/".$dir."index.php?bbs=".$bbsdata['no']."' style=\"text-decoration:none;\">이 스레드 더 보기</a></div>";

			?>
			<hr noshade>
			<?
		}
	}
 }
 function call_bbs_min(){ // 최신 BBS 제목만 불러오기
global $mobile_on;
global $dir;
$time = time() - 600;
?>
<div style="height:80px; font-size:9pt; overflow:auto; overflow-x:hidden; overflow-y:auto; padding:5px; border:2px solid #ddd; margin-bottom:5px;">
<?
	$bbslist = mysql_query("SELECT * FROM  `bbs` WHERE  `time_s` >=".$time."");
	while($bbsdata = mysql_fetch_array($bbslist)){
		$comment_list = mysql_query("SELECT * FROM  `comment` WHERE  `bbs` =".$bbsdata['no']." ORDER BY  `no` DESC");
		$comment_num = mysql_num_rows($comment_list) + 1;
		$bbsdata['subject'] = mb_substr($bbsdata['subject'],0,30,'utf-8');
		
		$msg = "<a href='http://".$_SERVER['HTTP_HOST']."/".$dir."index.php?bbs=".$bbsdata['no']."' style=\"text-decoration:none;\">".$bbsdata['no'].":".$bbsdata['subject']."(".$comment_num.")</a> ";
		
		// BBCODE 적용
		$msg_content = bbcode($msg_content);
		$msg .= $msg_content;

		echo $msg;
	}
?>
</div>
<?
}

function call_bbs(){ // 최신 BBS 불러오기
global $mobile_on;
global $dir;
	echo "최근에 갱신된 스레드 10개만 보여줍니다.<hr noshade>";
	$bbslist = mysql_query("SELECT * FROM  `bbs` ORDER BY  `time_s` DESC LIMIT 0 , 10");
	while($bbsdata = mysql_fetch_array($bbslist)){
		$comment_list = mysql_query("SELECT * FROM  `comment` WHERE  `bbs` =".$bbsdata['no']." ORDER BY  `no` DESC");
		$comment_num = mysql_num_rows($comment_list) + 1;
		$com_s = $comment_num - 6;
		if($com_s < 0){
			$com_s = 0;
		}
		$bbsdata['subject'] = mb_substr($bbsdata['subject'],0,30,'utf-8');
		$comment_list = mysql_query("SELECT * FROM  `comment` WHERE  `bbs` =".$bbsdata['no']." ORDER BY  `no` ASC LIMIT ".$com_s." , 5");
		$msg = "<a href='http://".$_SERVER['HTTP_HOST']."/".$dir."index.php?bbs=".$bbsdata['no']."' style=\"text-decoration:none;\"><b><font color=\"red\" size=\"5\">".$bbsdata['subject']."</font></b></a> 글번호:".$bbsdata['no']." <b>(".$comment_num.")</b>";
		$msg .= "<p style=\"font-size:11pt;\"><span style=\"font-size:12pt; font-weight:bold;\">".$bbsdata['writer']."</span><br>";
		$bbsdata['content'] = bbcode($bbsdata['content']);
		$msg_content = "".$bbsdata['content']."</p><p style=\"font-size:11px; margin-bottom:0px; color:gray;\">".$bbsdata['time']." l <b>ID:".mb_substr(md5("".$bbsdata['no']."".$bbsdata['ip'].""),0,12,'utf-8')."</b></p>";
		// BBCODE 적용
		$msg_content = bbcode($msg_content);
		$msg .= $msg_content;

		$no = 2;
		while($comment_data = mysql_fetch_array($comment_list)){
			if($no >= 7){
				break;
			}else{
				$color = $no % 2;
				
				if($color == 0){
					$msg .= "<div id=\"comment_box\" style=\"background-color:#f2f2f2;margin-top:15px; border:1px solid #ddd; padding:10px;\">";
				}else{
					$msg .= "<div id=\"comment_box\" style=\"margin-top:15px; border:1px solid #ddd; padding:10px;\">";
				}
				$msg .= "<span style=\"font-size:12pt; font-weight:bold;\">".$comment_data['writer']."</span><br>";
				$msg_content = "<span style=\"padding-left:15px;margin-top:0px;\">".$comment_data['content']."</span>";	
				
				// BBCODE 적용
				$msg_content = bbcode($msg_content);
				$msg .= $msg_content;
				
				$msg .= "<p style=\"font-size:11px; margin-bottom:0px; color:gray;\">".$comment_data['time']." l <b>ID:".mb_substr(md5("".$bbsdata['no']."".$bbsdata['ip'].""),0,12,'utf-8')."</b></p></div>";
				$no++;
			}
		}
		
		echo $msg;
		
			echo "<div style=\"margin-top:20px; margin-bottom:20px;\"><a id=\"more_thread\" href='http://".$_SERVER['HTTP_HOST']."/".$dir."index.php?bbs=".$bbsdata['no']."' style=\"text-decoration:none;\">이 스레드 더 보기</a></div>";

			?>
		<hr noshade>
		<?
	}
}

function new_t_active(){ // 신규 스레드 작성 페이지
?>
<p style="font-size:15pt; font-weight:bold;">신규스레드를 작성합니다.</p>
<form action="index.php?active=2" method= "post">
<div style="width:444px;">
	<div>
	이름 : <input id="name" name="name" title="원하는 이름을 입력해주세요. 미입력시 익명으로 처리됩니다." type="text" maxlength="6"><br>
	제목 : <input id="subject" name="subject" type="text"><br>
	<textarea id="text" name="text" cols="60" rows="10" style="resize: none;"></textarea><br>
	</div>
	<div style="text-align:right;">
		<span class="write_button"><input type="submit" title="글을 작성한다는 것은 익명 게시판의 수칙에 동의하는 것으로 간주합니다."value="스레드 작성"></span>
	</div>
</div>
</form>
<?
}

function inject_derictory($new_name,$subject,$maker,$index){ // 신규 스레드 등록
	if($subject != NULL && $index != NULL){
		$subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
		$subject = str_replace("\n",'<br>', $subject);
		
		$no = mysql_query("SELECT * FROM  `bbs` ");
		$no = mysql_num_rows($no)+1;
		
		if($maker == NULL || $maker == ""){
			$maker = "이름없음";
		}else{
			$maker = htmlspecialchars($maker, ENT_QUOTES, 'UTF-8');
			$maker = str_replace("\n",'<br>', $maker);
		}

		$index = htmlspecialchars($index, ENT_QUOTES, 'UTF-8');
		$index = str_replace("\n",'<br>', $index);
		mysql_query("INSERT INTO `bbs` (`time`,`time_s`,`subject`,`writer`,`content`,`ip`) VALUES ('".date("Y/m/d H:i:s")."','".time()."','".$subject."','".$maker."','".$index."','".$_SERVER['REMOTE_ADDR']."')") or die(mysql_error());
		echo "신규 스레드가 등록되었습니다. <a href='./index.php'>돌아가기</a>";
	}else{
		echo "정보를 빠짐없이 입력해주세요.";
	}
}

if($_GET['active'] == NULL && $_GET['bbs'] == NULL && $_GET['serch'] == NULL){
	call_bbs_min();
	call_bbs();
?>
	<div id="test"></div>
<?
}elseif($_GET['active'] == 1){
	new_t_active();
}elseif($_GET['active'] == 2){
	$randing_bbs_num = md5(time());
	inject_derictory($randing_bbs_num,$_POST['subject'],$_POST['name'],$_POST['text']);
}elseif($_GET['active'] == 3){
	ie($_POST['bbs_num'], $_POST['comment_name'], $_POST['comment_text']);
}elseif($_GET['serch'] != NULL){
	$serch_item = htmlspecialchars($_GET['serch'], ENT_QUOTES, 'UTF-8');
	echo "<b>\"".$serch_item."\"</b> 으로 검색한 결과입니다.<hr noshade>";
	serch($serch_item, $_GET['serch_option']);
}elseif($_GET['bbs'] != NULL){ // 글읽기
	$bbslist = mysql_query("SELECT * FROM  `bbs` WHERE  `no` =".$_GET['bbs']."");
	$bbsdata = mysql_fetch_array($bbslist);
	$bbsdata['subject'] = mb_substr($bbsdata['subject'],0,30,'utf-8');
	$title = "<a href='http://".$_SERVER['HTTP_HOST']."/".$dir."index.php?bbs=".$bbsdata['no']."' style=\"text-decoration:none;\"><b><font color=\"red\" size=\"5\">".$bbsdata['subject']."</font></b></a> 글번호:".$bbsdata['no']."";
	$msg = "<p style=\"font-size:11pt;\"><span style=\"font-size:12pt; font-weight:bold;\">".$bbsdata['writer']."</span><br>";
	$bbsdata['content'] = bbcode($bbsdata['content']);
	$msg .= "".$bbsdata['content']."</p><p style=\"font-size:11px; margin-bottom:0px; color:gray;\">".$bbsdata['time']." l <b>ID:".mb_substr(md5("".$_GET['bbs']."".$bbsdata['ip'].""),0,12,'utf-8')."</b></p>";
	
	$bbslist = mysql_query("SELECT * FROM  `comment` WHERE  `bbs` =".$_GET['bbs']." ORDER BY  `no` ASC");
	$msg_num = mysql_num_rows($bbslist) + 1;
	
	if($_GET['page'] == NULL){
		$_GET['page'] = 0;
	}
	$_GET['page'] = $_GET['page'] * 100;
	$bbslist = mysql_query("SELECT * FROM  `comment` WHERE  `bbs` =".$_GET['bbs']." ORDER BY  `no` ASC LIMIT ".$_GET['page']." , 100");
	$no = $_GET['page'] * 100 + 2;
	while($bbsdata = mysql_fetch_array($bbslist)){
		$color = $no % 2;
		
		if($color == 0){
			$msg .= "<div id=\"comment_box\" style=\"background-color:#f2f2f2;margin-top:15px; border:1px solid #ddd; padding:10px;\">";
		}else{
			$msg .= "<div id=\"comment_box\" style=\"margin-top:15px; border:1px solid #ddd; padding:10px;\">";
		}
		
		$msg .= "<span style=\"font-size:12pt; font-weight:bold;\">".$bbsdata['writer']."</span><br>";
		$msg_content = "<span style=\"padding-left:15px;margin-top:0px;font-size:11pt;\">".$bbsdata['content']."</span>";
		
		// BBCODE 적용
		$msg_content = bbcode($msg_content);
		
		$msg .= $msg_content;
		
		$msg .= "<p style=\"font-size:11px; margin-bottom:0px; color:gray;\">".$bbsdata['time']." l <b>ID:".mb_substr(md5("".$_GET['bbs']."".$bbsdata['ip'].""),0,12,'utf-8')."</b></p></div>";
		?>
		<?
		$no++;
	}
	$title = "".$title." <b>(".$msg_num.")</b><br>스레드 주소 : <a title=\"해당 주소로 접근시 이 스레드로 이동됩니다.\" href='http://".$_SERVER['HTTP_HOST']."/".$dir."index.php?bbs=".$_GET['bbs']."' style=\"text-decoration:none;\">http://".$_SERVER['HTTP_HOST']."/".$dir."index.php?bbs=".$_GET['bbs']."</a><br><hr noshade>";
	$msg = "".$title."".$msg."";
	
	echo $msg;
	
?>
<div id="page" style="text-decoration:none; font-size:18pt;">
<?
	page($_GET['page'], $msg_num); // 페이지 표시
?>
</div>
	<hr noshade>
	<div id ="incomment">
<?
	$userAgent = $_SERVER['HTTP_USER_AGENT']; // 브라우저 체크

	if(preg_match('/MSIE 6/i', $userAgent) || preg_match('/MSIE 7/i', $userAgent) || preg_match('/MSIE 8/i', $userAgent) || preg_match('/ipod/i', $userAgent) || preg_match('/Mobile/i', $userAgent) || preg_match('/IEMobile/i', $userAgent) || preg_match('/gtelecom/i', $userAgent) || $ajax_comment == false || $mobile_on == 1){
?>
<form action="index.php?active=3" method= "post">
<?
if($ajax_comment == true && $mobile_on != 1){
?>
<span style="font-size:8pt;">IE9이하에서 접속중입니다. 크롬, 파이어폭스 등 최신브라우저에서 접속하시는 것을 추천합니다.</span><br>
<?
}
?>
	<input name="bbs_num" type=hidden value="<? echo $_GET['bbs']; ?>">
	이름 : <input name="comment_name" title="입력하지 않으면 익명 처리 됩니다." type="text" maxlength="12"> 내용 :<br>
	<textarea name="comment_text" cols="50" rows="5"></textarea>
	<span class="write_button"><input type="submit" value="작성"></span>
</form>
	</div>
	<hr />
<?
		echo "<div style=\"margin-top:20px; margin-bottom:20px;\"><a id=\"more_thread\" href='index.php' style=\"text-decoration:none;\">돌아가기</a></div>";
?>
<?
	}else{
?>
	<input id="ajax_no" type=hidden value="<? echo $_GET['bbs']; ?>">
	이름 : <input id="ajax_name" title="입력하지 않으면 익명 처리 됩니다." type="text" maxlength="12"> 내용 :<br>
	<textarea id="ajax_text" cols="50" rows="5"></textarea>
	<button class="comment_but" id="<?echo $line;?>">작성</button>
	</div>
	<hr />
<?
		echo "<div style=\"margin-top:20px; margin-bottom:20px;\"><a id=\"more_thread\" href='index.php' style=\"text-decoration:none;\">돌아가기</a></div>";
?>
<?
	}
//여기까지
}
if($_GET['ajax_load'] != 1){
?>
</div>
<b>GTBBS Edition W</b> Copyright (C) 2013 <a href="http://erio_.blog.me" style="text-decoration:none;" target="_blenk">W</a> [<a href="http://www.gnu.org/licenses" style="text-decoration:none;" target="_blenk">GPL v3</a>]
</div>
</body>
</html>
</div>
<? } ?>