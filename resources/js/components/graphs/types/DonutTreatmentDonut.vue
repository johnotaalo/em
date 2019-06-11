<template>
	<div>
		<h3><center>{{ this.title }}</center></h3>
		<highcharts :options="graphOptions"></highcharts>
	</div>
</template>

<script type="text/javascript">
	export default {
		name: "DonutTreatmentDonut",
		props: {
			title: { default: null, type: String },
			gdata: { type: null, default: null },
			labels: { type: null, default: null },
			colors: { type: null, default: null }
		},
		data: {
			
		},
		computed: {
			graphOptions() {
				var seriesData = [];
				_.forOwn(this.labels, (treatment, id) => {
					var obj = {}
					obj.name = treatment
					if (id == "NOTX") {
						obj.borderColor = "red"
					}
					obj.color = this.colors[id]
					var sortedData = 0;
					_.forOwn(this.gdata, (data, k) => {
						sortedData += data[id]
					})

					obj.y = sortedData
					
					seriesData.push(obj)
				})

				return {
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: 0,
						plotShadow: false
					},
					title: {
						text: null,
						// align: 'center',
						// verticalAlign: 'middle',
						// y: 40
					},
					subtitle: {
						text: 'Hover to view percentage'
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b>'
					},
					plotOptions: {
						pie: {
							dataLabels: {
								enabled: false,
								distance: -50,
								style: {
									fontWeight: 'bold',
									color: 'white'
								}
							},
							startAngle: -90,
							endAngle: 90,
							center: ['50%', '75%'],
							size: '110%',
							showInLegend: true
						}
					},
					series: [{
						type: 'pie',
						name: this.title + " Treatment",
						innerSize: '50%',
						data: seriesData
					}]
				}
				// return {
				//     chart: {
				//         type: 'pie',
				//         margin: 0,
				//         style: {
				// 			fontFamily: 'Nunito'
				// 		},
				// 		options3d: {
				// 			enabled: true,
				// 			alpha: 45
				// 		}
				//     },
				// 	credits: {
				// 		enabled: false
				// 	},
				//     title: {
				//     	// verticalAlign: 'middle',
				//     	// loating: true,
				//      //    text: "<center><b>"+facilityCount+"</b> Facilities<br/>Assessed</center>",
				//      //    style: {
				//      //    	fontSize: "12px"
				//      //    }
				//     	text: null
				//     },
				//     xAxis: {
				//         categories: ['Treatment'],
				//         visible: false
				//     },
				//     yAxis: {
				//         min: 0,
				//         gridLineWidth: 0,
				//         minorGridLineWidth: 0,
				//         visible: false,
				//         title: {
				//             text: null
				//         }
				//     },
				//     legend: {
				//         reversed: true
				//     },
				//     plotOptions: {
				//         pie: {
				//         	innerSize: '60%',
				//         	dataLabels: {
				//         		enabled: true,
				//         		format: '<b>{point.name}</b>, {point.y}'
				//         	}
				//         }
				//     },
				//     series: [{
				//     	name: "Treatment",
				//     	data: seriesData
				//     }]
				// };
			}
		}
	}
</script>