<!DOCTYPE html>
<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'encoding.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'db/conn.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'include/function_debug.php');
    session_start();
	
	$userName = $_GET['name'];
	
	$ver = SQLite3::version();
	
	$db = new SQLite3('emwave2.emdb');
	$version = $db->querySingle('SELECT SQLITE_VERSION()');
	
	/******************************获得user id*****************************/
	$userID = 0;
	
	$sql_1 = "SELECT *  FROM `t_member` WHERE `f_fullname` = '".$userName."'";
	$rs_1 = $conn->execute($sql_1);
	$len = count($rs_1);
	
	$userID = $rs_1[$len - 1]['id'];
	/******************************获得user id*****************************/
	
	/*****************************获得user uuid****************************/
	$userUUID = 0;
	$birth = 0;
	$sex = 0;
	
	$res = $db->query("select * from User where FirstName ='".$userName."'");

	while ($row = $res->fetchArray()) {
		$userUUID = $row['UserUuid'];
		$birth = $row['DOB'];
		$sex = $row['Sex'];
	}	
	/*****************************获得user uuid****************************/
	
	//////////////////////////////测试资料参数//////////////////////////////
	$duration = 0;							//Duration
	$intervals = 0;							//Number of RR Intervals
	$meanBPM = 0;							//Mean Heart Rate
	$meanRR = 0;							//Mean Inter Beat Interval
	$SDNN = 0;								//SDNN
	$RMSSD = 0;								//RMSSD
	$TP = 0;								//Total Power
	$VLF = 0;								//Very Low Frequency
	$LF = 0;								//Low Frequency
	$HF = 0;								//High Frequency
	$LHratio = 0;							//Low Frequency/High Frequency ratio
	$normalizedCoherence = 0;				//Normalized Coherence
	$data = 0;								//图表个点的值
	$date = 0;
	//////////////////////////////测试资料参数//////////////////////////////
	
	/******************************获得测试资料*****************************/	
	$res = $db->query("SELECT * FROM Assessment WHERE UserUuid = '".$userUUID."'");

	while ($row = $res->fetchArray()) {
		$duration = $row['Duration'];
		$intervals = $row['Intervals'];
		$meanBPM = $row['meanBPM'];
		$meanRR = $row['MeanRR'];
		$SDNN = $row['SDNN'];
		$RMSSD = $row['RMSSD'];
		$TP = $row['TP'];
		$VLF = $row['VLF'];
		$LF = $row['LF'];
		$HF = $row['HF'];
		if($LF == 0 || $HF == 0)
		{
			$LHratio = 0;
		}
		else
		{
			$LHratio = $LF/$HF;
		}
		$normalizedCoherence = $row['NormCoherence'];
		$data = $row['LiveIBI'];
		$date = $row['DateCreated'];
	}
	
	$dataArr = explode(',', $data);		//用逗号将字串分开
	$liveIBI = array();
	$amount = 0;
	
	for($i = 0; $i < count($dataArr); $i++)	
	{
		if(strstr($dataArr[$i], "-t") == false)
		{
			$liveIBI[$amount] = substr($dataArr[$i],0,strlen($dataArr[$i])-1);
			$amount++;
		}
	}
	/******************************获得测试资料*****************************/
	
	/*******************************写入资料库******************************/
	$sqlAttr = array();
	$sqlAttr['f_userUUID'] = $userUUID;
	$sqlAttr['f_birth'] = $birth;
	$sqlAttr['f_sex'] = $sex;
	$sqlAttr['f_duration'] = $duration;
	$sqlAttr['f_intervals'] = $intervals;
	$sqlAttr['f_meanBPM'] = $meanBPM;
	$sqlAttr['f_meanRR'] = $meanRR;
	$sqlAttr['f_SDNN'] = $SDNN;
	$sqlAttr['f_RMSSD'] = $RMSSD;
	$sqlAttr['f_TP'] = $TP;
	$sqlAttr['f_VLF'] = $VLF;
	$sqlAttr['f_LF'] = $LF;
	$sqlAttr['f_HF'] = $HF;
	$sqlAttr['f_LHratio'] = $LHratio;
	$sqlAttr['f_normCoherence'] = $normalizedCoherence;
	$sqlAttr['f_uid'] = $userID;
	$sqlAttr['f_addtime'] = $date;
	
	$affectID = $conn->insert('t_emwave_data', $sqlAttr);
	/*******************************写入资料库******************************/
	
	/********************************判断资料*******************************/
	$nowYear = date("Ymd");
	$age = intval(($nowYear-$birth)/10000);
	
	$sql = "SELECT *  FROM `t_emwave_param` WHERE `f_sex` = ".$sex." AND `f_minAge` <= ".$age." AND `f_maxAge` >= ".$age;
	$rs = $conn->execute($sql);
	$len = count($rs);
	
	$state = array();
	
	for($i = 0; $i < 7; $i++)
	{
		$userMeasure = $rs[$i]['f_measure'];
		
		if($$userMeasure < $rs[$i]['f_minValue'])
		{
			$state[$userMeasure] = $$userMeasure - $rs[$i]['f_minValue'];
		}
		else if($rs[$i]['f_minValue'] <= $$userMeasure && $$userMeasure <= $rs[$i]['f_maxValue'])
		{
			$state[$userMeasure] = 0;
		}
		else if($rs[$i]['f_maxValue'] < $$userMeasure)
		{
			$state[$userMeasure] = $$userMeasure - $rs[$i]['f_maxValue'];
		}
	}
	
	$sqlAttr_2 = array();
	$sqlAttr_2['f_TP_state'] = $state['TP'];
	$sqlAttr_2['f_VLF_state'] = $state['VLF'];
	$sqlAttr_2['f_LF_state'] = $state['LF'];
	$sqlAttr_2['f_HF_state'] = $state['HF'];
	$sqlAttr_2['f_LHratio_state'] = $state['LHratio'];
	$sqlAttr_2['f_SDNN_state'] = $state['SDNN'];
	$sqlAttr_2['f_RMSSD_state'] = $state['RMSSD'];
	
	$place = "WHERE `t_emwave_data`.`f_userUUID` = '".$userUUID."'";
	$conn->update('t_emwave_data', $sqlAttr_2, $place);
	/********************************判断资料*******************************/
	
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Test Result</title>

	<link rel="stylesheet" type="text/css" href="assets/css/A4.css">
	<link rel="stylesheet" type="text/css" href="assets/css/my_style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/font.css">
	<link href="assets/css/root.css" rel="stylesheet">
	<link href="assets/css/result_style.css" rel="stylesheet">
