<? if($_GET['ajax_load'] != 1){ ?>
<div name="page">
<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>GTBBS</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<?
$userAgent = $_SERVER['HTTP_USER_AGENT']; // 브라우저 체크
if(preg_match('/iPhone/i', $userAgent) || preg_match('/iPod/i', $userAgent) || preg_match('/BlackBerry/i', $userAgent) || preg_match('/Android/i', $userAgent) || preg_match('/Windows CE/i', $userAgent) || preg_match('/LG/i', $userAgent) || preg_match('/MOT/i', $userAgent) || preg_match('/SAMSUNG/i', $userAgent) || preg_match('/SonyEricsson/i', $userAgent) || preg_match('/Symbian/i', $userAgent) || preg_match('/Opera Mobi/i', $userAgent) || preg_match('/Opera Mini/i', $userAgent) || preg_match('/IEmobile/i', $userAgent)){
?>
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=0.1, minimum-scale=0.75, width=device-width" />
<?
}
}
?>
	<link href="css/smoothness/jquery-ui-1.9.2.custom.css" rel="stylesheet">
	<script src="js/jquery-1.8.3.js"></script>
	<script src="js/jquery-ui-1.9.2.custom.js"></script>
	<script>
		count = 0;
	 $(document).ready(function() {
		function bbs_ajax_call(url){
			if(url != "back_but"){
				$.ajax({
					type: "GET",
					url: "./index.php?ajax_load=1",
					data:{ "bbs": url },
					success: function(msg){
						$("#content").html(msg);
					},
					error: function(){
						alert("오류");
					}
				});
			}else{
				$.ajax({
					url: "./index.php",
					success: function(msg){
						$("#content").html(msg);
					},
					error: function(){
						alert("오류_01");
					}
				});
			}

		}
		function comment_ajax_call(url,name,text,no){
			var time = new Date().getTime() / 1000;
			$.ajax({
				type: "POST",
				url: "comment.php",
				data:{ "file" : url , "ajax_name" : name , "ajax_text" : text , "ajax_no" : no , "ajax_time" : time},
				success: function(msg){
					$("#incomment").html("로딩 중 입니다.");
					bbs_ajax_call(url);
					interval_s = setInterval(function(){count = count + 1;
						if( count < 5 ){	
							limit = 5-count;
							$("#incomment").html("글작성과 추가작성 까지" + limit + "초 남았습니다");
						}else if(count >= 5){
							$("#incomment").html("완료!");
							bbs_ajax_call(url);
							clearInterval(interval_s);
					}},1000);

				},
				error: function(){
					alert('오류발생');
				}
			});
		}
		
		
		$( ".bbs_more" ).button();
		$( ".comment_but" ).button();
		$( ".n_s" ).button();
		
		$( ".comment_but").click(function(){
		var c_link_select = $(this).attr("id"); // 임시로 안씀
		comment_ajax_call($("#ajax_no").val(),$("#ajax_name").val(),$("#ajax_text").val(),$("#ajax_no").val());
		});
		
		
	 });
	 
	 </script>
<? if($_GET['ajax_load'] != 1){ ?>	 
	 <style>
	body{
	  font-family:'나눔고딕',nanumgothic,'맑은 고딕',malgun,'돋움',dotum;
	  margin:0px;
	  padding:0px;
	}
	#page{
		padding:10px;
	}
	.bbs_more{
		margin-top:30px;
		padding:7px;
		background-color:#2672ec;
		font-size:11pt;
		color:#ffffff;
	}

	.comment_but{
	font-size:15px;
	}
	
	#header{
	text-align:right;
	width:100%;
	height:80px;
	border-bottom:2px solid #ddd;
	padding-bottom:5px;
	margin-bottom:12px;
	}
	
	#new_thread{
		margin-top:10px;
		padding:10px;
		background-color:#2672ec;
		font-size:11pt;
		color:#ffffff;
	}
	
	#more_thread{
		margin-top:10px;
		padding:7px;
		background-color:#2672ec;
		font-size:14pt;
		color:#ffffff;
	}
	
	.srch{width:100%;padding:5px 0}
	.srch legend{overflow:hidden;visibility:hidden;position:absolute;top:0;left:0;width:1px;height:1px;font-size:0;line-height:0}
	.srch{color:#c4c4c4;text-align:center}
	.srch select,.srch input{margin:-1px 0 1px;font-size:12px;color:#373737;vertical-align:middle}
	.srch .keyword{margin-left:1px;padding:2px 3px 5px;border:1px solid #b5b5b5;font-size:12px;line-height:15px}

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
<?
$userAgent = $_SERVER['HTTP_USER_AGENT']; // 브라우저 체크
if(preg_match('/iPhone/i', $userAgent) || preg_match('/iPod/i', $userAgent) || preg_match('/BlackBerry/i', $userAgent) || preg_match('/Android/i', $userAgent) || preg_match('/Windows CE/i', $userAgent) || preg_match('/LG/i', $userAgent) || preg_match('/MOT/i', $userAgent) || preg_match('/SAMSUNG/i', $userAgent) || preg_match('/SonyEricsson/i', $userAgent) || preg_match('/Symbian/i', $userAgent) || preg_match('/Opera Mobi/i', $userAgent) || preg_match('/Opera Mini/i', $userAgent) || preg_match('/IEmobile/i', $userAgent)){
?>
<?
}else{
?>
	<script>
	$(function() {
		$( document ).tooltip({
			track: true
		});
	});
	</script>
<?
}
?>	
</head>	
<body>
<div id="page">
<? } ?>
<?
$fp = fopen("config.php", "r");
if(!$fp){
	echo "<script>alert(\"GTBBS를 처음 만나시는 분이시군요!\\n확인을 누르시면 설치페이지로 이동합니다.\");</script>";
	echo "<script type=\"text/javascript\"> location.href=\"setup.php\" </script>";
}
fclose($fp);

include_once("config.php");
?>