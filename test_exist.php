<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'encoding.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'db/conn.php');
	require_once($_SERVER['DOCUMENT_ROOT']."mail/sendmail.php");
	require_once($_SERVER['DOCUMENT_ROOT'].'db/jsonResponse.php');
	session_start();

	$respData = array();
	
	$name = $_POST['name'];
	
	$ver = SQLite3::version();
	$db = new SQLite3('emwave2.emdb');
	$version = $db->querySingle('SELECT SQLITE_VERSION()');
	$res = $db->query("SELECT * FROM User WHERE FirstName = '".$name."'");
	
	$count = 0;
	while ($row = $res->fetchArray()) 
		$count++;
	if($count == 0)
	{
		$resp = array('state'=>'error');
		echo json_encode($resp, JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$sql = "SELECT *  FROM `t_member` WHERE `f_fullname` = '".$name."'";
	$rs = $conn->execute($sql);
	$len = count($rs);
	
	if($len == 0)
	{
		$respData = array();
		while ($row = $res->fetchArray()) {
			$respData['fullName'] = $row['FirstName'];
			$respData['birth'] = $row['DOB'];
			$respData['sex'] = $row['Sex'];
		}	
		
		$return = json_encode($respData, JSON_UNESCAPED_UNICODE);
		$resp = array('state'=>'noRegister','return'=>$return);
		echo json_encode($resp, JSON_UNESCAPED_UNICODE);
		exit;
	}
	if($len > 0)
	{
		$resp = array('state'=>'success');
		echo json_encode($resp, JSON_UNESCAPED_UNICODE);
		exit;
	}	
?>