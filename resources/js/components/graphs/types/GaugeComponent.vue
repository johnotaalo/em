<template>
	<div>
		<h3><center>{{ this.title }}</center></h3>
		<highcharts :options="graphOptions"></highcharts>
	</div>
</template>
<script type="text/javascript">

	export default {
		name: 'GraphComponent',
		props: {
			gdata: { type: null, default: null },
			title: { type: String, default: null }
		},
		computed: {
			graphOptions(){
				return {
					chart: {
						type: 'solidgauge',
						height: '150px'
					},

					title: this.title,

					pane: {
						startAngle: -90,
						endAngle: 90,
						background: {
							innerRadius: '60%',
							outerRadius: '100%',
							shape: 'arc'
						}
					},

					tooltip: {
						enabled: false
					},
					credits: {
						enabled: false
					},

					// the value axis
					yAxis: {
						min: 0,
						max: 100,
						title: {
							// text: '% Immunised',
							text: null,
							y: -50
						},
						stops: [
							[0.1, '#DF5353'], // red,
							[0.5, '#DDDF0D'], // yellow,
							[0.9, '#55BF3B'] // green
						],
						lineWidth: 0,
						minorTickInterval: null,
						tickAmount: 2,
						labels: {
							y: 16
						}
					},

					plotOptions: {
						solidgauge: {
							dataLabels: {
								y: -30,
								borderWidth: 0,
								useHTML: true
							}
						}
					},
					series: [{
					name: this.title,
					data: [this.gdata],
					dataLabels: {
						format: '<div style="text-align:center"><span style="font-size:18px;color:black;">{y}</span><span style="font-size:18px;color:silver">%</span></div>'
					},
					tooltip: {
						valueSuffix: '%',
					},
					}],
				}
			}
		}
	}
</script>