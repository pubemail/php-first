<?php
	header("Content-type:text/html; charset=utf-8");
	function printerror($str){
		echo "{stat:-1,str:'".$str."'}";
		exit;
	}
	if((!isset($_POST["key"]))||(!isset($_POST["password"])))
		printerror("格式错误");
	if(!($key = base64_decode($_POST["key"])))
		printerror("KEY非法");
	$password = md5($_POST["password"]);

	$con = mysql_connect("localhost","root","root");
	if(!$con)
		printerror("数据库错误"+mysql_error());
	mysql_select_db("php1_db",$con);
	$result = mysql_query("
			SELECT * FROM forms WHERE 
				ID='".$key."' AND password = '".$password."'
		",$con);
	if(!($row = mysql_fetch_array($result))) {
		mysql_close($con);
		printerror("KEY或密码错误");
	}
	$arr_labels = explode(",",$row["labels"]);

	$result = mysql_query("SELECT * FROM votes WHERE formkey = '".$key."'",$con);
	if(!($row = mysql_fetch_array($result)))
		echo "{stat:0,votes:[]}";
	else {
		echo "{stat:0,votes:[";
		echo "{
				stuID:'".base64_decode($row["stuID"])."',
				name:'".base64_decode($row["name"])."',
				label:'".base64_decode($row["label"])."'
			}";
		while($row = mysql_fetch_array($result))
			echo ",{
					stuID:'".base64_decode($row["stuID"])."',
					name:'".base64_decode($row["name"])."',
					label:'".base64_decode($row["label"])."'
				}";
		echo "],
			labels:[".json_encode(base64_decode($arr_labels[0]));
		for($i = 1;$i < count($arr_labels);$i++)
			echo ",".json_encode(base64_decode($arr_labels[$i]));
		echo "]}";

	}
	mysql_close($con);
?>