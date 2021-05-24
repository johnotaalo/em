<template>
	<div>
		<div class="row">
			<div class="col">
				<div class="float-left">

				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md">
				<h4>
					<span class="text-uppercase text-center display-3">{{ county }} County</span><br/>
					<span class="text-muted">{{ selectedAssessment }}</span>
				</h4>
			</div>
			<div class="col-md">
				<div class="">
					<label>Select a County</label>
			 		<b-form-select v-model="selectedCounty" :options="counties" :change="openNewCounty" size="sm">
			 			<template slot="first">
							<option :value="null" disabled>Select County</option>
						</template>
			 		</b-form-select>
			 	</div>
			</div>

			<div class="col-md">
				<b-form-group>
					<label>Select an Assessment ({{ assessmentOptions.length }} Available)</label>
					<b-select
					id="radio-group-1"
					v-model="selectedAssessment"
					:options="assessmentOptions"
					name="radio-options"
					size="sm"
					></b-select>
				</b-form-group>
			</div>
		</div>

		<div class="row" v-if="!error">
			<div class="col">
				<b-card no-body>
					<b-tabs pills card>
						<b-tab active>
							<template slot="title">
							 	<h5>
							 		COUNTY OVERVIEW
							 	</h5>
							 </template>

							<div class="row">
								<div class="col-md">
									<h6 class="text-uppercase text-muted mb-2">
										Subcounties
									</h6>
									<span class="h2 mb-0">
										{{ options.subcountiesList.length }}
									</span>
								</div>

								<div class="col-md">
									<h6 class="text-uppercase text-muted mb-2">
										Pneumonia Cases Assessed
									</h6>
									<span class="h2 mb-0">
										{{ pneumoniaTotals.cases_after_dif.toLocaleString('en') }}
									</span>
								</div>

								<div class="col-md">
									<h6 class="text-uppercase text-muted mb-2">
										Diarrhoea Cases Assessed
									</h6>
									<span class="h2 mb-0">
										{{ diarrhoeaTotals.cases_after_dif.toLocaleString('en') }}
									</span>
								</div>
							</div>
							<hr/>

							<div class="row">
								<!-- <div class="col-md">
									<div class="mb-5">
										<h4 class="header-pretitle">
										Sub Counties
										</h4>
										<h1 class="display-2">
										{{ options.subcountiesList.length }}
										</h1>
										<h4 class="header-pretitle">Cases Assessed</h4>
										<div class="mb-3">
											<h6 class = "header-pretitle">Pneumonia</h6>
											<h3>{{ pneumoniaTotals.cases_after_dif.toLocaleString('en') }}</h3>
										</div>
										<div class="mb-3">
											<h6 class = "header-pretitle">Diarrhoea</h6>
											<h3>{{ diarrhoeaTotals.cases_after_dif.toLocaleString('en') }}</h3>
										</div>
									</div>
								</div> -->
								<div class="col-md-5">
									<highcharts :options="facilityDistributionChart" style="height: 500px;"></highcharts>
								</div>


								<div class="col-md-6">
									<center><h2>Classification</h2></center>
									<div class="row">
										<div class="col-lg-6">
											<gauge-component :gdata="pneumoniaCountyClassification" title="Pneumonia"></gauge-component>
										</div>
										<div class="col-lg-6">
											<gauge-component :gdata="diarrhoeaCountyClassification" title="Diarrhoea"></gauge-component>
										</div>
									</div>
									<center><h2>Treatment</h2></center>
									<div class="row">
										<div class="col-lg-6">
											<donut-treatment-donut title = "Pneumonia" :gdata="pneumoniaDonutData" :labels = "pneumonia.treatmentLabels" :colors="pneumonia.colors"></donut-treatment-donut>
										</div>
										<div class="col-lg-6">
											<donut-treatment-donut title = "Diarrhoea" :gdata="diarrhoeaDonutData" :labels = "diarrhoea.treatmentLabels" :colors="diarrhoea.colors"></donut-treatment-donut>
										</div>
									</div>

								</div>
							</div>
						</b-tab>
						<b-tab>
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
											<graph-component :title = "selectedAssessment" :data = "subcountyPneumoniaTreatment" :county="county" :treatmentLabels = "pneumoniaTreatmentLabels" :subcounties="subcounties"></graph-component>
											<!-- <graph-component v-for="(treatmentData, assessment) in data.pneumoniaTreat" :key="assessment" :title = "assessment" :data = "treatmentData" :county="county" :treatmentLabels = "pneumoniaTreatmentLabels" :subcounties="subcounties"></graph-component> -->

										</div>
										<div class="col-5">
											<!-- <h1><center>BY LEVEL OF CARE</center></h1> -->
											<highcharts :options="pneumoniaLoCTreatmentBaseline" style = "height: 300px;"></highcharts>
											<hr>
											<center><h2 class="mt-6">Prescription Pattern</h2></center>
											<div>
												<loc-graph-component :title = "selectedAssessment" :data = "subcountyLOCPneumoniaTreatment" :county="county" :treatmentLabels = "pneumoniaTreatmentLabels"></loc-graph-component>
												<!-- <loc-graph-component v-for="(treatmentData, assessment) in data.pneumoniaLocTreat" :key="assessment" :title = "assessment" :data = "treatmentData" :county="county" :treatmentLabels = "pneumoniaTreatmentLabels"></loc-graph-component> -->
											</div>
										</div>
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
													<facility-graph-component :title = "selectedAssessment" :data = "data.facilityTreatmentData" :subcounty="pneumonia.selectedSubcounty" :treatmentLabels = "pneumoniaTreatmentLabels"></facility-graph-component>
												</div>
												<div class="col-5">
													<highcharts :options="pneumoniaLOCChart" style = "height: 400px;"></highcharts>
													<center><h2 class="mt-6">Prescription Pattern</h2></center>
													<facility-sub-county-component :title = "selectedAssessment" :data = "data.facilityLocData" :county="county" :subcounties="subcounties" :subcounty="pneumonia.selectedSubcounty"></facility-sub-county-component>
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
							<b-tabs pills>
								<b-tab>
									<template slot="title">
									 	<h6>
											SUB COUNTY LEVEL
										</h6>
									</template>
									<div class="row">
										<div class="col-7">
											<highcharts :options="diarrhoeaSubCountyClassifications" style = "height: 400px;"></highcharts>

											<h4><center>Prescription Pattern</center></h4>
											<diarrhoea-subcounty-treatment :data="subcountyDiarrhoeaTreatment" :title = "selectedAssessment" :county="county" :treatmentLabels = "diarrhoea.treatmentLabels" :subcounties="subcounties" :treatmentColors="diarrhoea.colors"></diarrhoea-subcounty-treatment>
										</div>

										<div class="col-5">
											<highcharts :options="diarrhoeaLoCTreatment" style = "height: 400px;"></highcharts>

											<h4><center>Prescription Pattern</center></h4>
											<!-- <diarrhoea-loc-treatments v-for="(treatmentData, assessment) in data.diarrhoeaLocTreat" :key="assessment" :title = "assessment" :data = "treatmentData" :county="county" :treatmentLabels = "diarrhoea.treatmentLabels" :treatmentColors="diarrhoea.colors"></diarrhoea-loc-treatments> -->

											<diarrhoea-loc-treatments :data="subcountyLOCDiarrhoeaTreatment" :title = "selectedAssessment" :county="county" :treatmentLabels = "diarrhoea.treatmentLabels" :subcounties="subcounties" :treatmentColors="diarrhoea.colors"></diarrhoea-loc-treatments>
										</div>
									</div>
								</b-tab>

								<b-tab>
									<template slot = "title">
										<h6>FACILITY LEVEL</h6>
									</template>
									<div class="row">
										<div class="col">
											<div class="row mb-3 mt-2">
												<div class="col-4">
													<b-form-select v-model="diarrhoea.selectedSubcounty" :options="options.subcountiesList">
														<template slot="first">
															<option :value="null" disabled>Select Sub County</option>
														</template>
													</b-form-select>
												</div>
											</div>
											<center>
												<h1>{{ diarrhoea.selectedSubcounty }} - {{ diarrhoea.facilityNo }} Facilities</h1>
											</center>

											<div class="row">
												<div class="col-7">
													<highcharts :options="xfacilityChart" style = "height: 400px;"></highcharts>

													<center><h2 class="mt-6">Prescription Pattern</h2></center>
													<diarrhoea-facility-prescription-pattern :title = "selectedAssessment" :data = "diarrhoea.facilityTreatmentData" :subcounty="diarrhoea.selectedSubcounty" :treatmentLabels = "diarrhoea.treatmentLabels" :treatmentColors="diarrhoea.colors"></diarrhoea-facility-prescription-pattern>
												</div>
												<div class="col-5">
													<highcharts :options="diarrhoea.diarrhoeaLocClass" style = "height: 400px;"></highcharts>
													<center><h2 class="mt-6">Prescription Pattern</h2></center>
													<diarrhoea-loc-prescription-pattern :title = "selectedAssessment" :data = "diarrhoea.locTreatmentData" :county="county" :subcounties="subcounties" :subcounty="diarrhoea.selectedSubcounty" :treatmentLabels = "diarrhoea.treatmentLabels" :treatmentColors="diarrhoea.colors"></diarrhoea-loc-prescription-pattern>
												</div>
											</div>

										</div>
									</div>
								</b-tab>
							</b-tabs>

						</b-tab>
					</b-tabs>
				</b-card>
			</div>
		</div>
		<div v-else>
			<no-data-component></no-data-component>
		</div>
		<!--  -->
	</div>
