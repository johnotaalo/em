<template>
	<div>
		<div class="col">
			<div class="col-md">
				<center><small style="font-size: .7rem">This page shows data for at least 3 facility assessments.</small></center>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<div class="row align-items-center">
									<div class="col">

										<h6 class="card-title text-uppercase text-muted mb-2">
											Counties
										</h6>

										<span class="h2 mb-0">
											{{ counties }}
										</span>
									</div>
									<div class="col-auto">

										<span class="h2 fa fa-flag text-muted mb-0"></span>

									</div>
								</div>

							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<div class="row align-items-center">
									<div class="col">

										<h6 class="card-title text-uppercase text-muted mb-2">
											Facilities
										</h6>

										<span class="h2 mb-0">
											{{ facilities }}
										</span>
									</div>
									<div class="col-auto">

										<span class="h2 fa fa-hospital text-muted mb-0"></span>

									</div>
								</div>

							</div>
						</div>
					</div>
				</div>		

				<div class="card">
					<div class="row align-items-center">
						<div class="col">
							<table class="table table-bordered table-sm card-table">
								<tr v-for="(value, key) in assessmentCountNumber">
									<th>{{key}}</th>
									<td>{{ value.length }}</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				
				<div class="card" style="height: 600px;overflow-x: scroll;">
					<perfect-scrollbar>
						<table class="table table-bordered table-sm card-table">
							<thead>
								<th>County</th>
								<th>Facilities</th>
							</thead>
							<tbody>
								<tr v-for="(value, key) in countyData">
									<td>{{ value[1] }}</td>
									<td><a class="btn btn-link" :href="'/dashboard/county/breakdown/' + value[1]">{{ value[2] }}</a></td>
								</tr>
							</tbody>
						</table>
					</perfect-scrollbar>
				</div>

			</div>
			<div class="col-md">
				<!-- <div class="row">		
					<div class="col-md-6">
						<div class="card">
							<div class="card-header">
								<h5 class="card-header-title">
								PNEUMONIA CLASSIFICATION
								<span class="badge badge-soft-warning mt-n1 float-right" style="flex: 0"><span v-if="variance.classification.pneumonia > 0">+</span>{{ variance.classification.pneumonia }}%</span>
								</h5>
							</div>
							<div class="card-body">
								<loading :active.sync="classificationLoading" :color="loaderColor" :can-cancel="false" :is-full-page="false"></loading>
								<highcharts :options="pneumoniaClassificationOptions" style="height: 50vh;"></highcharts>
							</div>
						</div>
						
					</div>
					<div class="col-md-6">
						<div class="card">
							<div class="card-header">
								<h5 class="card-header-title">
								PNEUMONIA TREATMENT
								<span class="badge badge-soft-warning mt-n1 float-right" style="flex: 0">+0%</span>
								</h5>
							</div>
							<div class="card-body">
								<loading :active.sync="pneumoniaTreatmentLoading" :color="loaderColor" :can-cancel="false" :is-full-page="false"></loading>
								<highcharts :options="pneumoniaTreatmentOptions" style="height: 50vh;"></highcharts>
							</div>
						</div>
					</div>
				</div> -->
				<div class="row">		
					<div class="col-md-6">
						<div class="card">
							<div class="card-header">
								<h5 class="card-header-title">
								PNEUMONIA CLASSIFICATION
								<span class="badge badge-soft-warning mt-n1 float-right" style="flex: 0"><span v-if="variance.classification.pneumonia > 0">+</span>{{ variance.classification.pneumonia }}%</span>
								</h5>
							</div>
							<div class="card-body">
								<loading :active.sync="classificationLoading" :color="loaderColor" :can-cancel="false" :is-full-page="false"></loading>
								<highcharts :options="pneumoniaClassificationOptionsNew" style="height: 50vh;"></highcharts>
							</div>
						</div>
						
					</div>
					<div class="col-md-6">
						<div class="card">
							<div class="card-header">
								<h5 class="card-header-title">
								PNEUMONIA TREATMENT
								<span class="badge badge-soft-warning mt-n1 float-right" style="flex: 0">+0%</span>
								</h5>
							</div>
							<div class="card-body">
								<loading :active.sync="pneumoniaTreatmentLoading" :color="loaderColor" :can-cancel="false" :is-full-page="false"></loading>
								<highcharts :options="pneumoniaTreatmentOptionsNew" style="height: 50vh;"></highcharts>
							</div>
						</div>
					</div>
				</div>
				<div class="row" style="margin-bottom: 10px;">
					<div class="col-md-6">
						<div class="card">
							<div class="card-header">
								<h5 class="card-header-title">
								DIARRHOEA CLASSIFICATION

								<span class="badge badge-soft-warning mt-n1 float-right" style="flex: 0"><span v-if="variance.classification.diarrhoea > 0">+</span>{{ variance.classification.diarrhoea }}%</span>
								</h5>
							</div>
							<div class="card-body">
								<loading :active.sync="classificationLoading" :color="loaderColor" :can-cancel="false" :is-full-page="false"></loading>
								<highcharts :options="diarrhoeaClassificationOptionsNew" style = "height: 50vh;"></highcharts>	
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card">
							<div class="card-header">
								<h5 class="card-header-title">
								DIARRHOEA TREATMENT
								<span class="badge badge-soft-warning mt-n1 float-right" style="flex: 0">+0%</span>
								</h5>
							</div>
							<div class="card-body">
								<loading :active.sync="diarrhoeaTreatmentLoading" :color="loaderColor" :can-cancel="false" :is-full-page="false"></loading>
								<highcharts :options="diarrhoeaTreatmentOptionsNew" style = "height: 50vh;"></highcharts>
							</div>
							
						</div>
					</div>
				</div>
				
			</div>
		</div>
	
					
				
		
	</div>	
