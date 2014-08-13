<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="kr" lang="kr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="format-detection" content="telephone=no" />
<title>GTBBS Edition W 설치</title>
</head>
<style>
	body{
	  font-family:'나눔고딕',nanumgothic,'맑은 고딕',malgun,'돋움',dotum;
	}
	
	#header{
	width:100%;
	height:65px;
	border-bottom:10px solid #ddd;
	padding-bottom:5px;
	margin-bottom:12px;
	}
	
	.write_button INPUT[type=submit]{
	  color:#484848;
	  font-family:'나눔고딕',nanumgothic,'맑은 고딕',malgun,'돋움',dotum;
	  font-weight : bold;
	  font-size:9pt;
	  border:3px solid #484848;
	  width:80px;
	  height:30px;
	}
 
	.write_button INPUT{
	  color:#484848;
	  font-family:'나눔고딕',nanumgothic,'맑은 고딕',malgun,'돋움',dotum;
	  font-weight : bold;
	  font-size:9pt;
	  border: expression( (this.type=='submit')?'3px solid #484848':'' );
	  filter:chroma(color= #000000);
	  width:80px;
	  height:30px;
	}
</style>
<body>
<div id="header">
	<div style="font-size:15pt; font-weight:bold;">
	<a href="setup.php"><img src="img/top_logo.png" border=0></a><br>
	GTBBS Edition W 설치
	</div>
</div>
<?
if($_POST['dbserver'] != "" && $_POST['dbuser'] != "" && $_POST['dbpass'] != "" && $_POST['dbname'] != ""){
	echo "<p style=\"font-size:12pt; font-weight:bold;\">완료 메세지가 나올 때 까지 기달려주세요.</p>";
	$host = $_POST['dbserver'];
	$user = $_POST['dbuser'];
	$pass = $_POST['dbpass'];
	$database = $_POST['dbname'];

	$conn = mysql_connect($host,$user,$pass);
	$db = mysql_select_db($database, $conn) or die("mysql 서버에 접속할수 없습니다.");
	
	echo "mysql 서버에 접속 성공!<br>";
	
	mysql_query("drop table bbs");
	mysql_query("drop table comment");
	mysql_query("drop table ban");
	
	echo "중복방지를 위한 테이블 초기화 완료!<br>";
	
	mysql_query("CREATE TABLE IF NOT EXISTS `bbs` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_s` int(11) NOT NULL,
  `subject` text NOT NULL,
  `writer` text NOT NULL,
  `content` text NOT NULL,
  `ip` text NOT NULL,
  PRIMARY KEY (`no`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2") or die("bbs 테이블 생성 실패");

	echo "bbs 테이블 생성 완료!<br>";
	
if($_POST['intro'] != NULL){
	mysql_query("INSERT INTO `bbs` (`no`, `time`, `time_s`, `subject`, `writer`, `content`, `ip`) VALUES
(1, '2013-02-04 00:14:02', 1359990546, 'GTBBS Edition W', 'W', '[img]img/intro.png[/img]<br>GTBBS Edition W 에 오신 것을 환영합니다.\r<br>\r<br>GTBBS Edition W 는 오리지날 GTBBS와는 달리 DB를 사용하고 있으며, 더 많은 기능을 제공하고 있습니다.', '127.0.0.1')") or die("bbs 인트로 메세지 생성 실패");

	echo "bbs 인트로 메세지 생성 완료!<br>";
}
	
	mysql_query("CREATE TABLE IF NOT EXISTS `comment` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `bbs` int(11) NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_s` int(11) NOT NULL,
  `writer` text NOT NULL,
  `content` text NOT NULL,
  `ip` text NOT NULL,
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1") or die("comment 테이블 생성 실패");

	echo "comment 테이블 생성 완료!<br>";
	
	mysql_query("CREATE TABLE IF NOT EXISTS `ban` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_s` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `why` text NOT NULL,
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1") or die("ban 테이블 생성 실패");

	echo "ban 테이블 생성 완료!<br>";
	
	unlink("config.php");
	echo "중복방지를 위한 config.php 삭제 완료!<br>";
	
	$fp = fopen("config.php", "w");
	
	$host = $_POST['dbserver'];
	$user = $_POST['dbuser'];
	$pass = $_POST['dbpass'];
	$database = $_POST['dbname'];
	
	if($_POST['ajax'] == NULL){
		$ajax = "false";
	}else{
		$ajax = "true";
	}
	if($_POST['dir'] != NULL){
		$dir = "".$_POST['dir']."/";
	}
	
$config =
"<?php
// 설정 부분
\$dir = \"".$dir."\";
\$ajax_comment = \"".$ajax."\";

// 쿼리 접속 부분
\$host = \"".$host."\"; // 서버호스트
\$user = \"".$user."\"; // DB 유저이름
\$pass = \"".$pass."\"; // DB 패스워드
\$database = \"".$database."\"; // DB 이름

\$conn = mysql_connect(\$host,\$user,\$pass);
\$db = mysql_select_db(\$database, \$conn) or die(mysql_error());
?>
";
	fwrite($fp, $config);
	echo "config.php 생성 완료!<br>";
	fclose($fp);
	
	unlink("setup.php");
	echo "setup.php 삭제 완료!<br><br>";
	echo "<b>GTBBS Edition W 설치가 성공적으로 이루어졌습니다!</b><br>";
	echo "<p style=\"font-size:9pt; font-weight:bold;\"><a href=\"index.php\">메인으로 이동하기!</a></p>";
}

if($_POST['dbserver'] != "" && $_POST['dbuser'] != "" && $_POST['dbpass'] != "" && $_POST['dbname'] != ""){
}else{
?>
<form method="post">
<center>
<p style="font-size:9pt; font-weight:bold;">
※ 정확하게 입력해주셔야 하며, 이미 등록이 되어 있다면 이전 자료는 삭제됩니다.
</p>

<p style="font-size:12pt; font-weight:bold;">
서버 호스트<br>
<input name="dbserver" type="text" value="localhost"></p>

<p style="font-size:12pt; font-weight:bold;">
DB 아이디<br>
<input name="dbuser" type="text"></p>

<p style="font-size:12pt; font-weight:bold;">
DB 비밀번호<br>
<input name="dbpass" type="text"></p>

<p style="font-size:12pt; font-weight:bold;">
DB 이름<br>
<input name="dbname" type="text"></p>

<p style="font-size:12pt; font-weight:bold;">
설치 경로<br>
<input name="dir" type="text"><br>
<span style="font-size:9pt;">
※ setup.php 가 존재하는 디렉토리 주소를 써주세요. 최상위 디렉토리인 경우 작성하지 않으시면 됩니다.<br>
ex) 127.0.0.1/gtbbs 에 setup.php가 존재한다면 gtbbs를 써주시면 됩니다.
</span>
</p>

<p style="font-size:9pt;">
<input type="checkbox" name="intro" checked> 인트로메세지를 생성합니다.</p>

<p style="font-size:9pt;">
<input type="checkbox" name="ajax" checked> Ajax를 이용한 코멘트 등록을 사용합니다.</p>
<div id="write_button">
<span class="write_button"><input type="submit" value="설치"></span>
</div>
</center>
</form>
<?
}
?>
<hr size="12px" color="#dddddd">
<center>
<p style="font-size:9pt;">위에 입력된 데이터는 아무 곳에도 저장되지 않으며, 설치용도에만 사용됩니다.</p>
<p style="font-size:9pt;">DB 호스트 네임, DB 아이디, DB 비밀번호, DB 이름 정보는 서버 호스팅 관리자로부터 정보를 확인하세요.</p>
</center>
</body>
</html>