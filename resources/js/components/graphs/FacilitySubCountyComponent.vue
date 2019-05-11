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
		name: 'FacilitySubCountyComponent',
		props: {
			title: { type: null, default: null },
			data: { type: null, default: null },
			subcounty: { type: null, default: null }
		},
		data(){
			return {
				pneumoniaTreatmentLabels: {
					NOTX: "No Treatment",
					AMOXDT: "Amox DT",
					AMOX_SYRUP: "Amox Syrup",
					CTX: "CTX",
					INJECTABLES: "Injectables",
					OXYGEN: "Oxygen",
					OTHER: "Other Treatment",
				},
				pneumoniaTreatmentColors: {
					NOTX: "#FFFFFF",
					AMOXDT: "#00B0FF",
					AMOX_SYRUP: "#66BB6A",
					CTX: "#9E9E9E",
					INJECTABLES: "#FF9800",
					OXYGEN: "#7C4DFF",
					OTHER: "#FFFF00",
					
				}
			}
		},
		computed: {
			graphOptions(){
				// console.log(this.data)
				var categories = _.map(this.data, (o) => { return o.FACILITY_TYPE })
				var seriesData = []
				categories.sort()
				categories.unshift("<b>" + _.upperCase(this.subcounty + " SubCounty") +" </b>")

				_.forOwn(this.pneumoniaTreatmentLabels, (treatment, id) => {
					var obj = {}
					obj.name = treatment
					obj.data = []
					if (id == "NOTX") {
						obj.borderColor = "red"
					}
					obj.color = this.pneumoniaTreatmentColors[id]
					_.forOwn(categories, (category, key) => {
						if(key != 0){
							var scData = _.map(this.data, function(o){
								if (o.FACILITY_TYPE == category) { return o}
							})

							scData = _.without(scData, undefined)
							obj.data.push(scData[0][id])
						}else{
							obj.data.push(0)
						}
						
					})
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
				            text: 'Cases'
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