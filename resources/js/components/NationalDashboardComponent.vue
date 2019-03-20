<template>
	<div>
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
</template>

<script type="text/javascript">

// import {Chart} from 'highcharts-vue'

	export default {
		data() {
			return {
				diarrhoeaTreatmentOptions: {
					title: {
						text: 'Diarrhoea Treatment'
					},
					chart: {
						type: 'column'
					},
					xAxis: {
						categories: ['Baseline', 'Supervision 2018']
					},
					series: [{
						name: 'Classifications',
						data: [5,15] // sample data
					}]
				},
				classificationData: {
					pneumonia_class: 0,
					diarrhoea_class: 0
				},
				treatmentData: {
					diarrhoea: null,
					pneumonia: null
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
					CTX: "Amox CTX"
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
		},
		computed: {
			pneumoniaClassificationOptions: function(){
				return {title: {
										text: 'Pneumonia Classification'
									},
									chart: {
										type: 'column'
									},
									xAxis: {
										categories: ['Baseline', 'Supervision 2018']
									},
									series: [{
										name: 'Classifications',
										bar: {color: "green"},
										data: [47,this.classificationData.pneumonia_class] // sample data
									}]};
			},
			diarrhoeaClassificationOptions: function(){
					return {title: {
											text: 'Diarrhoea Classifications'
										},
										chart: {
											type: 'column'
										},
										xAxis: {
											categories: ['Baseline', 'Supervision 2018']
										},
										series: [{
											name: 'Classifications',
											data: [32,this.classificationData.diarrhoea_class] // sample data
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
				}
			}
		}
	}
</script>