</template>

<script type="text/javascript">
import json from '../../../public/counties.json'

// import {Chart} from 'highcharts-vue'

	export default {
		data() {
			return {
				data: {
					all: {}
				},
				mapLoading: false,
				classificationLoading: false,
				diarrhoeaTreatmentLoading: false,
				pneumoniaTreatmentLoading: false,
				loaderColor: "#2196F3",
				pneumoniaClassificationBaseline: 47,
				diarrhoeaClassificationBaseline: 32,
				countyData: [],
				diarrhoeaColor: "#03A9F4",
				pneumoniaColor: "#66BB6A",
				baselineColor: "#BDBDBD",
				facilities: 0,
				counties: 0,
				assessmentsCount: [],
				facilityBreakdown: {},
				countyFacilities: {},
				classificationData: {
					pneumonia_class: 0,
					diarrhoea_class: 0
				},
				treatmentData: {
					diarrhoea: {},
					pneumonia: {}
				},
				baselineData: {NOTX: 0,
					AMOXDT: 4,
					AMOX_SYRUP: 42,
					INJECTABLES: 29,
					CTX: 24,
					OXYGEN: 0,
					OTHER: 0,
					
				},
				pneumoniaTreatmentLabels: {NOTX: "No Treatment",
					AMOXDT: "Amox DT",
					AMOX_SYRUP: "Amox Syrup",
					CTX: "CTX",
					INJECTABLES: "Injectables",
					OXYGEN: "Oxygen",
					OTHER: "Other Treatment",
					
				},
				pneumoniaTreatmentColors: {NOTX: "#EF9A9A",
					AMOXDT: "#00B0FF",
					AMOX_SYRUP: "#66BB6A",
					CTX: "#FF9800",
					INJECTABLES: "#9E9E9E",
					OXYGEN: "#7C4DFF",
					OTHER: "#FFFF00",
					
				}
			}
		},
		mounted() {
			this.getComputedData();
			// this.classificationLoading = true;
			axios.get('/api/data/classification')
			.then((response) => {
				// this.classificationLoading = false;
				var data = response.data;
				this.classificationData.pneumonia_class = (data.pneumonia.total_cases / (data.pneumonia.total_cases + data.pneumonia.no_class)) * 100;
				this.classificationData.diarrhoea_class = (data.diarrhoea.total_cases / (data.diarrhoea.total_cases + data.diarrhoea.no_class)) * 100;
			});

			// this.pneumoniaTreatmentLoading = true;
			axios.get('/api/data/treatments/pneumonia')
			.then((response) => {
				// console.log(response)
				var data = response.data;
				this.treatmentData.pneumonia = data;
				// this.pneumoniaTreatmentLoading = false;
			});

			// this.diarrhoeaTreatmentLoading = true
			axios.get('/api/data/treatments/diarrhoea')
			.then((response) => {
				// this.diarrhoeaTreatmentLoading = false
				var data = response.data;
				this.treatmentData.diarrhoea = data;
			});

			axios.get('/api/data/count/assessments')
			.then((res) => {
				var data = res.data
				this.assessmentsCount = data
			})

			axios.get('/api/data/count/facilities')
			.then((response) => {
				var data = response.data;
				this.facilities = data;
			});

			axios.get('/api/data/count/counties')
			.then((response) => {
				var data = response.data;
				this.counties = data;
			});

			axios.get('/api/data/facilities/breakdown')
			.then(res => {
				var breakdown = []
				_.forOwn(res.data, (value, key) => {
					breakdown.push({
						type: _.startCase(_.toLower(key)),
						count: value.length
					});
				})

				this.facilityBreakdown = breakdown
			})

			this.mapLoading = true;
			axios.get('/api/data/county/facilities')
			.then((response) => {
				// console.log(response.data)
				this.mapLoading = false;
				var data = response.data
				this.countyData = _.map(data, (county) => ([ county.cto_id, _.toUpper(county.county), county.facilities.length ]))
			});
		},
		methods: {
			getComputedData(){
				this.classificationLoading = true;
				this.pneumoniaTreatmentLoading = true;
				this.diarrhoeaTreatmentLoading = true;
				axios.get('/api/data/national')
				.then(response => {
					this.classificationLoading = false;
					this.pneumoniaTreatmentLoading = false;
					this.diarrhoeaTreatmentLoading = false;
					this.data.all = response.data
				})
			}
		},
		computed: {
			mapData: function(){
				return {
		          chart: {
		            map: json
		          },

		          title: null,
		          mapNavigation: {
		            enabled: false,
		            enableDoubleClickZoomTo: false
		          },

		          colorAxis: {
		            dataClasses: [{
		              from: 1,
		              to: 1000000,
		              color: "#FFC107"
		            }]
		          },

		          legend:{ enabled:false },

		          plotOptions: {
		          	series: {
		          		points: {
		          			events: {
		          				click: function(){
		          					// console.log(this.series);
		          				}
		          			}
		          		}
		          	}
		          },

		          series: [{
		          	showInLegend: false,
		            data: this.countyData,
		            keys: ["cto_id", "COUNTY_NAM", "value"],
		            name: 'County data',
		            joinBy: 'COUNTY_NAM',
		            states: {
		              hover: {
		                color: '#FFE082'
		              }
		            },
		            point: {
		            	events: {
		            		click: function(){
		            			window.location = `/dashboard/county/breakdown/${this.COUNTY_NAM}`
		            		}
		            	}
		            },
		            dataLabels: {
		              enabled: false,
		              format: '{point.properties.COUNTY_NAM}'
		            }
		          }],

		          tooltip: {
		            formatter: function(){
		              return _.startCase(_.toLower(this.point.COUNTY_NAM)) + "<br/> Facilities: " + this.point.value
		            }
		          }
		        }
			},
			assessmentCountNumber: function(){
				var assessmentArray = {};

				_.forOwn(this.assessmentsCount, (v, k) => {
					if (v.assessment_type_step === "Baseline (Legacy)") {
						v.assessment_type_step = "Baseline";
					}

					if(typeof assessmentArray[v.assessment_type_step] === 'undefined'){
						assessmentArray[v.assessment_type_step] = [];
					}
					assessmentArray[v.assessment_type_step].push(v.county)
				})

				// console.log(assessmentArray)
				return assessmentArray;
			},
			pneumoniaClassificationOptionsNew: function(){
				var em = this
				var categories = _.map(this.data.all.pneumonia, (object, key)=>{
					return key
				})

				categories.sort()

				var chartData = [];

				_.each(categories, (category) => {
					var data = this.data.all.pneumonia[category];
					var total_cases_after_dif = _.sumBy(data, 'total_cases_after_dif')
					var total_classified = _.sumBy(data, 'total_classified')
					chartData.push(_.round((total_classified / total_cases_after_dif) * 100))
				})
				return {
					title: null,
					chart: {
						type: 'column'
					},
					xAxis: {
						categories: categories
					},
					yAxis: [{
						gridLineWidth: 0,
						minorGridLineWidth: 0,
						labels:
						{
							enabled: false
						},
						title: {
							text: null
						}
					}],
					plotOptions: {
						column: {
							dataLabels: {
								enabled: true,
								format: "{point.y}%"
							}
						}
					},
					series: [{
						showInLegend: false,
						name: 'Classifications',
						color: em.pneumoniaColor,
						data: chartData // sample data
					}]
				}
			},
			pneumoniaClassificationOptions: function(){
				var em = this;
				return {
					title: null,
					chart: {
						type: 'column'
					},
					xAxis: {
						categories: ['Baseline', 'Supervision 2018']
					},
					yAxis: [{
						gridLineWidth: 0,
						minorGridLineWidth: 0,
						labels:
						{
							enabled: false
						},
						title: {
							text: null
						}
					}],
					plotOptions: {
						column: {
							dataLabels: {
								enabled: true,
								format: "{point.y}%"
							}
						}
					},
					series: [{
						showInLegend: false,
						name: 'Classifications',
						color: em.pneumoniaColor,
						data: [em.pneumoniaClassificationBaseline,_.round(this.classificationData.pneumonia_class)] // sample data
					}]
				};
			},
			diarrhoeaClassificationOptions: function(){
				var em = this;
					return {
						title: null,
										chart: {
											type: 'column'
										},
										xAxis: {
											categories: ['Baseline', 'Supervision 2018']
										},
										yAxis: [{
											gridLineWidth: 0,
											minorGridLineWidth: 0,
											labels:
											{
												enabled: false
											},
											title: {
												text: null
											}					
										}],
										plotOptions: {
											column: {
												dataLabels: {
													enabled: true,
													format: "{point.y}%"
												}
											}
										},
										series: [{
											showInLegend: false,
											name: 'Classifications',
											color: em.diarrhoeaColor,
											data: [em.diarrhoeaClassificationBaseline,_.round(this.classificationData.diarrhoea_class)] // sample data
										}]}
			},
			diarrhoeaClassificationOptionsNew: function(){
				var em = this;
				var chartData = [];

				var categories = _.map(this.data.all.diarrhoea, (object, key)=>{
					return key
				})

				categories.sort()

				_.each(categories, (category) => {
					var data = this.data.all.diarrhoea[category];
					var total_cases_after_dif = _.sumBy(data, 'TOTAL_CASES_AFTER_DIFF')
					var total_classified = _.sumBy(data, 'classified')
					chartData.push(_.round((total_classified / total_cases_after_dif) * 100))
				})

				return {
					title: null,
					chart: {
						type: 'column'
					},
					xAxis: {
						categories: categories
					},
					yAxis: [{
						gridLineWidth: 0,
						minorGridLineWidth: 0,
						labels:
						{
							enabled: false
						},
						title: {
							text: null
						}					
					}],
					plotOptions: {
						column: {
							dataLabels: {
								enabled: true,
								format: "{point.y}%"
							}
						}
					},
					series: [{
						showInLegend: false,
						name: 'Classifications',
						color: em.diarrhoeaColor,
						data: chartData // sample data
					}]
				}
			},
			pneumoniaTreatmentOptionsNew: function(){
				var _this = this;

				var categories = _.map(this.data.all.pneumonia, (object, key)=>{
					return key
				})

				categories.sort()

				var pneumoniaTreatmentKeys = _.map(this.pneumoniaTreatmentLabels, (object, key) => {
					return key
				})

				var seriesData = [];
				var preevaluatedData = {};

				_.each(categories, (category) => {
					var data = this.data.all.pneumonia[category]
					var specificData = [];
					_.each(pneumoniaTreatmentKeys, (k) => {
						specificData[k] = _.sumBy(data, k)
					})

					preevaluatedData[category] = specificData
				});

				_.each(this.pneumoniaTreatmentLabels, (label, key) => {
					var obj = {}
					obj.name = label
					obj.color = this.pneumoniaTreatmentColors[key]
					var objData = [];

					_.forOwn(categories, (category) => {
						objData.push(preevaluatedData[category][key])
					})

					obj.data = objData
					seriesData.push(obj)
				})

				return {
					title: null,
					chart: {
						type: 'column'
					},
					xAxis: {
						categories: categories,
					},
					yAxis: [{
						gridLineWidth: 0,
						minorGridLineWidth: 0,
						labels:
						{
							enabled: false
						},
						title: {
							text: null
						}
					}],
					legend: {
						verticalAlign: 'top',
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
						},
						allowPointSelect: false,
					},
					series: seriesData
				}
			},
			pneumoniaTreatmentOptions: function(){
				var _this = this;
				var seriesData = [];
				_.forOwn(this.treatmentData.pneumonia, function(v, k){
					var obj = {};
					obj = {
						name: _this.pneumoniaTreatmentLabels[k],
						data: [_this.baselineData[k], v],
						color: _this.pneumoniaTreatmentColors[k]
					};

					seriesData.push(obj);
				});
				return {
					title: null,
					chart: {
						type: 'column'
					},
					xAxis: {
						categories: ['Baseline', 'Supervision 2018'],
					},
					yAxis: [{
						gridLineWidth: 0,
						minorGridLineWidth: 0,
						labels:
						{
							enabled: false
						},
						title: {
							text: null
						}
					}],
					legend: {
						verticalAlign: 'top',
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
						},
						allowPointSelect: false,
					},
					series: seriesData
				}
			},
			diarrhoeaTreatmentOptions: function(){
				var _this = this;
				return {
					title: null,
					chart: {
						type: 'column'
					},
					xAxis: {
						categories: ['Baseline', 'Supervision 2018']
					},
					yAxis: [{
						gridLineWidth: 0,
						minorGridLineWidth: 0,
						labels:
						{
							enabled: false
						},
						title: {
							text: null
						}
					}],
					series: [{
						showInLegend: false,
						name: 'Treatments',
						color: _this.diarrhoeaColor,
						data: [5,_this.treatmentData.diarrhoea.DIARRHOEA_ZINC_ORS] // sample data
					}]
				}
			},
			diarrhoeaTreatmentOptionsNew: function(){
				var _this = this;
				var seriesData = []
				var categories = _.map(this.data.all.diarrhoea, (object, key)=>{
					return key
				})

				categories.sort()

				_.each(categories, category => {
					var data = this.data.all.diarrhoea[category]
					seriesData.push(_.sumBy(data, 'ZINC_ORS'))
				})
				return {
					title: null,
					chart: {
						type: 'column'
					},
					xAxis: {
						categories: categories
					},
					yAxis: [{
						gridLineWidth: 0,
						minorGridLineWidth: 0,
						labels:
						{
							enabled: false
						},
						title: {
							text: null
						}
					}],
					series: [{
						showInLegend: false,
						name: 'Treatments',
						color: _this.diarrhoeaColor,
						data: seriesData // sample data
					}]
				}
			},
			variance: function(){
				var diarrhoeaClassificationVariance = this.classificationData.diarrhoea_class - this.diarrhoeaClassificationBaseline;
				var pneumoniaClassificationVariance = this.classificationData.pneumonia_class - this.pneumoniaClassificationBaseline;

				return {
					classification: {
						diarrhoea: _.round(diarrhoeaClassificationVariance),
						pneumonia: _.round(pneumoniaClassificationVariance)
					}
				}
			}
		}
	}
</script>