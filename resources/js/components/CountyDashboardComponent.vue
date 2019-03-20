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
							<highcharts :options="diarrhoeaClassificationOptions"></highcharts>
							<highcharts :options="diarrhoeaVarianceOptions"></highcharts>
							<highcharts :options="diarrhoeaTreatmentOptions"></highcharts>
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
				classificationData: {
					pneumonia_class: [],
					diarrhoea_class: [],
					baselineDiarrhoea: []
				},
				treatmentData: [],
				classificationCategories: [],
				treatmentCategories: [],
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
						data: em.classificationData.baselineDiarrhoea
					},{
						name: 'Supervision 2018',
						data: em.classificationData.diarrhoea_class
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
						data: variance
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
					series: [{
						name: 'Treatments',
						data: em.treatmentData
					}]
				}
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
					if(typeof(em.diarrhoeaBaselineData[v.county]) !== "undefined"){
						bsData = em.diarrhoeaBaselineData[v.county];
					}
					_diarBaseline.push(bsData); 
				});

				this.classificationCategories = _categories;
				this.classificationData.pneumonia_class = _pneuClassification;
				this.classificationData.diarrhoea_class = _diarClassification;
				this.classificationData.baselineDiarrhoea = _diarBaseline;
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
		},
	}
</script>