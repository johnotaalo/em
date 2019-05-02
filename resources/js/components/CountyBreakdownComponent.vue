<template>
	<div>
		<h2 class="text-primary text-uppercase text-center display-4">{{ county }} County</h2>
		<div class="row">
			<div class="col">
				<b-card no-body>
					<b-tabs pills card>
						<b-tab active>
							 <template slot="title">
							 	<h5>
							 		PNEUMONIA
							 	</h5>
							 </template>
							 <b-tabs pills>
							 	<b-tab>
							 		<template slot="title">
									 	<h6>
											SUB COUNTY LEVEL
										</h6>
									</template>
							 		<div class="row">
										<div class="col-7 border border-left-0 border-top-0 border-bottom-0" >
											<h1><center>SUB COUNTY LEVEL</center></h1>
											<highcharts :options="pneumoniaSubCountyClassifications" style = "height: 300px;"></highcharts>
											<hr>
											<center><h2 class="mt-6">Prescription Pattern</h2></center>
											<div>
												<highcharts :options="pneumoniaSubCountyTreatmentBaseline" style = "height: 400px;"></highcharts>

												<highcharts :options="pneumoniaSubCountyTreatmentSupervision1" style = "height: 400px;"></highcharts>

												<!-- <highcharts :options="pneumoniaSubCountyTreatmentSupervision2" style = "height: 400px;"></highcharts> -->
											</div>
											
										</div>
										<div class="col-5">
											<h1><center>BY LEVEL OF CARE</center></h1>
											<highcharts :options="pneumoniaLoCTreatmentBaseline" style = "height: 300px;"></highcharts>
											<hr>
											<center><h2 class="mt-6">Prescription Pattern</h2></center>
											<div>
												<highcharts :options="pneumoniaLoCPPBaseline" style = "height: 400px;"></highcharts>

												<highcharts :options="pneumoniaLoCPPSupervision1" style = "height: 400px;"></highcharts>

												<!-- <highcharts :options="pneumoniaLoCPPSupervision2" style = "height: 400px;"></highcharts> -->
											</div>
											<!-- <highcharts :options="pneumoniaSubCountyClassifications" style = "height: 400px;"></highcharts> -->
										</div>
									<!-- <div class="col">
										<highcharts :options="pneumoniaClassificationTrend" style = "height: 400px;"></highcharts>
									</div> -->
									</div>
							 	</b-tab>
							 	<b-tab>
							 		<template slot="title">
									 	<h6>
											FACILITY LEVEL
										</h6>
									</template>
							 		<div class="row">
										<div class="col">
											<div class="row mb-3 mt-2">
												<div class="col-4">
													<b-form-select v-model="pneumonia.selectedSubcounty" :options="options.subcounties">
														<template slot="first">
															<option :value="null" disabled>Select Sub County</option>
														</template>
													</b-form-select>
												</div>
											</div>
											
											<highcharts :options="facilityChart" style = "height: 500px;"></highcharts>
										</div>
									</div>
							 	</b-tab>
							 </b-tabs>
							

							
						</b-tab>
						<b-tab>
							<template slot="title">
							 	<h5>
									DIARRHOEA
								</h5>
							</template>
							<div class="row">
								<div class="col">
									<highcharts :options="pneumoniaSubCountyClassifications" style = "height: 400px;"></highcharts>
								</div>
							</div>
						</b-tab>
					</b-tabs>
				</b-card>
			</div>
		</div>
		<!--  -->
	</div>
</template>

