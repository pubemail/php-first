<?php
	header("Content-type:text/html; charset=utf-8");
	 function printback($stat,$str){
	 	echo "{stat:".$stat.",str:'".$str."'}";
	 	exit;
	 }
	 function check_stuID_name($stuID,$name){
	 	/*
			检查姓名和学号的关联是否正确...
			先空着吧...
	 	*/
		if($stuID==""||$name=="")
			return false;
	 	return true;
	 }

	 if((!isset($_POST["stuID"]))||(!isset($_POST["name"]))||(!isset($_POST["key"]))||(!isset($_POST["label"])))
	 	printback(-1,"格式错误");
	 $stuID = $_POST["stuID"];
	 $name = $_POST["name"];
	 $key = $_POST["key"];
	 $label = $_POST["label"];

	 if(!check_stuID_name($stuID,$name))
	 	printback(-1,"姓名或学号错误");
	 $stuID = base64_encode($stuID);
	 $name = base64_encode($name);

	 if($key=="")
	 	printback(-1,"KEY不能为空");
	 if(!($key = base64_decode($key)))
	 	printback(-1,"非法的KEY");
	 
	 if($label=="")
	 	printback(-1,"选项不能为空");
	 $label = base64_encode($label);

	 $con = mysql_connect("localhost","root","root");
	 if(!$con)
	 	printback(-1,"数据库错误:"+mysql_error());
	 mysql_select_db("php1_db",$con);

	 $result = mysql_query("
	 		SELECT * FROM forms
	 			WHERE ID = '".$key."'",$con);
	 if(!($row = mysql_fetch_array($result))){
	 	mysql_close($con);
	 	printback(-1,"非法的KEY");
	 }
	 
	 if(!in_array($label,explode(",",$row["labels"]))){
	 	mysql_close($con);
	 	printback(-1,"选项不存在");
	 }

	 $result = mysql_query("
	 		SELECT * FROM votes
	 			WHERE stuID = '".$stuID."'
	 			AND formkey = '".$key."'
	 	");
	 if(mysql_fetch_array($result)){
	 	mysql_close($con);
	 	printback(-1,"请不要重复提交");
	 }

	 mysql_query("INSERT INTO votes(stuID,name,formkey,label)
	 		VALUES(
	 				'".$stuID."',
	 				'".$name."',
	 				'".$key."',
	 				'".$label."'
	 			)
	 	",$con);

	mysql_close($con);

	printback(0,"提交成功");
?>