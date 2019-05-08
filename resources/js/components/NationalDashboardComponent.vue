<template>
	<div>
		<div class="col">
			<div class="col-md">
				<small style="font-size: .7rem">This page shows data for at least 3 facility assessments.</small>
			</div>
		</div>
		<div class="row">
			<!-- <div class="col-md-1">
				
			</div> -->
			<div class="col-md-4">
				<div class="row">
					<div class="col">
						<div class="card">
							<div class="card-header">
								<h4 class="card-header-title">
								COUNTY COVERAGE
								</h4>
								<small class="text-muted">Click on a county to view analytics</small>
							</div>
							<div class="card-body">
								<center><small style="font-size: .7rem"><span v-for="(breakdown, key) in facilityBreakdown" :key="key">{{ breakdown.type | pluralize(breakdown.count) }} {{ breakdown.count }} | </span></small></center>
								<loading :active.sync="mapLoading" :color="loaderColor" :can-cancel="false" :is-full-page="false"></loading>
								<highmaps :options="mapData" style="height: 400px;"></highmaps>
							</div>
							
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col">
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
					</div>

					<div class="col">
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
				

				
						
				<!-- <div class="card">
					<div class="card-body">
						<div class="row align-items-center">
							<div class="col">

								<h6 class="card-title text-uppercase text-muted mb-2">
									Facilities Breakdown
								</h6>

								<span class="h2 mb-0">
									{{ facilities }}
								</span>
							</div>
							<div class="col-auto">

								<span class="h2 fe fe-grid text-muted mb-0"></span>

							</div>
						</div>

						<div class="row">
							<div class="col">
								<b-list-group>
									<b-list-group-item v-for="(breakdown, key) in facilityBreakdown" class="d-flex justify-content-between align-items-center" :key="key">
										{{ breakdown.type | pluralize(breakdown.count) }}
										<b-badge variant="primary" pill>{{ breakdown.count }}</b-badge>
									</b-list-group-item>
								</b-list-group>
							</div>
							
						</div>
					</div>
				</div> -->
				
			</div>
			<div class="col-md-8">
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
								<highcharts :options="pneumoniaClassificationOptions" style="height: 300px;"></highcharts>
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
										<highcharts :options="diarrhoeaClassificationOptions" style = "height: 200px;"></highcharts>	
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
										<highcharts :options="diarrhoeaTreatmentOptions" style = "height: 200px;"></highcharts>
									</div>
									
								</div>
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
								<highcharts :options="pneumoniaTreatmentOptions" style="height: 600px;"></highcharts>
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
					CTX: "#9E9E9E",
					INJECTABLES: "#FF9800",
					OXYGEN: "#7C4DFF",
					OTHER: "#FFFF00",
					
				}
			}
		},
		mounted() {
			this.classificationLoading = true;
			axios.get('/api/data/classification')
			.then((response) => {
				this.classificationLoading = false;
				var data = response.data;
				this.classificationData.pneumonia_class = (data.pneumonia.total_cases / (data.pneumonia.total_cases + data.pneumonia.no_class)) * 100;
				this.classificationData.diarrhoea_class = (data.diarrhoea.total_cases / (data.diarrhoea.total_cases + data.diarrhoea.no_class)) * 100;
			});

			this.pneumoniaTreatmentLoading = true;
			axios.get('/api/data/treatments/pneumonia')
			.then((response) => {
				console.log(response)
				var data = response.data;
				this.treatmentData.pneumonia = data;
				this.pneumoniaTreatmentLoading = false;
			});

			this.diarrhoeaTreatmentLoading = true
			axios.get('/api/data/treatments/diarrhoea')
			.then((response) => {
				this.diarrhoeaTreatmentLoading = false
				var data = response.data;
				this.treatmentData.diarrhoea = data;
			});

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
				this.countyData = _.map(data, (county) => ([ _.toUpper(county.county), county.facilities.length ]))
			});
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
		          					console.log(this);
		          				}
		          			}
		          		}
		          	}
		          },

		          series: [{
		          	showInLegend: false,
		            data: this.countyData,
		            keys: ["COUNTY_NAM", "value"],
		            name: 'County data',
		            joinBy: 'COUNTY_NAM',
		            states: {
		              hover: {
		                color: '#FFE082'
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