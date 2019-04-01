<template>
	<div>
		
		<div class="row">
			<div class="col-md-2">
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

								<span class="badge badge-soft-warning mt-n1">
								+0%
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

								<span class="badge badge-soft-warning mt-n1">
								+0%
								</span>

							</div>
							<div class="col-auto">

								<span class="h2 fa fa-hospital text-muted mb-0"></span>

							</div>
						</div>

					</div>
				</div>
						
				<div class="card">
					<div class="card-body">
						<div class="row align-items-center">
							<div class="col">

								<h6 class="card-title text-uppercase text-muted mb-2">
									Facilities Breakdown
								</h6>

								<span class="h2 mb-0">
									{{ facilities }}
								</span>

								<span class="badge badge-soft-warning mt-n1">
								+0%
								</span>

							</div>
							<div class="col-auto">

								<span class="h2 fe fe-grid text-muted mb-0"></span>

							</div>
						</div>
						<hr>
					</div>
				</div>
			</div>
			<div class="col-md-5">
				<div class="card">
					<div class="card-body">
						<highmaps :options="mapData" style="height: 600px;"></highmaps>
					</div>
					
				</div>
				
			</div>
			<div class="col-md-5">
				<div class="row" style="margin-bottom: 10px;">
					<div class="col-md-6">
						<div class="card">
							<div class="card-body">
								<highcharts :options="diarrhoeaClassificationOptions"></highcharts>	
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card">
							<div class="card-body">
								<highcharts :options="diarrhoeaTreatmentOptions"></highcharts>
							</div>
							
						</div>
					</div>
				</div>
				<div class="row">		
					<div class="col-md-6">
						<div class="card">
							<div class="card-body">
								<highcharts :options="pneumoniaClassificationOptions"></highcharts>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card">
							<div class="card-body">
								<highcharts :options="pneumoniaTreatmentOptions"></highcharts>
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
				countyData: [],
				diarrhoeaColor: "#03A9F4",
				pneumoniaColor: "#66BB6A",
				baselineColor: "#BDBDBD",
				facilities: 0,
				counties: 0,
				classificationData: {
					pneumonia_class: 0,
					diarrhoea_class: 0
				},
				treatmentData: {
					diarrhoea: {},
					pneumonia: {}
				},
				baselineData: {
					AMOXDT: 4,
					AMOX_SYRUP: 42,
					INJECTIBLES: 29,
					CTX: 24
				},
				pneumoniaTreatmentLabels: {
					AMOXDT: "Amox DT",
					AMOX_SYRUP: "Amox Syrup",
					INJECTIBLES: "Injectibles",
					CTX: "CTX"
				}
			}
		},
		mounted() {
			axios.get('/api/data/classification')
			.then((response) => {
				var data = response.data;
				this.classificationData.pneumonia_class = (data.pneumonia.total_cases / (data.pneumonia.total_cases + data.pneumonia.no_class)) * 100;
				this.classificationData.diarrhoea_class = (data.diarrhoea.total_cases / (data.diarrhoea.total_cases + data.diarrhoea.no_class)) * 100;
			});

			axios.get('/api/data/treatments/pneumonia')
			.then((response) => {
				var data = response.data;
				this.treatmentData.pneumonia = data;
			});

			axios.get('/api/data/treatments/diarrhoea')
			.then((response) => {
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

			axios.get('/api/data/countyData')
			.then((response) => {
				var data = response.data
				this.countyData = _.map(data, (county) => ([ _.toUpper(county), 1 ]))
			});
		},
		computed: {
			mapData: function(){
				return {
		          chart: {
		            map: json
		          },

		          title: {
		            text: 'County Coverage'
		          },
		          mapNavigation: {
		            enabled: true,
		            buttonOptions: {
		                verticalAlign: 'bottom'
		            }
		          },

		          colorAxis: {
		            dataClasses: [{
		              from: 1,
		              to: 2,
		              color: "#FFC107"
		            }]
		          },

		          series: [{
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
		              return "<b>"+this.point.COUNTY_NAM+"</b>"
		            }
		          }
		        }
			},
			pneumoniaClassificationOptions: function(){
				var em = this;
				return {
					title: {
						text: 'Pneumonia Classification'
					},
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
						data: [47,_.round(this.classificationData.pneumonia_class)] // sample data
					}]
				};
			},
			diarrhoeaClassificationOptions: function(){
				var em = this;
					return {
						title: {
											text: 'Diarrhoea Classification'
										},
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
											data: [32,_.round(this.classificationData.diarrhoea_class)] // sample data
										}]}
			},
				pneumoniaTreatmentOptions: function(){
					var _this = this;
					var seriesData = [];
					_.forOwn(this.treatmentData.pneumonia, function(v, k){
						var obj = {};
						obj = {
							name: _this.pneumoniaTreatmentLabels[k],
							data: [_this.baselineData[k], v]
						};

						seriesData.push(obj);
					});
				return {
					title: {
						text: 'Pneumonia Treatment'
					},
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
							stacking: 'percent',
							dataLabels: {
								enabled: true,
								format: "{point.percentage:.0f}%"
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
					title: {
						text: 'Diarrhoea Treatment'
					},
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
			}
		}
	}
</script>