</template>

<script type="text/javascript">
	import Highcharts from 'highcharts'
	import exportingInit from 'highcharts/modules/exporting'
	import GaugeComponent from './graphs/types/GaugeComponent'
	import DonutTreatmentDonut from './graphs/types/DonutTreatmentDonut'
	import GraphComponent from './graphs/GraphComponent'
	import LocGraphComponent from './graphs/LocGraphComponent'
	import FacilityGraphComponent from './graphs/FacilityGraphComponent'
	import FacilitySubCountyComponent from './graphs/FacilitySubCountyComponent'
	import DiarrhoeaSubcountyTreatment from './graphs/DiarrhoeaSubcountyTreatment'
	import DiarrhoeaLocTreatments from './graphs/DiarrhoeaLocTreatments'
	import DiarrhoeaFacilityPrescriptionPattern from  './graphs/DiarrhoeaFacilityPrescriptionPattern'
	import DiarrhoeaLocPrescriptionPattern from './graphs/DiarrhoeaLocPrescriptionPattern'

	import NoDataComponent from './common/NoDataComponent'

	exportingInit(Highcharts)

	export default {
		props: {
			id: { type: null, default: null },
			county: { type: null, default: null },
			assessments: { type: null, default: null },
			facilitydistribution: { type: null, default: null },
			facilities: { type: null, default: null },
			diarrhoeatotals: { type: null, default: null },
			pneumoniatotals: { type: null, default: null },
			legacydata: { type: null, default: null },
			error: { type: null, default: null }
		},
		components: { NoDataComponent, GraphComponent, LocGraphComponent, FacilityGraphComponent, FacilitySubCountyComponent, DiarrhoeaSubcountyTreatment, DiarrhoeaLocTreatments, DiarrhoeaFacilityPrescriptionPattern, DiarrhoeaLocPrescriptionPattern, GaugeComponent, DonutTreatmentDonut },
		data(){
			return {
				selectedAssessment: "",
				counties: [],
				subcounties: [],
				selectedCounty: null,
				facilityNo: 0,
				pneumoniaColor: "#66BB6A",
				diarrhoeaColor: "#03A9F4",
				notclassifiedColor: {
					border: "red",
					color: "#ffffff"
				},
				colors: {
					'DISPENSARY'	: '#FFD600',
					'HOSPITAL'		: '#FF6D00',
					'HEALTH CENTRE'	: '#2962FF'
				},
				pneumoniaSubCounties: [],
				diarrhoeaSubCounties: [],
				diarrhoeaSubCountiesX: [],
				pneumoniaTreatmentLabels: {
				    NOTX: "No Treatment",
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
					treatmentLabels: {
						NOTX: "No Treatment",
						AMOXDT: "Amox DT",
						AMOX_SYRUP: "Amox Syrup",
						OXYGEN: "Oxygen",
						CTX: "CTX",
						INJECTABLES: "Injectables",
						OTHER: "Other Treatment",
					},
					colors: {
						NOTX: "#FFFFFF",
						AMOXDT: "#00B0FF",
						AMOX_SYRUP: "#66BB6A",
						CTX: "#FF9800",
						INJECTABLES: "#9E9E9E",
						OXYGEN: "#7C4DFF",
						OTHER: "#FFFF00",
					},
					countyTreatment: []
				},

				diarrhoea: {
					selectedSubcounty: null,
					facilityNo: 0,
					facilityTreatmentData: [],
					diarrhoeaLocClass: {},
					locTreatmentData: [],
					treatmentLabels: {
						NOTX: "No Treatment",
						COP: "Co-Pack",
						ZINC: "Zinc",
						ORS: "ORS",
						ANTIBIOTICS: "Antibiotics",
						IV: "IV Fluids",
						OTHER: "Other Treatment",
					},
					colors: {
						NOTX: "#FFFFFF",
						ANTIBIOTICS: "#FFFF00",
						IV: "#000000",
						COP: "#03A9F4",
						ZINC: "#9E9E9E",
						ORS: "#FF9800",
						OTHER: "#EF9A9A",
					},
					countyTreatment: []
				},
				options: {
					subcounties: [],
					subcountiesList: [],
					graphTypes: [{ text: 'Line Chart', value: 'line' },
								{ text: 'Column Chart', value: 'column' }],
					pneumoniaFacilityTypes: []
				},
				facilityChart: {},
				pneumoniaLOCChart: {},
				xfacilityChart: {},
				data: {
					all: {
						pneumonia: [],
						diarrhoea: []
					},
					pneumoniaClass: [],
					pneumoniaLocClass: [],
					pneumoniaTreat: {},
					pneumoniaLocTreat: {},
					diarrhoeaClass: [],
					facilityTypes: [],
					xfacilityTypes: [],
					facilityTypesX: [],
					facilityTreatmentData: [],
					pneumoniaFacilityTreat: [],
					facilityLocData: [],
					diarrhoeaTreat: [],
					diarrhoeaLocClass: [],
					diarrhoeaLocTreat: []
				}
			}
		},
		created(){
			this.getCounties()
			if(!this.error){
				this.getSubCounties()
				this.getData()
			}
		},
		mounted(){

		},
		methods: {
			getSubCountyFacilityData(subcounty, assessment){
				let _this = this

				var facilityClassificationData = _.without(_.map(this.diarrhoeaPageData, (data) => {
					if (_.upperCase(data.facility.SUB_COUNTY) === _.upperCase(subcounty) && data.assessment_type_step === assessment) {
						return {
							facility: data.facility.FACILITY_NAME,
							classified: data.classified,
							notclassified: data.TOTAL_CASES_AFTER_DIFF - data.classified
						}
					}
				}), undefined)

				var categories = [`<b>${_.upperCase( subcounty )} Sub County</b>`]
				var seriesData = []
				var resData = [];
				var obj = {};
				var notClassifiedObj = {};

				obj.name = "Classified"
				obj.data = [0]

				notClassifiedObj.name = "Not Classified"
				notClassifiedObj.data = [0]

				obj.color = this.diarrhoeaColor
				obj.borderColor = this.diarrhoeaColor
				notClassifiedObj.color = this.notclassifiedColor.color
				notClassifiedObj.borderColor = "red"

				_.forOwn(facilityClassificationData, (o) => {
					categories.push(o.facility)
					obj.data.push(o.classified)
					notClassifiedObj.data.push(o.notclassified)
				})

				var data = obj.data;
				var noData = notClassifiedObj.data;

				var average = _.round(_.mean(data), 1)
				var noAverage = _.round(_.mean(noData), 1)

				obj.data[0] = average
				notClassifiedObj.data[0] = noAverage

				seriesData.push(notClassifiedObj)
				seriesData.push(obj)

				let facilityChart = {

				    chart: {
				        type: this.pneumonia.selectedChart
				    },

				    title: {
				        text: ' Diarrhoea Case Classification'
				    },

				    subtitle: {
				    	title: subcounty
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
				                this.series.name + ': ' + this.y + '<br/>'
				        }
				    },

				    plotOptions: {
				        column: {
				            stacking: 'percent',
				            dataLabels: {
								enabled: true,
								color: "#000",
								borderColor: "#000",
								format: "{point.percentage:.0f}%"
							},
							pointPadding: 0.2,
            				borderWidth: 2
				        }
				    },

				    series: seriesData
				}

				return facilityChart
			},
			getData(){
				axios.get(`/api/data/county/breakdown/${this.id}`)
				.then(res => {
					this.data.all = res.data
				})
			},
			getCounties(){
				axios.get('/api/data/counties')
				.then(res => {
					this.counties = _.map(res.data, o => ({
						value: o.county,
						text: o.county
					}))

					this.selectedCounty = this.county
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

			getDiarrhoeaSubcountyClassificationData(){
				axios.get('/api/data/diarrhoea/classification/subcounties/' + this.county)
				.then(res => {
					var diarrhoeaClassData = {}
					_.forOwn(res.data, data =>{
						if(typeof diarrhoeaClassData[data.assessment] == 'undefined'){
							diarrhoeaClassData[data.assessment] = {}
						}

						if(typeof diarrhoeaClassData[data.assessment][data.sub_county] == 'undefined'){
							diarrhoeaClassData[data.assessment][data.sub_county] = {}
						}

						diarrhoeaClassData[data.assessment][data.sub_county]['classified'] = data.TOTAL_CLASSIFIED;
						diarrhoeaClassData[data.assessment][data.sub_county]['notClassified'] = data.TOTAL_CASES_AFTER_DIFF - data.TOTAL_CLASSIFIED
					})
					// console.log(pneumoniaClassData)

					this.data.diarrhoeaClass = diarrhoeaClassData
					this.diarrhoeaSubCounties = [{sub_county: "<b>" + _.upperCase(this.county + " County") +" </b>"}]
					var subcounties = _.map(res.data, subcounty => {
						var sc = {"sub_county": subcounty.sub_county}
						if(!_.some(this.diarrhoeaSubCounties, sc)){
							this.diarrhoeaSubCounties.push(sc)
						}

					})
					// console.log(this.subcounties)
				})
			},

			getDiarrhoeaSubcountyLocClassificationData(){
				axios.get('/api/data/diarrhoea/classification/loc/' + this.county)
				.then(res => {
					var diarrhoeaLocClassData = {}
					var facility_type = []
					_.forOwn(res.data, data =>{
						if(typeof diarrhoeaLocClassData[data.assessment] == 'undefined'){
							diarrhoeaLocClassData[data.assessment] = {}
						}

						var ftype = (data.FACILITY_TYPE == null) ? "Unknown" : data.FACILITY_TYPE

						if(typeof diarrhoeaLocClassData[data.assessment][ftype] == 'undefined'){
							diarrhoeaLocClassData[data.assessment][ftype] = {}
						}

						diarrhoeaLocClassData[data.assessment][ftype]['classified'] = data.TOTAL_CLASSIFIED
						diarrhoeaLocClassData[data.assessment][ftype]['notClassified'] = data.TOTAL_CASES_AFTER_DIFF - data.TOTAL_CLASSIFIED
						facility_type.push(ftype)
					})
					// console.log(diarrhoeaLocClassData)

					this.data.diarrhoeaLocClass = diarrhoeaLocClassData
					// this.diarrhoeaLocFtypes = [{sub_county: "<b>" + _.upperCase(this.county + " County") +" </b>"}]
					facility_type = _.uniq(facility_type)
					this.data.facilityTypesX = facility_type
					// console.log(this.subcounties)
				})
			},

			getDiarrhoeaFacilityLocClassificationData(subcounty, assessment){
				let _this = this

				var facilityClassificationData = _.without(_.map(this.diarrhoeaPageData, (data) => {
					if (_.upperCase(data.facility.SUB_COUNTY) == _.upperCase(subcounty) && data.assessment_type_step == assessment) {
						return {
							facility_type: data.facility.FACILITY_TYPE,
							classified: data.classified,
							notclassified: data.TOTAL_CASES_AFTER_DIFF - data.classified
						}
					}
				}), undefined)

				var cleanedLOCData = _(facilityClassificationData)
				.groupBy('facility_type')
				.map((facilityData, facilityType) => ({
					// console.log(facilityData)
					facilityType: facilityType,
					classified: _.sumBy(facilityData, 'classified'),
					not_classified: _.sumBy(facilityData, 'notclassified')
				})).value()

				var categories = [`<b>${_.upperCase( subcounty )} Sub County</b>`]
				var seriesData = []
				var resData = [];
				var obj = {};
				var notClassifiedObj = {};

				obj.name = "Classified"
				obj.data = [0]

				notClassifiedObj.name = "Not Classified"
				notClassifiedObj.data = [0]

				obj.color = this.diarrhoeaColor
				obj.borderColor = this.diarrhoeaColor
				notClassifiedObj.color = this.notclassifiedColor.color
				notClassifiedObj.borderColor = "red"

				_.forOwn(cleanedLOCData, (o) => {
					categories.push(o.facilityType)
					obj.data.push(o.classified)
					notClassifiedObj.data.push(o.not_classified)
				})

				var data = obj.data;
				var noData = notClassifiedObj.data;

				var average = _.round(_.mean(data), 1)
				var noAverage = _.round(_.mean(noData), 1)

				obj.data[0] = average
				notClassifiedObj.data[0] = noAverage

				seriesData.push(notClassifiedObj)
				seriesData.push(obj)

				let locChart = {

				    chart: {
				        type: this.pneumonia.selectedChart
				    },

				    title: {
				        text: ' Diarrhoea Case Classification'
				    },

				    subtitle: {
				    	title: subcounty
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
				                this.series.name + ': ' + this.y + '<br/>'
				        }
				    },

				    plotOptions: {
				        column: {
				            stacking: 'percent',
				            dataLabels: {
								enabled: true,
								color: "#000",
								borderColor: "#000",
								format: "{point.percentage:.0f}%"
							},
							pointPadding: 0.2,
            				borderWidth: 2
				        }
				    },

				    series: seriesData
				}

				return locChart
			},
			getDiarrhoeaLocTreatmentData(){
				axios.get('/api/data/diarrhoea/treatment/loc/' + this.county)
				.then(res => {
					this.data.diarrhoeaLocTreat = res.data
				})
			},
			getPneumoniaTreatmentData(){
				axios.get('/api/data/pneumonia/treatment/subcounty/' + this.county)
				.then(res => {
					this.data.pneumoniaTreat = res.data
				})
			},
			getDiarrhoeaTreatmentData(){
				axios.get('/api/data/diarrhoea/treatment/subcounty/' + this.county)
				.then(res => {
					this.data.diarrhoeaTreat = res.data
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
						if(typeof pneumoniaClassData[data.assessment][data.sub_county] == 'undefined'){
							pneumoniaClassData[data.assessment][data.sub_county] = {}
						}

						// pneumoniaClassData[data.assessment][data.sub_county] = _.round((data.TOTAL_CLASSIFIED / data.TOTAL_CASES_AFTER_DIF) * 100)
						pneumoniaClassData[data.assessment][data.sub_county]['classified'] = data.TOTAL_CLASSIFIED;
						pneumoniaClassData[data.assessment][data.sub_county]['notClassified'] = data.TOTAL_CASES_AFTER_DIF - data.TOTAL_CLASSIFIED
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
						var ftype = (data.FACILITY_TYPE == null) ? "Unknown" : data.FACILITY_TYPE
						if(typeof pneumoniaLocClassData[data.assessment] == "undefined"){
							pneumoniaLocClassData[data.assessment] = {};
						}

						if(typeof pneumoniaLocClassData[data.assessment][ftype] == "undefined"){
							pneumoniaLocClassData[data.assessment][ftype] = {};
						}

						// pneumoniaLocClassData[data.assessment][ftype] = _.round((data.TOTAL_CLASSIFIED / data.TOTAL_CASES_AFTER_DIF) * 100)
						pneumoniaLocClassData[data.assessment][ftype]['classified'] = data.TOTAL_CLASSIFIED
						pneumoniaLocClassData[data.assessment][ftype]['notClassified'] = data.TOTAL_CASES_AFTER_DIF - data.TOTAL_CLASSIFIED

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
			getDiarrhoeaFacilityTreatmentData(subcounty, assessment){
				var facilityTreatmentData = _.without(_.map(this.diarrhoeaPageData, (data) => {
					if (_.upperCase(data.facility.SUB_COUNTY) == _.upperCase(subcounty) && data.assessment_type_step == assessment) {
						return {
							facility_name: data.facility.FACILITY_NAME,
							'NOTX': data.NOTX_CALC,
							'COP' : data.COP,
							'ZINC' : data.ZINC,
							'ORS' : data.ORS,
							'ANTIBIOTICS' : data.ANTIBIOTICS,
							'IV' : data.IV,
							'OTHER' : data.OTHER
						}
					}
				}), undefined)

				return facilityTreatmentData
			},

			getDiarrhoeaLocSubcountyTreatmentData(subcounty, assessment){
				var facilityTreatmentData = _.without(_.map(this.diarrhoeaPageData, (data) => {
					if (_.upperCase(data.facility.SUB_COUNTY) == _.upperCase(subcounty) && data.assessment_type_step == assessment) {
						return {
							facility_type: data.facility.FACILITY_TYPE,
							'NOTX': data.NOTX_CALC,
							'COP' : data.COP,
							'ZINC' : data.ZINC,
							'ORS' : data.ORS,
							'ANTIBIOTICS' : data.ANTIBIOTICS,
							'IV' : data.IV,
							'OTHER' : data.OTHER
						}
					}
				}), undefined)

				var cleanedData = _(facilityTreatmentData)
				.groupBy('facility_type')
				.map((facilityData, facilityType) => ({
					// console.log(facilityData)
					facility_type: facilityType,
					'NOTX': _.sumBy(facilityData, 'NOTX'),
					'COP' : _.sumBy(facilityData, 'COP'),
					'ZINC' : _.sumBy(facilityData, 'ZINC'),
					'ORS' : _.sumBy(facilityData, 'ORS'),
					'ANTIBIOTICS' : _.sumBy(facilityData, 'ANTIBIOTICS'),
					'IV' : _.sumBy(facilityData, 'IV'),
					'OTHER' : _.sumBy(facilityData, 'OTHER')
				})).value();

				return cleanedData
			},
			getPneumoniaFacilityTreatmentData(subcounty){
				var facilityTreatmentData = _.without(_.map(this.pageData, (data) => {
					if (data.facility.SUB_COUNTY === subcounty) {
						return {
							facility_name: data.facility.FACILITY_NAME,
							'AMOXDT': data.AMOXDT,
							'AMOX_SYRUP' : data.AMOX_SYRUP,
							'OXYGEN' : data.OXYGEN,
							'CTX' : data.CTX,
							'INJECTABLES' : data.INJECTABLES,
							'OTHER' : data.ANTI_OTHER,
							'NOTX' : data.NOTX_DIFF
						}
					}
				}), undefined)

				return facilityTreatmentData
			},
			getFacilityClassificationData(subcounty){
				var facilityClassificationData = _.without(_.map(this.pageData, (data) => {
					if (data.facility.SUB_COUNTY === subcounty) {
						return data
					}
				}), undefined)

				var cleanedLOCData = _(facilityClassificationData)
				.groupBy('facility.FACILITY_TYPE')
				.map((facilityData, facilityType) => ({
					// console.log(facilityData)
					facilityType: facilityType,
					classified: _.sumBy(facilityData, 'total_classified'),
					not_classified: _.sumBy(facilityData, 'total_cases_after_dif') - _.sumBy(facilityData, 'total_classified')
				})).value()

				var categories = [`<b>${_.upperCase( subcounty )} Sub County</b>`]
				var seriesData = []
				var resData = [];
				var obj = {};
				var notClassifiedObj = {};

				obj.name = "Classified"
				obj.data = [0]

				notClassifiedObj.name = "Not Classified"
				notClassifiedObj.data = [0]

				obj.color = this.pneumoniaColor
				notClassifiedObj.color = this.notclassifiedColor.color
				notClassifiedObj.borderColor = "red"

				_.forOwn(cleanedLOCData, (loc) => {
					categories.push(loc.facilityType)
					obj.data.push(loc.classified)
					notClassifiedObj.data.push(loc.not_classified)
				})

				var data = obj.data;
				var noData = notClassifiedObj.data;

				var average = _.round(_.mean(data), 1)
				var noAverage = _.round(_.mean(noData), 1)

				obj.data[0] = average
				notClassifiedObj.data[0] = noAverage

				seriesData.push(notClassifiedObj)
				seriesData.push(obj)

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
				            text: null
				        },allowDecimals: false,
				        min: 0,
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
				        	stacking: 'percent',
				            pointPadding: 0.2,
				            borderWidth: 2,
				        	dataLabels: {
								enabled: true,
								color: "#000",
								borderColor: "#000",
								format: "{point.percentage:.0f}%"
							},
				        }
				    },
				    series: seriesData
				}
			},
			getFacilityLocTreatementData(subcounty){
				var facilityClassificationData = _.without(_.map(this.pageData, (data) => {
					if (data.facility.SUB_COUNTY === subcounty) {
						return data
					}
				}), undefined)

				var cleanedLOCData = _(facilityClassificationData)
				.groupBy('facility.FACILITY_TYPE')
				.map((facilityData, facilityType) => ({
					FACILITY_TYPE: facilityType,
					'AMOXDT': _.sumBy(facilityData, 'AMOXDT'),
					'AMOX_SYRUP': _.sumBy(facilityData, 'AMOX_SYRUP'),
					'OXYGEN' : _.sumBy(facilityData, 'OXYGEN'),
					'CTX' : _.sumBy(facilityData, 'CTX'),
					'INJECTABLES' : _.sumBy(facilityData, 'INJECTABLES'),
					'OTHER' : _.sumBy(facilityData, 'ANTI_OTHER'),
					'NOTX' : _.sumBy(facilityData, 'NOTX_DIFF')
				})).value()

				return cleanedLOCData
			}
		},
		watch: {
			'selectedAssessment': function(newVal, oldVal){
				this.xfacilityChart = this.getSubCountyFacilityData(this.diarrhoea.selectedSubcounty, newVal)
				this.diarrhoea.facilityTreatmentData = this.getDiarrhoeaFacilityTreatmentData(this.diarrhoea.selectedSubcounty, newVal);
				this.diarrhoea.diarrhoeaLocClass = this.getDiarrhoeaFacilityLocClassificationData(this.diarrhoea.selectedSubcounty, newVal);
				this.diarrhoea.locTreatmentData = this.getDiarrhoeaLocSubcountyTreatmentData(this.diarrhoea.selectedSubcounty, newVal)
			},
			'pneumonia.selectedSubcounty': function(newVal, oldVal){
				var facilityClassificationData = _.without(_.map(this.pageData, (data) => {
					if (data.facility.SUB_COUNTY === newVal) {
						return {
							facility: data.facility.FACILITY_NAME,
							classified: data.total_classified,
							notclassified: data.total_cases_after_dif - data.total_classified
						}
					}
				}), undefined)

				var categories = [`<b>${_.upperCase( newVal )} Sub County</b>`]
				var seriesData = []
				var resData = [];
				var obj = {};
				var notClassifiedObj = {};

				obj.name = "Classified"
				obj.data = [0]

				notClassifiedObj.name = "Not Classified"
				notClassifiedObj.data = [0]

				obj.color = this.pneumoniaColor
				notClassifiedObj.color = this.notclassifiedColor.color
				notClassifiedObj.borderColor = "red"

				_.forOwn(facilityClassificationData, (o) => {
					categories.push(o.facility)
					obj.data.push(o.classified)
					notClassifiedObj.data.push(o.notclassified)
				})

				var data = obj.data;
				var noData = notClassifiedObj.data;

				var average = _.round(_.mean(data), 1)
				var noAverage = _.round(_.mean(noData), 1)

				obj.data[0] = average
				notClassifiedObj.data[0] = noAverage

				seriesData.push(notClassifiedObj)
				seriesData.push(obj)

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
				                this.series.name + ': ' + this.y + '<br/>'
				        }
				    },

				    plotOptions: {
				        column: {
				            stacking: 'percent',
				            dataLabels: {
								enabled: true,
								color: "#000",
								borderColor: "#000",
								format: "{point.percentage:.0f}%"
							},
							pointPadding: 0.2,
            				borderWidth: 2
				        }
				    },

				    series: seriesData
				}
				// })
				// .catch(error =>{
				// 	console.error(error)
				// 	alert("There was an error");
				// })

				this.data.facilityTreatmentData = this.getPneumoniaFacilityTreatmentData(newVal)
				this.pneumoniaLOCChart = this.getFacilityClassificationData(newVal)
				this.data.facilityLocData = this.getFacilityLocTreatementData(newVal)
			},
			'diarrhoea.selectedSubcounty': function(newVal, oldVal){

				this.xfacilityChart = this.getSubCountyFacilityData(newVal, this.selectedAssessment)
				this.diarrhoea.facilityTreatmentData = this.getDiarrhoeaFacilityTreatmentData(newVal, this.selectedAssessment);
				this.diarrhoea.diarrhoeaLocClass = this.getDiarrhoeaFacilityLocClassificationData(newVal, this.selectedAssessment)
				this.diarrhoea.locTreatmentData = this.getDiarrhoeaLocSubcountyTreatmentData(newVal, this.selectedAssessment)
			},
			selectedCounty: function(county){
				var _this = this
				if (_this.county !== county) {
					let url = "/dashboard/county/breakdown/" + county
					window.location.href = url
				}

			}
		},
		computed: {
			pageData() {
				return this.data.all.pneumonia[this.selectedAssessment]
			},
			diarrhoeaPageData(){
				return this.data.all.diarrhoea[this.selectedAssessment]
			},
			pneumoniaTotals(){
				var data = this.data.all.pneumonia[this.selectedAssessment]

				var total_cases_after_dif = _.sumBy(data, 'total_cases_after_dif');
				var total_classified = _.sumBy(data, 'total_classified')

				return {
					'total_classified':	total_classified,
					'cases_after_dif':	total_cases_after_dif
				}
			},

			diarrhoeaTotals(){
				var data = this.diarrhoeaPageData

				var total_classified = _.sumBy(data, 'classified')
				var total_cases_after_dif = _.sumBy(data, 'TOTAL_CASES_AFTER_DIFF')

				return {
					'total_classified':	total_classified,
					'cases_after_dif':	total_cases_after_dif
				}
			},

			subcountyPneumoniaTreatment(){
				var cleanedSubcountyData = _(this.pageData)
				.groupBy('facility.SUB_COUNTY')
				.map((facilityData, subcounty) => ({
					// console.log(facilityData)
					sub_county: subcounty,
					'AMOXDT': _.sumBy(facilityData, 'AMOXDT'),
					'AMOX_SYRUP' : _.sumBy(facilityData, 'AMOX_SYRUP'),
					'OXYGEN' : _.sumBy(facilityData, 'OXYGEN'),
					'CTX' : _.sumBy(facilityData, 'CTX'),
					'INJECTABLES' : _.sumBy(facilityData, 'INJECTABLES'),
					'OTHER' : _.sumBy(facilityData, 'ANTI_OTHER'),
					'NOTX' : _.sumBy(facilityData, 'NOTX_DIFF')
				})).value()

				cleanedSubcountyData =_.sortBy(cleanedSubcountyData, ['subcounty', 'ASC'])

				return cleanedSubcountyData
			},

			subcountyDiarrhoeaTreatment(){
				var cleanedSubcountyData = _(this.diarrhoeaPageData)
				.groupBy('facility.SUB_COUNTY')
				.map((facilityData, subcounty) => ({
					subcounty: subcounty,
					NOTX: _.sumBy(facilityData, 'NOTX_CALC'),
					COP: _.sumBy(facilityData, 'COP'),
					ZINC: _.sumBy(facilityData, 'ZINC'),
					ORS: _.sumBy(facilityData, 'ORS'),
					ANTIBIOTICS: _.sumBy(facilityData, 'ANTIBIOTICS'),
					IV: _.sumBy(facilityData, 'IV'),
					OTHER: _.sumBy(facilityData, 'OTHER'),
				})).value()

				cleanedSubcountyData = _.sortBy(cleanedSubcountyData, ['subcounty', 'ASC'])

				return cleanedSubcountyData
			},

			facilityPneumoniaTreatment(){
				var cleanedSubcountyData = _(this.pageData)
				.groupBy('facility.SUB_COUNTY')
				.map((facilityData, subcounty) => ({
					// console.log(facilityData)
					sub_county: subcounty,
					'AMOXDT': _.sumBy(facilityData, 'AMOXDT'),
					'AMOX_SYRUP' : _.sumBy(facilityData, 'AMOX_SYRUP'),
					'OXYGEN' : _.sumBy(facilityData, 'OXYGEN'),
					'CTX' : _.sumBy(facilityData, 'CTX'),
					'INJECTABLES' : _.sumBy(facilityData, 'INJECTABLES'),
					'OTHER' : _.sumBy(facilityData, 'ANTI_OTHER'),
					'NOTX' : _.sumBy(facilityData, 'NOTX_DIFF')
				})).value()

				cleanedSubcountyData =_.sortBy(cleanedSubcountyData, ['subcounty', 'ASC'])

				return cleanedSubcountyData
			},

			subcountyLOCPneumoniaTreatment(){
				var cleanedSubcountyData = _(this.pageData)
				.groupBy('facility.FACILITY_TYPE')
				.map((facilityData, facility_type) => ({
					// console.log(facilityData)
					FACILITY_TYPE: facility_type,
					'AMOXDT': _.sumBy(facilityData, 'AMOXDT'),
					'AMOX_SYRUP' : _.sumBy(facilityData, 'AMOX_SYRUP'),
					'OXYGEN' : _.sumBy(facilityData, 'OXYGEN'),
					'CTX' : _.sumBy(facilityData, 'CTX'),
					'INJECTABLES' : _.sumBy(facilityData, 'INJECTABLES'),
					'OTHER' : _.sumBy(facilityData, 'ANTI_OTHER'),
					'NOTX' : _.sumBy(facilityData, 'NOTX_DIFF')
				})).value()

				cleanedSubcountyData =_.sortBy(cleanedSubcountyData, ['subcounty', 'ASC'])

				return cleanedSubcountyData
			},

			subcountyLOCDiarrhoeaTreatment(){
				var cleanedSubcountyData = _(this.diarrhoeaPageData)
				.groupBy('facility.FACILITY_TYPE')
				.map((facilityData, facility_type) => ({
					// console.log(facilityData)
					FACILITY_TYPE: facility_type,
					NOTX: _.sumBy(facilityData, 'NOTX_CALC'),
					COP: _.sumBy(facilityData, 'COP'),
					ZINC: _.sumBy(facilityData, 'ZINC'),
					ORS: _.sumBy(facilityData, 'ORS'),
					ANTIBIOTICS: _.sumBy(facilityData, 'ANTIBIOTICS'),
					IV: _.sumBy(facilityData, 'IV'),
					OTHER: _.sumBy(facilityData, 'OTHER'),
				})).value()

				cleanedSubcountyData =_.sortBy(cleanedSubcountyData, ['subcounty', 'ASC'])

				return cleanedSubcountyData
			},

			pneumoniaDonutData(){
				var data = this.data.all.pneumonia[this.selectedAssessment]

				var cleanedData = _.map(data, (o) => {
					return {
						'AMOXDT' : o.AMOXDT,
						'AMOX_SYRUP' : o.AMOX_SYRUP,
						'OXYGEN' : o.OXYGEN,
						'CTX' : o.CTX,
						'INJECTABLES' : o.INJECTABLES,
						'OTHER' : o.ANTI_OTHER,
						'NOTX' : o.NOTX_DIFF
					}
				})

				return cleanedData
			},

			diarrhoeaDonutData(){
				var data = this.diarrhoeaPageData

				var cleanedData = _.map(data, (o) => {
					return {
						NOTX: o.NOTX_CALC,
						COP: o.COP,
						ZINC: o.ZINC,
						ORS: o.ORS,
						ANTIBIOTICS: o.ANTIBIOTICS,
						IV: o.IV,
						OTHER: o.OTHER,
					}
				})

				return cleanedData
			},

			facilityDistributionChart() {
				var data = [];
				var facilityCount = 0;
				_.forOwn(this.facilitydistribution, (dist) => {
					var obj = {};
					obj.name = dist.FACILITY_TYPE
					obj.y = dist.facilities
					obj.color = this.colors[dist.FACILITY_TYPE]
					data.push(obj)
					facilityCount = facilityCount + dist.facilities
					// var arr = [];
					// data.push([dist.FACILITY_TYPE, dist.facilities])
				})

				return {
				    chart: {
				        type: 'pie',
				        margin: 0,
				        style: {
							fontFamily: 'Nunito'
						},
						options3d: {
							enabled: true,
							alpha: 45
						}
				    },
					credits: {
						enabled: false
					},
				    title: {
				    	verticalAlign: 'middle',
				    	loating: true,
				        text: "<center><b>"+facilityCount+"</b> Facilities<br/>Assessed</center>",
				        style: {
				        	fontSize: "12px"
				        }
				    },
				    xAxis: {
				        categories: ['Facilities'],
				        visible: false
				    },
				    yAxis: {
				        min: 0,
				        gridLineWidth: 0,
				        minorGridLineWidth: 0,
				        visible: false,
				        title: {
				            text: null
				        }
				    },
				    legend: {
				        reversed: true
				    },
				    plotOptions: {
				        pie: {
				        	innerSize: '60%',
				        	dataLabels: {
				        		enabled: true,
				        		format: '<b>{point.name}</b>, {point.y}'
				        	}
				        }
				    },
				    series: [{
				    	name: "Facilities",
				    	data: data
				    }]
				};
			},
			pneumoniaCountyClassification(){
				var data = this.data.all.pneumonia[this.selectedAssessment]

				var total_cases_after_dif = _.sumBy(data, 'total_cases_after_dif')
				var total_classified = _.sumBy(data, 'total_classified')
				return _.round((total_classified / total_cases_after_dif) * 100)
			},
			diarrhoeaCountyClassification(){
				return _.round((this.diarrhoeaTotals.total_classified / this.diarrhoeaTotals.cases_after_dif) * 100)
			},
			assessmentOptions(){
				let options = [];
				let legacyOptions = [];
				let legacy_assessment_types = [];
				let assessment_types = [];
				try{

					options = _.map( this.assessments, (o) => {
						return {
							text: o.assessment_type_step,
							value: o.assessment_type_step
						}
					})

					if (this.legacydata.length >  0){
						assessment_types = _.chain(this.legacydata).map('assessment_type').uniq().value()
						legacyOptions = _.map(assessment_types, (o) => {
							return {
								text: o + " (Legacy)",
								value: o + " (Legacy)"
							}
						})

						_.each(legacyOptions, (option) => {
							options.unshift(option)
						})
					}

					// console.log(options)

					this.selectedAssessment = options[0].value

					return options
				}catch(err){
					console.log(err)
				}

				return options;
			},
			diarrhoeaSubCountyClassifications() {
				var categories = [`<b>${_.upperCase( this.county )} COUNTY</b>`];

				var seriesData = []
				var resData = []

				var cleanedData = _(this.diarrhoeaPageData)
				.groupBy('facility.SUB_COUNTY')
				.map((facilityData, subcounty) => ({
					subcounty: subcounty,
					classified: _.sumBy(facilityData, 'classified'),
					not_classified: _.sumBy(facilityData, 'TOTAL_CASES_AFTER_DIFF') - _.sumBy(facilityData, 'classified')
				})).value()

				cleanedData =_.sortBy(cleanedData, ['subcounty', 'ASC'])

				var obj = {};
				var notClassifiedObj = {};
				obj.name = "Classified"
				notClassifiedObj.name = "Not Classified";
				obj.data = [0]
				notClassifiedObj.data = [0]
				obj.color = this.diarrhoeaColor
				obj.borderColor = this.diarrhoeaColor
				notClassifiedObj.color = this.notclassifiedColor.color
				notClassifiedObj.borderColor = "red"

				_.forOwn(cleanedData, (data, k) => {
					var key = k + 1;
					categories.push(data.subcounty)
					obj.data.push(data.classified);
					notClassifiedObj.data.push(data.not_classified);
				})


				var data = obj.data;
				var noData = notClassifiedObj.data;
				var average = _.round(_.mean(data), 1)
				var noAverage = _.round(_.mean(noData), 1)
				obj.data[0] = average
				notClassifiedObj.data[0] = noAverage
				seriesData.push(notClassifiedObj)
				seriesData.push(obj)
				// })

				return {

				    chart: {
				        type: 'column'
				    },

				    title: {
				        text: 'Diarrhoea Case Classification'
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
				                this.series.name + ': ' + this.y + '<br/>'
				        }
				    },

				    plotOptions: {
				        column: {
				            stacking: 'percent',
				            dataLabels: {
								enabled: true,
								color: "#000",
								borderColor: "#000",
								format: "{point.percentage:.0f}%"
							},
							pointPadding: 0.2,
            				borderWidth: 2
				        }
				    },

				    series: seriesData
				}
			},
			pneumoniaSubCountyClassifications(){
				var cleanedSubcountyData = _(this.pageData)
				.groupBy('facility.SUB_COUNTY')
				.map((facilityData, subcounty) => ({
					// console.log(facilityData)
					subcounty: subcounty,
					classified: _.sumBy(facilityData, 'total_classified'),
					not_classified: _.sumBy(facilityData, 'total_cases_after_dif') - _.sumBy(facilityData, 'total_classified')
				})).value()

				cleanedSubcountyData =_.sortBy(cleanedSubcountyData, ['subcounty', 'ASC'])


				var seriesData = []
				var resData = []

				var categories = [`<b>${_.upperCase( this.county )} COUNTY</b>`];
				var obj = {};
				var notClassifiedObj = {};
				obj.name = "Classified"
				notClassifiedObj.name = "Not Classified";
				obj.data = [0]
				notClassifiedObj.data = [0]
				obj.color = this.pneumoniaColor
				notClassifiedObj.color = this.notclassifiedColor.color
				notClassifiedObj.borderColor = "red"
				// _.forOwn(categories, (subcounty, k) => {
				// 	if(k != 0){
				// 		if(typeof categoryData[subcounty] == "undefined"){
				// 			obj.data.push(0)
				// 			notClassifiedObj.data.push(0)
				// 		}else{
				// 			obj.data.push(categoryData[subcounty]['classified'])
				// 			notClassifiedObj.data.push(categoryData[subcounty]['notClassified'])
				// 		}
				// 	}
				// })

				_.forOwn(cleanedSubcountyData, (data, k) => {
					var key = k + 1;
					categories.push(data.subcounty)
					obj.data.push(data.classified);
					notClassifiedObj.data.push(data.not_classified);
				})

				var data = obj.data;
				var noData = notClassifiedObj.data;
				var average = _.round(_.mean(data), 1)
				var noAverage = _.round(_.mean(noData), 1)
				obj.data[0] = average
				notClassifiedObj.data[0] = noAverage
				seriesData.push(notClassifiedObj)
				seriesData.push(obj)

				// })
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
				                this.series.name + ': ' + this.y + '<br/>'
				        }
				    },
				    plotOptions: {
				        column: {
				            stacking: 'percent',
				            dataLabels: {
								enabled: true,
								color: "#000",
								borderColor: "#000",
								format: "{point.percentage:.0f}%"
							},
							pointPadding: 0.2,
            				borderWidth: 2
				        }
				    },
				    series: seriesData
				}
			},
			diarrhoeaFacilityClassification(){
				var categories = ["<b>" + _.upperCase(this.diarrhoea.selectedSubcounty) + " SUB COUNTY" + "</b>",];
				categories = categories.concat(this.data.facilityTypesX)
				var seriesData = [];
				var cat = Object.keys(this.diarrhoea.diarrhoeaLocClass)
				var categoryData = this.diarrhoea.diarrhoeaLocClass[this.selectedAssessment]
					var obj = {};
					var notClassifiedObj = {};

					obj.name = "Classified"
					notClassifiedObj.name = "Not Classified";

					obj.data = []
					notClassifiedObj.data = []

					obj.color = this.diarrhoeaColor
					notClassifiedObj.color = this.notclassifiedColor.color
					notClassifiedObj.borderColor = "red"

					_.forOwn(categories, (ftype, k) => {
						if(k != 0){
							if(typeof categoryData[ftype] == "undefined"){
								obj.data.push(0)
								notClassifiedObj.data.push(0)
							}else{
								obj.data.push(categoryData[ftype]['classified'])
								notClassifiedObj.data.push(categoryData[ftype]['notClassified'])
							}
						}
					})

					var data = obj.data;
					var noData = notClassifiedObj.data;

					var average = _.round(_.mean(data), 1)
					var noAverage = _.round(_.mean(noData), 1)

					obj.data.unshift(average)
					notClassifiedObj.data.unshift(noAverage)

					seriesData.push(notClassifiedObj)
					seriesData.push(obj)
				// })

				return {

				    chart: {
				        type: 'column'
				    },

				    title: {
				        text: 'Diarrhoea Case Classification by Level of Care'
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
				                this.series.name + ': ' + this.y + '<br/>'
				        }
				    },

				    plotOptions: {
				        column: {
				            stacking: 'percent',
				            dataLabels: {
								enabled: true,
								color: "#000",
								borderColor: "#000",
								format: "{point.percentage:.0f}%"
							},
							pointPadding: 0.2,
            				borderWidth: 2
				        }
				    },

				    series: seriesData
				}
			},
			pneumoniaFacilityClassification(){
				var categories = ["<b>" + _.upperCase(this.pneumonia.selectedSubcounty) + " SUB COUNTY" + "</b>",];
				categories = categories.concat(this.data.xfacilityTypes)
				var seriesData = [];
				var cat = Object.keys(this.data.pneumoniaFacilityTreat)
				var categoryData = this.data.pneumoniaFacilityTreat[this.selectedAssessment]
				// _.forOwn(cat, (category) => {
					var obj = {};
					var notClassifiedObj = {};

					obj.name = "Classified"
					notClassifiedObj.name = "Not Classified";

					obj.data = []
					notClassifiedObj.data = []

					obj.color = this.pneumoniaColor
					notClassifiedObj.color = this.notclassifiedColor.color
					notClassifiedObj.borderColor = "red"

					_.forOwn(categories, (ftype, k) => {
						if(k != 0){
							if(typeof categoryData[ftype] == "undefined"){
								obj.data.push(0)
								notClassifiedObj.data.push(0)
							}else{
								obj.data.push(categoryData[ftype]['classified'])
								notClassifiedObj.data.push(categoryData[ftype]['notClassified'])
							}
						}
					})

					var data = obj.data;
					var noData = notClassifiedObj.data;

					var average = _.round(_.mean(data), 1)
					var noAverage = _.round(_.mean(noData), 1)

					obj.data.unshift(average)
					notClassifiedObj.data.unshift(noAverage)

					seriesData.push(notClassifiedObj)
					seriesData.push(obj)
				// })

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
				            text: null
				        },allowDecimals: false,
				        min: 0,
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
				        	stacking: 'percent',
				            pointPadding: 0.2,
				            borderWidth: 2,
				        	dataLabels: {
								enabled: true,
								color: "#000",
								borderColor: "#000",
								format: "{point.percentage:.0f}%"
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
				        }
				    },

				    series: seriesData
				}
			},
			pneumoniaLoCTreatmentBaseline(){
				var categories = ["<b>" + _.upperCase(this.county) + " COUNTY" + "</b>",];

				var cleanedSubcountyData = _(this.pageData)
				.groupBy('facility.FACILITY_TYPE')
				.map((facilityData, facilityType) => ({
					facilityType: facilityType,
					classified: _.sumBy(facilityData, 'total_classified'),
					not_classified: _.sumBy(facilityData, 'total_cases_after_dif') - _.sumBy(facilityData, 'total_classified')
				})).value()

				cleanedSubcountyData = _.sortBy(cleanedSubcountyData, [ 'facilityType', 'ASC' ])
				var seriesData = [];
				var obj = {};
				var notClassifiedObj = {};

				obj.name = "Classified"
				notClassifiedObj.name = "Not Classified";

				obj.data = [0]
				notClassifiedObj.data = [0]

				obj.color = this.pneumoniaColor
				notClassifiedObj.color = this.notclassifiedColor.color
				notClassifiedObj.borderColor = "red"

				_.forOwn(cleanedSubcountyData, (data) => {
					categories.push(data.facilityType)

					obj.data.push(data.classified)
					notClassifiedObj.data.push(data.not_classified)
				})


					var data = obj.data;
					var noData = notClassifiedObj.data;

					var average = _.round(_.mean(data), 1)
					var noAverage = _.round(_.mean(noData), 1)

					obj.data[0] = average
					notClassifiedObj.data[0] = noAverage

					seriesData.push(notClassifiedObj)
					seriesData.push(obj)


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
				            text: null
				        },allowDecimals: false,
				        min: 0,
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
				        	stacking: 'percent',
				            pointPadding: 0.2,
				            borderWidth: 2,
				        dataLabels: {
								enabled: true,
								color: "#000",
								borderColor: "#000",
								format: "{point.percentage:.0f}%"
							},
				        }
				    },
				    series: seriesData
				}
			},
			diarrhoeaLoCTreatment(){
				var categories = ["<b>" + _.upperCase(this.county) + " COUNTY" + "</b>",];
				var seriesData = []
				var resData = []

				var cleanedData = _(this.diarrhoeaPageData)
				.groupBy('facility.FACILITY_TYPE')
				.map((facilityData, facility_type) => ({
					facility_type: facility_type,
					classified: _.sumBy(facilityData, 'classified'),
					not_classified: _.sumBy(facilityData, 'TOTAL_CASES_AFTER_DIFF') - _.sumBy(facilityData, 'classified')
				})).value()

				cleanedData =_.sortBy(cleanedData, ['subcounty', 'ASC'])

				var obj = {};
				var notClassifiedObj = {};
				obj.name = "Classified"
				notClassifiedObj.name = "Not Classified";
				obj.data = [0]
				notClassifiedObj.data = [0]
				obj.color = this.diarrhoeaColor
				obj.borderColor = this.diarrhoeaColor
				notClassifiedObj.color = this.notclassifiedColor.color
				notClassifiedObj.borderColor = "red"

				_.forOwn(cleanedData, (data, k) => {
					var key = k + 1;
					categories.push(data.facility_type)
					obj.data.push(data.classified);
					notClassifiedObj.data.push(data.not_classified);
				})


				var data = obj.data;
				var noData = notClassifiedObj.data;
				var average = _.round(_.mean(data), 1)
				var noAverage = _.round(_.mean(noData), 1)
				obj.data[0] = average
				notClassifiedObj.data[0] = noAverage
				seriesData.push(notClassifiedObj)
				seriesData.push(obj)
				// })

				return {

				    chart: {
				        type: 'column'
				    },

				    title: {
				        text: 'Diarrhoea Case Classification by Level of Care'
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
				                this.series.name + ': ' + this.y + '<br/>'
				        }
				    },

				    plotOptions: {
				        column: {
				            stacking: 'percent',
				            dataLabels: {
								enabled: true,
								color: "#000",
								borderColor: "#000",
								format: "{point.percentage:.0f}%"
							},
							pointPadding: 0.2,
            				borderWidth: 2
				        }
				    },

				    series: seriesData
				}
				// }else{
				// 	return {}
				// }
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
				        }
				    },

				    series: seriesData
				}
			},
		}
	}
</script>
