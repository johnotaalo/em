<template>
	<div>
		<highcharts :options="graphOptions" style = "height: 400px;"></highcharts>
	</div>
</template>

<script type="text/javascript">
	import Highcharts from 'highcharts'
	import exportingInit from 'highcharts/modules/exporting'
	exportingInit(Highcharts)
	export default {
		name: 'DiarrhoeaSubcountyTreatment',
		props: {
			title: { type: null, default: null },
			data: { type: null, default: null },
			county: { type: null, default: null },
			subcounties: { type: null, default: null },
			treatmentLabels: { type: null, default: null },
			treatmentColors: { type: null, default: null }
		},
		data(){
			return {
				
			}
		},
		computed: {
			graphOptions(){
				// console.log(this.data)
				var categories = _.map(this.data, (o) => { return o.subcounty })
				var seriesData = []
				categories.sort()
				categories.unshift("<b>" + _.upperCase(this.county + " County") +" </b>")

				_.forOwn(this.treatmentLabels, (treatment, id) => {
					var obj = {}
					obj.name = treatment
					obj.data = []
					if (id == "NOTX") {
						obj.borderColor = "red"
					}else{
						obj.borderColor = this.treatmentColors[id]
					}
					obj.color = this.treatmentColors[id]
					_.forOwn(categories, (category, key) => {
						if(key != 0){
							var scData = _.map(this.data, function(o){
								if (o.subcounty == category) { return o}
							})

							scData = _.without(scData, undefined)
							obj.data.push(scData[0][id])
						}
						
					})

					var data = obj.data;

					var average = _.round(_.mean(data), 1)
					obj.data.unshift(average)

					seriesData.push(obj)
				})

				return {
					chart: {
				        type: 'column'
				    },

				    title: {
				        text: this.title
				    },

				    xAxis: {
				        categories: categories
				    },

				    yAxis: {
				        allowDecimals: false,
				        min: 0,
				        title: {
				            text: null
				        },
				        gridLineWidth: 0,
						minorGridLineWidth: 0,
						labels:
						{
							enabled: false
						}
				    },

				    tooltip: {
				        formatter: function () {
				            return '<b>' + this.x + '</b><br/>' +
				                this.series.name + ': ' + this.y + '<br/>' +
				                'Total: ' + this.point.stackTotal;
				        }
				    },

				    plotOptions: {
				        column: {
				            stacking: 'percent',
				            dataLabels: {
								enabled: true,
								format: "{point.percentage:.0f}%",
								color: "#000",
								borderColor: "#000"
							},
							borderWidth: 2,
							events: {
								legendItemClick: function () {
									return false; 
								}
							}
				        }
				    },

				    series: seriesData
				}
			}
		}
	}
</script>