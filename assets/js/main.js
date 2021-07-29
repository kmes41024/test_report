document.getElementById("btn").addEventListener('click',check);

function check()
{

	var name = $('#fullname').prop('value');
	console.log(name);
	
	$.ajax({  
		type: "POST",   //提交的方法
		datatype:"json",
		url:"test_exist.php", //提交的地址  
		data:
		{
			name : name,
		},
		success: function(data) {  //成功
			if (data.state == 'success')
			{
				var str = "javascript:location.href='test_result.php?name="+name+"'";
				
				layui.layer.msg('正在跳转结果页面',{time:5000});
				setTimeout(str, 1000);  
			}
			else if(data.state == 'noRegister')
			{
				userData = JSON.parse(data.return);
				var str = "javascript:location.href='register_index.php?name="+userData['fullName']+"&birth="+userData['birth']+"&sex="+userData['sex']+"'";
				
				layui.layer.msg('尚未注册，正在跳转注册页面',{time:5000});
				setTimeout(str, 1000);  
			}
			else if(data.state == 'error')
			{
				layui.layer.msg('此姓名无测验记录，请重新输入',{time:5000});
				document.getElementById('fullname').value = "";
			}
		},
		complete: function(data) {
			console.log(data);
		},
	});
}