</head>
<body>
<section class="page">
	<div class="main-content" style = "padding:2cm;">
		    <table align = "center">
				<tr>
					<td style = "height:30px;width:2.5cm;">姓名</td>
					<td style = "height:30px;width:7cm;"><?php echo $userName; ?></td>
					<td style = "height:30px;width:2cm;">日期</td>
					<td style = "height:30px;width:6.5cm;"><?php echo date("Y年m月d日 H:i:s",$date); ?></td>
				</tr>
				<tr>
					<td>性别</td>
					<td>
						<?php
							if($sex == 1)
							{
								echo 'Female';
							}
							else if($sex == 0)
							{
								echo 'Male';
							}
						?>
					</td>
					<td>年龄</td>
					<td><?php echo $age; ?></td>
				</tr>
			</table>
			
			<table align = "center">
				<tr>
					<td style = "width:18cm;">
						<div id="main"></div>
					</td>
				</tr>
			</table>
			
			<table class="table table-striped">
				<thead>
				  <tr style = "background:#7a7aef;color:white;">
					<td class = "table_head">英文名称</td>
					<td class = "table_head">中文名称</td>
					<td class = "table_head">测量值</td>
					<td class = "table_head">结果</td>
				  </tr>
				</thead>
				<tbody>
					<?php
						$nameList = array("TP"=>"整体心律","VLF"=>"","LF"=>"交感神经","HF"=>"副交感神经","LF/HF"=>"自律神经平衡性","SDNN"=>"自律神经活性高低","RMSSD"=>"");
						$keyList = array_keys($nameList);
						for($i = 0; $i < 7; $i++)
						{
							$englishName = $keyList[$i];
							if($englishName == 'LF/HF')
								$englishName = 'LHratio';
							echo "<tr class = 'infoBox'>";
							echo "<td class = 'infoBox'>".$keyList[$i]."</td>";
							echo "<td class = 'infoBox'>".$nameList[$keyList[$i]]."</td>";
							echo "<td class = 'infoBox'>".round($$englishName,2)."</td>";
							if($state[$englishName] == 0)
							{
								echo "<td class = 'infoBox'>正常</td>";
							}
							else if($state[$englishName] > 0)
							{
								echo '<td class = "infoBox"><div><i class="fa fa-arrow-circle-up";></i> '.round($state[$englishName],2).'</div></td>';
							}
							else if($state[$englishName] < 0)
							{
								echo '<td class = "infoBox"><div><i class="fa fa-arrow-circle-down"></i> '.round(-$state[$englishName],2).'</div></td>';
							}
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
			
			<table class = "note" style = "margin-top:55px">
				<tr>
					<td class = "col_1" style = "padding-top:10px;">＊<测量值>显示受测者的各项实际测量数值; <结果>顯示本次测量數值與同年龄层的正常平均值之差异数值。<td>
				</tr>
				<tr>
					<td class = "col_1">＊交感神经主要维持人体紧张状态时的生理需要。当交感神经过於兴奋易产生：心率加快、呼吸加快、血压升高、脸红、入睡时亢奋、心悸、便秘、憋气胸闷等。</td>
				</tr>
				<tr>
					<td class = "col_1" style = "padding-bottom:10px;">＊副交感神经主要维持人体安静时的生理需要。当副交感神经过於兴奋易产生：血压降低、身体倦怠、头昏目眩、精神不济等。</td>
				</tr>
			</table>
</section>
	<script type="text/javascript" src="assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/plugins.js"></script>
	<script type="text/javascript" src="assets/js/result_main.js"></script>
	<script type="text/javascript" src="http://echarts.baidu.com/build/dist/echarts.js"></script>
</body>
</html>