<template>
	<div class="row">
		<div class="col-md-12">
			<!-- <div class="card">
				<div class="card-body">
				</div>
			</div> -->
			<div>
				<b-card no-body>
					<b-tabs card>
						<b-tab title="Diarrhoea" active>
							<highcharts :options="diarrhoeaClassificationOptions"></highcharts>
							<highcharts :options="diarrhoeaVarianceOptions"></highcharts>
							<highcharts :options="diarrhoeaTreatmentOptions"></highcharts>
						</b-tab>
						<b-tab title="Pneumonia">
							<highcharts :options="pneumoniaClassificationOptions"></highcharts>
							<highcharts :options="pneumoniaVarianceOptions"></highcharts>
							<highcharts :options="pneumoniaTreatmentOptions"></highcharts>
						</b-tab>
					</b-tabs>
				</b-card>
			</div>
		</div>
	</div>	
</template>

<script type="text/javascript">

// import {Chart} from 'highcharts-vue'

	export default {
		data() {
			return {
				diarrhoeaColor: "#03A9F4",
				pneumoniaColor: "#66BB6A",
				baselineColor: "#BDBDBD",
				classificationData: {
					pneumonia_class: [],
					diarrhoea_class: [],
					baselineDiarrhoea: [],
					baselinePneumonia: []
				},
				treatmentData: [],
				classificationCategories: [],
				treatmentCategories: [],
				pneuTreatmentCategories: [],
				pneuTreatmentData: [],
				diarrhoeaBaselineData: {
					"Lamu": 36,
					"Machakos": 39,
					"Kilifi" : 55,
					"Nakuru" : 22,
					"Tana River": 23,
					"Kajiado": 43,
					"Murang'a": 26,
					"Kwale": 37,
					"Mombasa": 30,
					"Kiambu": 17,
					"Taita Taveta": 19
				},
				pneumoniaBaselineData: {
					"Lamu": 41,
					"Machakos": 50,
					"Kilifi" : 64,
					"Nakuru" : 42,
					"Tana River": 46,
					"Kajiado": 56,
					"Murang'a": 50,
					"Kwale": 50,
					"Mombasa": 58,
					"Kiambu": 30,
					"Taita Taveta": 26
				},
				pneumoniaTreatmentLabels: {
					AMOXDT: "Amox DT",
					AMOX_SYRUP: "Amox Syrup",
					INJECTIBLES: "Injectibles",
					CTX: "Amox CTX"
				}
			}
				
		},
		computed: {
			diarrhoeaClassificationOptions: function() {
				var em = this;
				return {
					title: {
						text: 'Diarrhoea Classifications'
					},
					chart: {
						type: 'column'
					},
					xAxis: {
						categories: em.classificationCategories
					},
					plotOptions: {
						column: {
							dataLabels: {
								enabled: true,
								format: "{point.y}%"
							}
						}
					},
					series: [{
						name: "Baseline",
						color: em.baselineColor,
						data: em.classificationData.baselineDiarrhoea
					},{
						name: 'Supervision 2018',
						color: em.diarrhoeaColor,
						data: em.classificationData.diarrhoea_class
					}]
				}
			},
			pneumoniaClassificationOptions: function(){
				var em = this;
				return {
					title: {
						text: 'Pneumonia Classifications'
					},
					chart: {
						type: 'column'
					},
					xAxis: {
						categories: em.classificationCategories
					},
					plotOptions: {
						column: {
							dataLabels: {
								enabled: true,
								format: "{point.y}%"
							}
						}
					},
					series: [{
						name: "Baseline",
						data: em.classificationData.baselinePneumonia,
						color: em.baselineColor
					},{
						name: 'Supervision 2018',
						data: em.classificationData.pneumonia_class,
						color: em.pneumoniaColor
					}]
				}
			},
			diarrhoeaVarianceOptions: function() {
				var em = this;
				var variance = [];
				_.forOwn(em.classificationData.diarrhoea_class, function(v,k){
					var diff = v - em.classificationData.baselineDiarrhoea[k];
					variance.push(diff);
				});

				return {
					title: {
						text: 'Diarrhoea Classification Variance'
					},
					chart: {
						type: 'column'
					},
					xAxis: {
						categories: em.classificationCategories
					},
					plotOptions: {
						column: {
							dataLabels: {
								enabled: true,
								format: "{point.y}%"
							}
						}
					},
					series: [{
						name: 'Variance',
						data: variance,
						color: em.baselineColor
					}]
				}
			},
			pneumoniaVarianceOptions: function() {
				var em = this;
				var variance = [];
				_.forOwn(em.classificationData.pneumonia_class, function(v,k){
					var diff = v - em.classificationData.baselinePneumonia[k];
					variance.push(diff);
				});

				return {
					title: {
						text: 'Pneumonia Classification Variance'
					},
					chart: {
						type: 'column'
					},
					xAxis: {
						categories: em.classificationCategories
					},
					plotOptions: {
						column: {
							dataLabels: {
								enabled: true,
								format: "{point.y}%"
							}
						}
					},
					series: [{
						name: 'Variance',
						data: variance,
						color: em.baselineColor
					}]
				}
			},
			diarrhoeaTreatmentOptions: function() {
				var em = this;

				return {
					title: {
						text: 'Diarrhoea Treatment'
					},
					chart: {
						type: 'column'
					},
					xAxis: {
						categories: em.treatmentCategories
					},
					plotOptions: {
						column: {
							dataLabels: {
								enabled: true,
								format: "{point.y}"
							}
						}
					},
					yAxis: {
						plotLines: [{
							color: "red",
							value: _.mean(em.treatmentData),
							width: 1,
							zIndex: 2,
							dataLabels: {
								enabled: true
							}
						}]
					},
					series: [{
						name: 'Treatments',
						data: em.treatmentData,
						color: em.diarrhoeaColor
					}]
				}
			},
			pneumoniaTreatmentOptions: function(){
				var _this = this;
				var seriesData = [];

				var _preSortedArray = [];

				_.forOwn(_this.pneuTreatmentData, function(v, k){
					_.forOwn(_this.pneumoniaTreatmentLabels, function(label, key){
						if(typeof(_preSortedArray[label]) === "undefined"){
							_preSortedArray[label] = [];
						}
						_preSortedArray[label].push(v[key]);
					});
				});

				_.forOwn(_preSortedArray, function(data, label){
					var _obj = {};

					_obj = {
						name: label,
						data: data
					}

					seriesData.push(_obj);
				});

				return {
					title: {
						text: 'Pneumonia Treatment'
					},
					chart: {
						type: 'column'
					},
					xAxis: {
						categories: _this.pneuTreatmentCategories
					},
					plotOptions: {
						column: {
							stacking: 'percent',
							dataLabels: {
								enabled: true,
								format: "{point.percentage:.0f}%"
							}
						}
					},
					series: seriesData
				};
			}
		},
		created() {
			axios.get('/api/data/county/diarrhoea/classification')
			.then((response) => {
				var data = response.data;

				var _pneuClassification = [];
				var _diarClassification = [];
				var _pneuBaseline = [];
				var _diarBaseline = [];
				var _categories = [];

				var em = this;

				_.forOwn(data, function(v, k){
					_categories.push(v.county);
					_pneuClassification.push(_.round((v.SUM_PN_CLASSIFICATION / (v.SUM_PN_CLASSIFICATION + v.SUM_PN_NO_CLASSIFICATION)) * 100));
					_diarClassification.push(_.round((v.SUM_DIA_CLASSIFICATION / (v.SUM_DIA_CLASSIFICATION + v.SUM_DIA_NO_CLASSIFICATION)) * 100));
					var bsData = 0;
					var pnBsData = 0;

					if(typeof(em.diarrhoeaBaselineData[v.county]) !== "undefined"){
						bsData = em.diarrhoeaBaselineData[v.county];
					}

					if(typeof(em.pneumoniaBaselineData[v.county]) !== "undefined"){
						pnBsData = em.pneumoniaBaselineData[v.county];
					}
					_diarBaseline.push(bsData);
					_pneuBaseline.push(pnBsData);
				});

				this.classificationCategories = _categories;
				this.classificationData.pneumonia_class = _pneuClassification;
				this.classificationData.diarrhoea_class = _diarClassification;
				this.classificationData.baselineDiarrhoea = _diarBaseline;
				this.classificationData.baselinePneumonia = _pneuBaseline;
			});

			axios.get('/api/data/county/diarrhoea/treatment')
			.then((response) => {
				var _categories = [];
				var _actualData = [];
				_.forOwn(response.data, function(v, k){
					_categories.push(v.county);
					_actualData.push(v.DIARRHOEA_ZINC_ORS);
				});

				this.treatmentCategories = _categories;
				this.treatmentData = _actualData;
			});

			axios.get('/api/data/county/pneumonia/treatment')
			.then((response) => {
				var _categories = [];
				var _actualData = [];
				_.forOwn(response.data, function(v, k){
					_categories.push(v.county);
				});

				this.pneuTreatmentCategories = _categories;
				this.pneuTreatmentData = response.data;
			});
		},
	}
</script>