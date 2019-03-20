<template>
	<div>
	<div class="row" style="margin-bottom: 10px;">
		<div class="col-md-6">
			<div class="card">
				<div class="card-body">
					<p>Total Data: {{ classificationData.pneumonia_class }}</p>
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
				classificationData: {
					pneumonia_class: 0,
					diarrhoea_class: 0
				},
				diarrhoeaClassificationOptions: {
					title: {
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
						data: [1,2] // sample data
					}]
				},
				pneumoniaClassificationOptions: {
					title: {
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
						data: [5,15] // sample data
					}]
				},
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
				pneumoniaTreatmentOptions: {
					title: {
						text: 'Pneumonia Treatment'
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
				}
			}
		},
		created() {
			axios.get('/api/data/classification')
			.then(function(response){
				var data = response.data;
				this.classificationData.pneumonia_class = (data.pneumonia.total_cases / (data.pneumonia.total_cases + data.pneumonia.no_class)) * 100;
				this.classificationData.diarrhoea_class = (data.diarrhoea.total_cases / (data.diarrhoea.total_cases + data.diarrhoea.no_class)) * 100;
			});
		}
	}
</script>