<script type="text/javascript">
	import Highcharts from 'highcharts'
	import exportingInit from 'highcharts/modules/exporting'

	exportingInit(Highcharts)
	export default {
		props: {
			county: { type: null, default: null }
		},
		data(){
			return {
				subcounties: [],
				pneumoniaTreatmentLabels: {NOTX: "No Treatment",
					AMOXDT: "Amox DT",
					AMOX_SYRUP: "Amox Syrup",
					CTX: "CTX",
					INJECTABLES: "Injectables",
					OXYGEN: "Oxygen",
					OTHER: "Other Treatment",
				},
				categories : ["Facility Supervision 2018", "Baseline"],
				pneumonia: {
					selectedSubcounty: null,
					selectedChart: 'column',
				},
				options: {
					subcounties: [],
					graphTypes: [{ text: 'Line Chart', value: 'line' },
								{ text: 'Column Chart', value: 'column' }]
				},
				facilityChart: {},
				data: {
					pneumoniaClass: [],
					pneumoniaTreat: {}
				}
			}
		},
		created(){
			// this.getSubCounties()
			this.getPneumoniaClassificationData()
			this.getPneumoniaTreatmentData()
		},
		methods: {
			getSubCounties(){
				axios.get('/api/data/subcounties/' + this.county)
				.then(res => {
					this.options.subcounties = _.map(res.data, subcounty => ({
						value: subcounty.sub_county,
						text: subcounty.sub_county
					}))

					this.subcounties = [{sub_county: "<b>" + _.upperCase(this.county + " County") +" </b>"}];
					_.forOwn(res.data, (subcounty) => {
						this.subcounties.push(subcounty)
					})
				})
			},
			getFacilities(){
				
			},
			getPneumoniaTreatmentData(){
				axios.get('/api/data/pneumonia/treatment/subcounty/' + this.county)
				.then(res => {
					this.data.pneumoniaTreat = res.data
				})
			},
			getPneumoniaClassificationData(){
				axios.get('/api/data/pneumonia/classification/subcounty/' + this.county)
				.then(res => {
					var pneumoniaClassData = {}
					_.forOwn(res.data, data =>{
						pneumoniaClassData[data.sub_county] = data.TOTAL_CASES_AFTER_DIF
					})
					// console.log(pneumoniaClassData)

					this.data.pneumoniaClass = pneumoniaClassData
					this.subcounties = [{sub_county: "<b>" + _.upperCase(this.county + " County") +" </b>"}]
					var subcounties = _.map(res.data, subcounty => {
						this.subcounties.push({"sub_county": subcounty.sub_county})
					})
					// console.log(this.subcounties)
				})
			}
		},
		watch: {
			'pneumonia.selectedSubcounty': function(newVal, oldVal){
				axios.get('/api/data/facilities/' + newVal)
				.then(res => {
					var categories = []
					var seriesData = []
					var categories = _.map(res.data, (o) => {
						return o.facility_name
					})

					var cat = this.categories
					cat.splice(3, 2)
					
					_.forOwn(cat, (category) => {
						var obj = {};
						obj.name = category
						obj.data = []
						_.forOwn(categories, (subcounty) => {
							obj.data.push(_.random(1, 20))
						})

						seriesData.push(obj)
					})

					this.facilityChart = {

					    chart: {
					        type: this.pneumonia.selectedChart
					    },

					    title: {
					        text: ' Pneumonia Case Classification'
					    },

					    subtitle: {
					    	title: newVal
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
					                this.series.name + ': ' + this.y + '<br/>'
					        }
					    },

					    plotOptions: {
					        column: {
					            stacking: 'normal',
					            dataLabels: {
									enabled: true,
									color: "#000",
									borderColor: "#000"
								},
								pointPadding: 0.2,
	            				borderWidth: 0
					        }
					    },

					    series: seriesData
					}
				})
				.catch(error =>{
					alert("There was an error");
				})
			}
		},
		computed: {
			pneumoniaSubCountyClassifications(){
				// Order by the third bar classified
				var categories = _.map(this.subcounties, (o) => { return o.sub_county })
				console.log(categories)
				var seriesData = []

				var cat = this.categories
				cat.splice(3, 2)
				
				_.forOwn(cat, (category) => {
					var obj = {};
					obj.name = category
					obj.data = []
					_.forOwn(this.subcounties, (subcounty, k) => {
						if(category != "Baseline" && k != 0){
							obj.data.push(this.data.pneumoniaClass[subcounty.sub_county])
						}else if(k == 0){
							obj.data.push(0)
						}
						// obj.data.push(_.random(1, 20))
					})

					seriesData.push(obj)
				})

				return {

				    chart: {
				        type: 'column'
				    },

				    title: {
				        text: 'Pneumonia Case Classification'
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
				                this.series.name + ': ' + this.y + '<br/>'
				        }
				    },

				    plotOptions: {
				        column: {
				   //          stacking: 'percent',
				            dataLabels: {
								enabled: true,
								color: "#000",
								borderColor: "#000"
							},
							pointPadding: 0.2,
            				borderWidth: 0
				        }
				    },

				    series: seriesData
				}
			},
			pneumoniaClassificationTrend(){
				var categories = this.categories
					var seriesData = []

					_.forOwn(this.subcounties, (subcounty) => {
						var obj = {};
						obj.name = subcounty.sub_county
						obj.data = []
						_.forOwn(categories, category => {
							obj.data.push(_.random(1, 20))
						})
						seriesData.push(obj)
					})


					return {

					    chart: {
					        type: 'line'
					    },

					    title: {
					        text: 'Classification'
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
					            stacking: 'normal',
					        }
					    },

					    series: seriesData
					}
			},
			pneumoniaSubCountyTreatmentBaseline(){
				var categories = _.map(this.subcounties, (o) => { return o.sub_county })
				var seriesData = []
				var stacks = ["baseline", "supervision1", "supervision2"]

				// _.forOwn(this.pneumoniaTreatmentLabels, (treatment) => {
				// 	var obj = {}
				// 	obj.name = treatment
				// 	obj.data = []
				// 	_.forOwn(categories, category => {
				// 		obj.data.push(_.random(1, 20))
				// 	})
				// 	seriesData.push(obj)
				// })
				
				
				return {
					chart: {
				        type: 'column'
				    },

				    title: {
				        text: 'Baseline'
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
				        }
				    },

				    series: seriesData
				}
			},
			pneumoniaSubCountyTreatmentSupervision1(){
				var categories = _.map(this.data.pneumoniaTreat, (o) => { return o.sub_county })
				categories.unshift("<b>" + _.upperCase(this.county + " County") +" </b>")
				var seriesData = []
				var stacks = ["baseline", "supervision1", "supervision2"]

				_.forOwn(this.pneumoniaTreatmentLabels, (treatment, id) => {
					var obj = {}
					obj.name = treatment
					obj.data = []
					_.forOwn(categories, (category, key) => {
						if(key != 0){
							var scData = _.map(this.data.pneumoniaTreat, function(o){
								if (o.sub_county == category) { return o}
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
				        text: 'Facility Supervision 2018'
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
				        }
				    },

				    series: seriesData
				}
			},
			pneumoniaSubCountyTreatmentSupervision2(){
				var categories = _.map(this.subcounties, (o) => { return o.sub_county })
				var seriesData = []
				var stacks = ["baseline", "supervision1", "supervision2"]

				_.forOwn(this.pneumoniaTreatmentLabels, (treatment) => {
					var obj = {}
					obj.name = treatment
					obj.data = []
					_.forOwn(categories, category => {
						var scData = _.map(this.data.pneumoniaTreat, function(o){
							if (o.sub_county == category) { console.log(o); return o}
						})
						obj.data.push(_.random(1, 20))
					})
					seriesData.push(obj)
				})
				
				
				return {
					chart: {
				        type: 'column'
				    },

				    title: {
				        text: 'Facility Supervision 2019'
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
				        }
				    },

				    series: seriesData
				}
			},
			pneumoniaLoCTreatmentBaseline(){
				return {
				    chart: {
				        type: 'column'
				    },
				    title: {
				        text: 'Pneumonia Case Classification by Level of Care'
				    },
				    xAxis: {
				        categories: [
				        	"<b>" + _.upperCase(this.county) + " COUNTY" + "</b>",
				            'County Hospital',
				            'Sub County Hospital',
				            'Health Center',
				            'Dispensary',
				        ],
				        crosshair: true
				    },
				    yAxis: {
				        min: 0,
				        title: {
				            text: 'Cases'
				        },allowDecimals: false,
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
				        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
				        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
				            '<td style="padding:0"><b>{point.y}</b></td></tr>',
				        footerFormat: '</table>',
				        shared: true,
				        useHTML: true
				    },
				    plotOptions: {
				        column: {
				            pointPadding: 0.2,
				            borderWidth: 0,
				        dataLabels: {
								enabled: true,
								color: "#000",
								borderColor: "#000"
							},
				        }
				    },
				    series: [{
				        name: 'Baseline',
				        data: [49.9, 71.5, 106.4, 129.2, 144.0]

				    }, {
				        name: 'Facility Supervision 2018',
				        data: [83.6, 78.8, 98.5, 93.4, 106.0]

				    }, {
				        name: 'Facility Supervision 2019',
				        data: [48.9, 38.8, 39.3, 41.4, 47.0]

				    }]
				}
			},
			pneumoniaLoCPPBaseline(){
				var categories = ["<b>" + _.upperCase(this.county) + " COUNTY" + "</b>",
				            'County Hospital',
				            'Sub County Hospital',
				            'Health Center',
				            'Dispensary']
				var seriesData = []
				var stacks = ["baseline", "supervision1", "supervision2"]

				_.forOwn(this.pneumoniaTreatmentLabels, (treatment) => {
					var obj = {}
					obj.name = treatment
					obj.data = []
					_.forOwn(categories, category => {
						obj.data.push(_.random(1, 20))
					})
					seriesData.push(obj)
				})
				
				
				return {
					chart: {
				        type: 'column'
				    },

				    title: {
				        text: 'Baseline'
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
				        }
				    },

				    series: seriesData
				}
			},
			pneumoniaLoCPPSupervision1(){
				var categories = ["<b>" + _.upperCase(this.county) + " COUNTY" + "</b>",
				            'County Hospital',
				            'Sub County Hospital',
				            'Health Center',
				            'Dispensary']
				var seriesData = []
				var stacks = ["baseline", "supervision1", "supervision2"]

				_.forOwn(this.pneumoniaTreatmentLabels, (treatment) => {
					var obj = {}
					obj.name = treatment
					obj.data = []
					_.forOwn(categories, category => {
						obj.data.push(_.random(1, 20))
					})
					seriesData.push(obj)
				})
				
				
				return {
					chart: {
				        type: 'column'
				    },

				    title: {
				        text: 'Facility Supervision 2018'
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
				        }
				    },

				    series: seriesData
				}
			},
			pneumoniaLoCPPSupervision2(){
				var categories = ["<b>" + _.upperCase(this.county) + " COUNTY" + "</b>",
				            'County Hospital',
				            'Sub County Hospital',
				            'Health Center',
				            'Dispensary']
				var seriesData = []
				var stacks = ["baseline", "supervision1", "supervision2"]

				_.forOwn(this.pneumoniaTreatmentLabels, (treatment) => {
					var obj = {}
					obj.name = treatment
					obj.data = []
					_.forOwn(categories, category => {
						obj.data.push(_.random(1, 20))
					})
					seriesData.push(obj)
				})
				
				
				return {
					chart: {
				        type: 'column'
				    },

				    title: {
				        text: 'Facility Supervision 2019'
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
				        }
				    },

				    series: seriesData
				}
			},
		}
	}
</script>