<?php
	header("Content-type:text/html; charset=utf-8");
	function printback($stat,$str){
		echo "{stat:".$stat.",str:'".$str."'}";
		exit;
	}
	if((!isset($_POST["title"]))||(!isset($_POST["password"]))||(!isset($_POST["describe"]))||(!isset($_POST["cnt"])))
		printback(-1,"格式错误");

	$title = $_POST["title"];
	$password = $_POST["password"];
	$describe = $_POST["describe"];
	$cnt = $_POST["cnt"];

	if($title=="")
		printback(-1,"标题不能为空");
	if($password=="")
		printback(-1,"密码不能为空");
	//if($describe=="")
	//	printback(-1,"描述不能为空");
	if($cnt<2)
		printback(-1,"选项过少");

	if(!isset($_POST["label0"]))
		printback(-1,"格式错误");
	
	$title = base64_encode($title);
	$password = md5($password);
	$describe = base64_encode($describe);

	$labels = base64_encode($_POST["label0"]);
	for($i=1;$i<$cnt;$i++)
		if(!isset($_POST["label".$i]))
			printback(-1,"格式错误");
		else
			$labels = $labels.",".base64_encode($_POST["label".$i]);

	$con = mysql_connect("localhost","root","root");
	if(!$con)
		printback(-1,"数据库错误:".mysql_error());

	mysql_select_db("php1_db",$con);
	mysql_query("
		INSERT INTO forms(title,password,describes,labels)
		VALUES(
				'".$title."',
				'".$password."',
				'".$describe."',
				'".$labels."'
			)",$con);

	$key = base64_encode(mysql_insert_id());

	mysql_close($con);

	printback(0,"KEY:".$key);
?>