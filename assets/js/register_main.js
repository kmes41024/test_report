function onSend()
{
	var sex = document.getElementById('f_sex').value;
	var birthday = document.getElementById('f_birthday').value;
	var mPattern = /^1[3456789]\d{9}$/; 
	
	if($("#f_fullname").val() != "" && sex != 'null' && birthday != "" && $("#f_mobile").val() != "")
	{
		if(mPattern.test($("#f_mobile").val()) == false)
		{
			alert("手机格式错误，请重新输入");
			document.getElementById('f_mobile').value = "";
		}
		else
		{
			$.ajax({  
				type: "POST",   //提交的方法
				datatype:"json",
				url:"register_send.php", //提交的地址  
				data:$('#contactForm').serialize(),// 序列化表单值  
				success: function(data) {  //成功
					console.log(data.state);  //就将返回的数据显示出来
					if (data.state == 'success')
					{
						layui.layer.msg('新增成功',{time:5000});
						var name = document.getElementById('f_fullname').value;
						setTimeout($script, 000); 
					}
					else
					{
						layui.layer.alert('error');
					}
				},
			});
		}
	}
	else
	{
		alert("请填写完整");
	}
}