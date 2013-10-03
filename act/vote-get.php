<?php
	header("Content-type:text/html; charset=utf-8");
	function printerror($str){
		echo "{stat:-1,str:'".$str."'}";
		exit;
	}
	
	if(!isset($_POST["key"]))
		printerror("格式错误");
	if(!($key = base64_decode($_POST["key"])))
		printerror("不存在..");
	if(!filter_var($key, FILTER_VALIDATE_INT))
		printerror("不存在...");

	$con = mysql_connect("localhost","root","root");
	if(!$con)
		printerror("数据库错误:".mysql_error());
	mysql_select_db("php1_db",$con);

	$result = mysql_query("
					SELECT * FROM forms WHERE ID='".$key."'
				",$con);
	$row = mysql_fetch_array($result);
	mysql_close($con);

	if(!$row)
		printerror("不存在..");
	
	//echo $row["labels"];
	$labels = explode(",",$row["labels"]);
	//print_r($labels);
	$strlabels = json_encode(base64_decode($labels[0]));
	for($i=1;$i<count($labels);$i++)
		$strlabels = $strlabels.",".json_encode(base64_decode($labels[$i]));
	//echo $strlabels;
	echo "{
				stat:0,
				title:".json_encode(base64_decode($row["title"])).",
				describe:".json_encode(base64_decode($row["describes"])).",
				labels:[".$strlabels."]
		}";
?>