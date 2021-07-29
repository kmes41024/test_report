<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'encoding.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'db/conn.php');
	require_once($_SERVER['DOCUMENT_ROOT']."mail/sendmail.php");
	require_once($_SERVER['DOCUMENT_ROOT'].'db/jsonResponse.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'utilinc/function_utility.php');
	session_start();

	$sqlAttr = array();
	
	$sqlAttr['f_fullname'] = $_POST['f_fullname'];
	$sqlAttr['f_sex'] = $_POST['f_sex'];
	$sqlAttr['f_birthday'] = $_POST['f_birthday'];
	$sqlAttr['f_mobile'] = $_POST['f_mobile'];
	$sqlAttr['f_company_id'] = '1';
	
	$affect_id = $conn->insert('t_member', $sqlAttr);
	
	$resp = array('state'=>'success');
	echo json_encode($resp, JSON_UNESCAPED_UNICODE);
	exit;
?>