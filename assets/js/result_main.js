// 路径配置
require.config({
	paths: {
		echarts: 'http://echarts.baidu.com/build/dist'
	}
});

// 使用
require(
	[
		'echarts',
		'echarts/chart/line' // 使用柱状图就加载bar模块，按需加载
	],
	function (ec) {
		// 基于准备好的dom，初始化echarts图表
		var myChart = ec.init(document.getElementById('main')); 
		
		var option = {
			xAxis : [
				{
					splitLine: {show: false},
					type : 'category',
					boundaryGap : false,
					data : [
						<?php
							for($i = 0; $i < count($liveIBI); $i++)	
							{
								echo $i.",";
							}
						?>
					],
				}
			],
			yAxis : [
				{
					 splitLine: {show: true}
				}
			],
			series : [
				{
					name:'最高气温',
					type:'line',
					symbol: 'none',
					smooth: true,
					data : [
						<?php
							for($i = 0; $i < count($liveIBI); $i++)	
							{
								echo $liveIBI[$i].",";
							}
						?>
					],
				}
			]
		};
		
		myChart.setOption(option); 
	}
);