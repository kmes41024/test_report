<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'encoding.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'db/conn.php');
	require_once($_SERVER['DOCUMENT_ROOT']."mail/sendmail.php");
	
	session_start();
	
	$fullName = $_GET['name'];	
	$birth = $_GET['birth'];
	$sex = $_GET['sex'];
	
	$insert_1 = substr_replace($birth, '-', 4, 0);
	$date = substr_replace($insert_1, '-', 7, 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Kode is a Premium Bootstrap Admin Template, It's responsive, clean coded and mobile friendly">
	<meta name="keywords" content="bootstrap, admin, dashboard, flat admin template, responsive," />
	<title>Register</title>

	<link href="assets/css/root.css" rel="stylesheet">
	<link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
	<div style = "opacity: 0.95">
		<div class="modal-dialog" style="width:17cm">
			<div class="modal-content">
				<div class="modal-header" align="center" style = "height:75px;line-height:40px;">
					<font style="font-family:'思源黑体 CN MEDIUM';font-size:32px;color:#000; " class="m-modal-title" align = "center">
						新增客户基本资料
					</font>
				</div>
				<div class="modal-body" align="center" style="font-family:'思源黑体 CN NORMAL';font-size:20px;padding: 0px 20.5px 0px 20px">
					<div class="panel-body">
						<form id="contactForm" onsubmit="return false" class="form-horizontal" method="post"><br>
							<div class="form-area">
								<div class="form-group">
									<table>
										<tr>
											<td class = "col_1">
												<div class = "info_col">
													<pre align = "right">客户姓名  :</pre>
												</div>
											</td>
											<td colspan = "3" class = "col_span">
												<input type='text' value = <?php echo "'{$fullName}'"; ?> name = "f_fullname" id = "f_fullname" class="form-control" style = "height:50px;width:10cm;font-size:20pt;border-radius:100px;padding-left:25px;">
											</td>
										</tr>
										<tr>
											<td class = "col_1">
												<div class = "info_col">
													<pre align = "right">    性别  :</pre>
												</div> 
											</td>
											<td colspan = "3" class = "col_span">
												<select name = "f_sex" id = "f_sex" class = "info_col">
													<option value = "null"> [请选择性别]</option>
													<?php
														if($sex == 1)
														{
															echo '<option value = "男">男</option>';
															echo '<option value = "女" selected>女</option>';
														}
														else if ($sex == 0)
														{
															echo '<option value = "男" selected>男</option>';
															echo '<option value = "女">女</option>';
														}
													?>
												</select>
											</td>
										</tr>
										<tr>
											<td class = "col_1">
												<div class = "info_col">
													<pre align = "right">出生年月  :</pre>
												</div> 
											</td>
											<td colspan = "3" class = "col_span">
												<input type = "date" value = <?php echo "'{$date}'"; ?> name = "f_birthday" id = "f_birthday" class="form-control" style = "height:50px;width:10cm;font-size:20pt;border-radius:100px;padding-left:25px;">
											</td>
										</tr>
										<tr>
											<td class = "col_1">
												<div class = "info_col">
													<pre align = "right">  手机号  :</pre>
												</div> 
											</td>
											<td colspan = "3" class = "col_span">
												<input type='text' name = "f_mobile" id = "f_mobile" class="form-control" style = "height:50px;width:10cm;font-size:20pt;border-radius:100px;padding-left:25px;">
											</td>
										</tr>
										<tr>
											<td style = "height:30px;"></td>
										</tr>
									</table>
									<table>
										<tr>
											<td class = "btn_col">
												<a href = "testSearch_index.php">
													<button type = "button" class="form-control">放弃离开</button>
												</a>
											</td>
											<td class = "btn_col">
												<button type = "submit" class="form-control" onclick="onSend()">确认送出</button>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>		
	</div>
</body> 
	<script type="text/javascript" src="assets/js/jquery.min.js"></script>
	<script src="assets/js/bootstrap/bootstrap.min.js"></script>

	<script type="text/javascript" src="assets/js/plugins.js"></script>
	<script type="text/javascript" src="layui/layui.all.js"></script>
	<script type="text/javascript" src="assets/js/main.js"></script>
</html>