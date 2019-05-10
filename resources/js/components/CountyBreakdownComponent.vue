<template>
	<div>
		<div class="row">
			<div class="col">
				<div class="float-left">
					<h2 class="text-primary text-uppercase text-center display-4">{{ county }} County</h2>
				</div>
				
				<div class="float-right">
					<!-- <label>Select a County</label> -->
			 		<b-form-select v-model="selectedCounty" :options="counties" :change="openNewCounty">
			 			<template slot="first">
							<option :value="null" disabled>Select County</option>
						</template>
			 		</b-form-select>
			 	</div>
			</div>
		</div>
		
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
											<!-- <h1><center>SUB COUNTY LEVEL</center></h1> -->
											<highcharts :options="pneumoniaSubCountyClassifications" style = "height: 300px;"></highcharts>
											<hr>
											<center><h2 class="mt-6">Prescription Pattern</h2></center>
											<!-- <div>
												<highcharts :options="pneumoniaSubCountyTreatmentBaseline" style = "height: 400px;"></highcharts>

												<highcharts :options="pneumoniaSubCountyTreatmentSupervision1" style = "height: 400px;"></highcharts>

												<highcharts :options="pneumoniaSubCountyTreatmentSupervision2" style = "height: 400px;"></highcharts>
											</div> -->
											<graph-component v-for="(treatmentData, assessment) in data.pneumoniaTreat" :key="assessment" :title = "assessment" :data = "treatmentData" :county="county" :treatmentLabels = "pneumoniaTreatmentLabels" :subcounties="subcounties"></graph-component>
											
										</div>
										<div class="col-5">
											<!-- <h1><center>BY LEVEL OF CARE</center></h1> -->
											<highcharts :options="pneumoniaLoCTreatmentBaseline" style = "height: 300px;"></highcharts>
											<hr>
											<center><h2 class="mt-6">Prescription Pattern</h2></center>
											<div>
												<loc-graph-component v-for="(treatmentData, assessment) in data.pneumoniaLocTreat" :key="assessment" :title = "assessment" :data = "treatmentData" :county="county" :treatmentLabels = "pneumoniaTreatmentLabels"></loc-graph-component>
												<!-- <highcharts :options="pneumoniaLoCPPBaseline" style = "height: 400px;"></highcharts>

												<highcharts :options="pneumoniaLoCPPSupervision1" style = "height: 400px;"></highcharts> -->

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
													<b-form-select v-model="pneumonia.selectedSubcounty" :options="options.subcountiesList">
														<template slot="first">
															<option :value="null" disabled>Select Sub County</option>
														</template>
													</b-form-select>
												</div>
											</div>
											<center>
												<h1>{{ this.pneumonia.selectedSubcounty }} - {{ this.facilityNo }} Facilities</h1>
											</center>

											<div class="row">
												<div class="col-7">
													<highcharts :options="facilityChart" style = "height: 400px;"></highcharts>

													<center><h2 class="mt-6">Prescription Pattern</h2></center>
													<facility-graph-component v-for="(treatmentData, assessment) in data.facilityTreatmentData" :key="assessment" :title = "assessment" :data = "treatmentData" :subcounty="pneumonia.selectedSubcounty" :treatmentLabels = "pneumoniaTreatmentLabels"></facility-graph-component>
												</div>
												<div class="col-5">
													<highcharts :options="pneumoniaFacilityClassification" style = "height: 400px;"></highcharts>
													<center><h2 class="mt-6">Prescription Pattern</h2></center>
													<facility-sub-county-component v-for="(treatmentData, assessment) in data.facilityLocData" :key="assessment" :title = "assessment" :data = "treatmentData" :subcounty="pneumonia.selectedSubcounty" :treatmentLabels = "pneumoniaTreatmentLabels"></facility-sub-county-component>
												</div>
											</div>
											
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
									<highcharts :options="diarrhoeaSubCountyClassifications" style = "height: 400px;"></highcharts>
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
	import GraphComponent from './graphs/GraphComponent'
	import LocGraphComponent from './graphs/LocGraphComponent'
	import FacilityGraphComponent from './graphs/FacilityGraphComponent'
	import FacilitySubCountyComponent from './graphs/FacilitySubCountyComponent'

	exportingInit(Highcharts)
	export default {
		props: {
			county: { type: null, default: null }
		},
		components: { GraphComponent, LocGraphComponent, FacilityGraphComponent, FacilitySubCountyComponent },
		data(){
			return {
				counties: [],
				subcounties: [],
				selectedCounty: null,
				facilityNo: 0,
				pneumoniaColor: "#66BB6A",
				pneumoniaSubCounties: [],
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
					subcountiesList: [],
					graphTypes: [{ text: 'Line Chart', value: 'line' },
								{ text: 'Column Chart', value: 'column' }],
					pneumoniaFacilityTypes: []
				},
				facilityChart: {},
				data: {
					pneumoniaClass: [],
					pneumoniaLocClass: [],
					pneumoniaTreat: {},
					pneumoniaLocTreat: {},
					diarrhoeaClass: [],
					facilityTypes: [],
					xfacilityTypes: [],
					facilityTreatmentData: [],
					pneumoniaFacilityTreat: [],
					facilityLocData: []
				}
			}
		},
		created(){
			this.getCounties()
			this.getSubCounties()
			this.getPneumoniaClassificationData()
			this.getPneumoniaTreatmentData()
			this.getPneumoniaLocClassificationData()
			this.getPneumoniaLocTreatmentData()
			this.getDiarrhoeaClassifications()
		},
		methods: {
			getCounties(){
				axios.get('/api/data/counties')
				.then(res => {
					this.counties = _.map(res.data, o => ({
						value: o.county,
						text: o.county
					}))
				})
			},
			openNewCounty(){
				alert("clicked")
			},
			getSubCounties(){
				axios.get('/api/data/subcounties/' + this.county)
				.then(res => {
					this.options.subcountiesList = _.map(res.data, subcounty => ({
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
						if(typeof pneumoniaClassData[data.assessment] == 'undefined'){
							pneumoniaClassData[data.assessment] = {}
						}

						pneumoniaClassData[data.assessment][data.sub_county] = _.round((data.TOTAL_CLASSIFIED / data.TOTAL_CASES_AFTER_DIF) * 100)
					})
					// console.log(pneumoniaClassData)

					this.data.pneumoniaClass = pneumoniaClassData
					this.pneumoniaSubCounties = [{sub_county: "<b>" + _.upperCase(this.county + " County") +" </b>"}]
					var subcounties = _.map(res.data, subcounty => {
						var sc = {"sub_county": subcounty.sub_county}
						if(!_.some(this.pneumoniaSubCounties, sc)){
							this.pneumoniaSubCounties.push(sc)
						}
						
					})
					// console.log(this.subcounties)
				})
			},
			getPneumoniaLocClassificationData(){
				axios.get('/api/data/pneumonia/classification/loc/' + this.county)
				.then(res => {
					var pneumoniaLocClassData = {}
					var facility_type = []
					_.forOwn(res.data, data =>{
						if(typeof pneumoniaLocClassData[data.assessment] == "undefined"){
							pneumoniaLocClassData[data.assessment] = {};
						}

						var ftype = (data.FACILITY_TYPE == null) ? "Unknown" : data.FACILITY_TYPE
						pneumoniaLocClassData[data.assessment][ftype] = _.round((data.TOTAL_CLASSIFIED / data.TOTAL_CASES_AFTER_DIF) * 100)
						

						facility_type.push(ftype)
					})
					// console.log(pneumoniaClassData)
					// console.log(pneumoniaLocClassData)
					this.data.pneumoniaLocClass = pneumoniaLocClassData
					facility_type = _.uniq(facility_type)
					this.data.facilityTypes = facility_type
				})
			},
			getPneumoniaLocTreatmentData(){
				axios.get('/api/data/pneumonia/treatment/loc/' + this.county)
				.then(res => {
					this.data.pneumoniaLocTreat = res.data
				})
			},
			getDiarrhoeaClassifications(){
				axios.get('/api/data/diarrhoea/classification/subcounties/' + this.county)
				.then(res => {
					this.data.diarrhoeaClass = res.data
				})
			},
			getPneumoniaFacilityTreatmentData(subcounty){
				axios.get('/api/data/pneumonia/treatment/facilities/' + subcounty)
				.then(res => {
					this.data.facilityTreatmentData = res.data
				})
			},
			getFacilityClassificationData(subcounty){
				axios.get('/api/data/pneumonia/classification/facility/' + subcounty)
				.then(res => {
					var pneumoniaLocClassData = {}
					var facility_type = []
					_.forOwn(res.data, data =>{
						if(typeof pneumoniaLocClassData[data.assessment] == "undefined"){
							pneumoniaLocClassData[data.assessment] = {};
						}

						var ftype = (data.FACILITY_TYPE == null) ? "Unknown" : data.FACILITY_TYPE
						pneumoniaLocClassData[data.assessment][ftype] = _.round((data.TOTAL_CLASSIFIED / data.TOTAL_CASES_AFTER_DIF) * 100)
						

						facility_type.push(ftype)
					})
					// console.log(pneumoniaClassData)
					// console.log(pneumoniaLocClassData)
					this.data.pneumoniaFacilityTreat = pneumoniaLocClassData
					facility_type = _.uniq(facility_type)
					this.data.xfacilityTypes = facility_type
					// this.data.pneumoniaFacilityTreat = res.data
				})
			},
			getFacilityLocTreatementData(subcounty){
				axios.get('/api/data/pneumonia/treatment/facilitydata/loc/' + subcounty)
				.then(res => {
					this.data.facilityLocData = res.data
				})
			}
		},
		watch: {
			'pneumonia.selectedSubcounty': function(newVal, oldVal){
				axios.get("/api/data/pneumonia/classification/facility/" + newVal)
				.then(res => {
					var categories = []
					var seriesData = []
					var resData = []
					var cat = _.uniq(_.map(res.data, (o) => { return o.assessment }))

					var categories = _.map(res.data, (o) => {
						return o.facility_name
					})

					this.facilityNo = categories.length
					categories.sort()

					_.forOwn(res.data, (value) => {
						if(typeof resData[value.assessment] === 'undefined'){
							resData[value.assessment]= []
						}
						resData[value.assessment][value.facility_name] = _.round((value.TOTAL_CLASSIFIED / value.TOTAL_CASES_AFTER_DIF) * 100)
					})

					// console.log(resData)

					_.forOwn(cat, (category) => {
						// console.log(category)
						var obj = {};
						obj.name = category
						obj.data = []
						obj.color = this.pneumoniaColor
						_.forOwn(categories, (facility) => {
							// console.log(facility)
							var data = 0
							if (typeof resData[category][facility] != "undefined") {
								data = resData[category][facility]
							}
							obj.data.push(data)
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
					                this.series.name + ': ' + this.y + '%<br/>'
					        }
					    },

					    plotOptions: {
					        column: {
					            stacking: 'normal',
					            dataLabels: {
									enabled: true,
									color: "#000",
									borderColor: "#000",
									format: "{point.y}%"
								},
								pointPadding: 0.2,
	            				borderWidth: 0
					        }
					    },

					    series: seriesData
					}
				})
				.catch(error =>{
					console.error(error)
					alert("There was an error");
				})

				this.getPneumoniaFacilityTreatmentData(newVal)
				this.getFacilityClassificationData(newVal)
				this.getFacilityLocTreatementData(newVal)
			},
			selectedCounty: function(county){
				window.location.href = "/dashboard/county/breakdown/" + county
			}
		},
		computed: {
			diarrhoeaSubCountyClassifications() {

			},
			pneumoniaSubCountyClassifications(){
				// Order by the third bar classified
				var cat = Object.keys(this.data.pneumoniaClass);
				var categories = _.uniq(_.map(this.pneumoniaSubCounties, (o) => { return o.sub_county }))
				categories.sort()
				// console.log(categories)
				var seriesData = []
				var resData = []

				// var cat = this.categories
				// cat.splice(3, 2)
				// console.log(this.data.pneumoniaClass)
				
				_.forOwn(cat, (category) => {
					var obj = {};
					obj.name = category
					obj.data = []
					obj.color = this.pneumoniaColor
					_.forOwn(this.pneumoniaSubCounties, (subcounty, k) => {
						if(k != 0){
							if(typeof this.data.pneumoniaClass[category][subcounty.sub_county] == "undefined"){
								obj.data.push(0)
							}else{
								obj.data.push(this.data.pneumoniaClass[category][subcounty.sub_county])
							}
						}else{
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
				                this.series.name + ': ' + this.y + '%<br/>'
				        }
				    },

				    plotOptions: {
				        column: {
				   //          stacking: 'percent',
				            dataLabels: {
								enabled: true,
								color: "#000",
								borderColor: "#000",
								format: "{point.y}%"
							},
							pointPadding: 0.2,
            				borderWidth: 0
				        }
				    },

				    series: seriesData
				}
			},
			pneumoniaFacilityClassification(){
				var categories = ["<b>" + _.upperCase(this.subcounty) + " SUB COUNTY" + "</b>",];
				categories = categories.concat(this.data.xfacilityTypes)
				var seriesData = [];
				var cat = Object.keys(this.data.pneumoniaFacilityTreat)
				_.forOwn(cat, (category) => {
					var obj = {};
					obj.name = category
					obj.data = []
					obj.color = this.pneumoniaColor
					_.forOwn(categories, (ftype, k) => {
						if(k != 0){
							if(typeof this.data.pneumoniaLocClass[category][ftype] == "undefined"){
								obj.data.push(0)
							}else{
								obj.data.push(this.data.pneumoniaLocClass[category][ftype])
							}
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
				        text: 'Pneumonia Case Classification by Level of Care'
				    },
				    xAxis: {
				        categories: categories,
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
				            '<td style="padding:0"><b>{point.y}%</b></td></tr>',
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
								borderColor: "#000",
								format: "{point.y}%"
							},
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
							if (o.sub_county == category) { return o}
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
				var categories = ["<b>" + _.upperCase(this.county) + " COUNTY" + "</b>",];
				categories = categories.concat(this.data.facilityTypes)
				var seriesData = [];
				var cat = Object.keys(this.data.pneumoniaLocClass)
				_.forOwn(cat, (category) => {
					var obj = {};
					obj.name = category
					obj.data = []
					obj.color = this.pneumoniaColor
					_.forOwn(categories, (ftype, k) => {
						if(k != 0){
							if(typeof this.data.pneumoniaLocClass[category][ftype] == "undefined"){
								obj.data.push(0)
							}else{
								obj.data.push(this.data.pneumoniaLocClass[category][ftype])
							}
						}else{
							obj.data.push(0)
						}
					})

					seriesData.push(obj)
				})

				// console.log(seriesData)
				
				_.forOwn(this.data.pneumoniaLocClass, (value, key) =>{
					// categories.push(key)
					// seriesData.push(value)
				})
					
				return {
				    chart: {
				        type: 'column'
				    },
				    title: {
				        text: 'Pneumonia Case Classification by Level of Care'
				    },
				    xAxis: {
				        categories: categories,
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
				            '<td style="padding:0"><b>{point.y}%</b></td></tr>',
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
								borderColor: "#000",
								format: "{point.y}%"
							},
				        }
				    },
				    series: seriesData
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
						// obj.data.push(_.random(1, 20))
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
				var categories = _.map(this.data.pneumoniaLocTreat, (o) => { return o.FACILITY_TYPE })
				var specData = []
				// console.log(categories)
				_.forOwn(categories, (category) => {
					_.forOwn(this.data.pneumoniaLocTreat, (value) => {
						if (category == value.FACILITY_TYPE) {
							delete value["FACILITY_TYPE"]
							specData[category] = value
						}
					})
				})

				// console.log(specData)
				

				categories.unshift("<b>" + _.upperCase(this.county + " County") +" </b>")
				var seriesData = []
				
				var stacks = ["baseline", "supervision1", "supervision2"]

				

				_.forOwn(this.pneumoniaTreatmentLabels, (treatment, key) => {
					var obj = {}
					obj.name = treatment
					obj.data = [0]
					// _.forOwn(categories, category => {
					// 	if(typeof specData[category] != undefined)
					// 		obj.data.push(specData[category][key])
					// })
					_.forOwn(specData, (val) => {
						obj.data.push(val[key])
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