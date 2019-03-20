<template>
	<div class="row">
		<div class="col-md-12">
			<div class="panel">
				<div class="panel-body">
					<h3>County Dashboard</h3>
					<div class="row">
						<div class="col-md-6">
							<highcharts :options="diarrhoeaClassificationOptions"></highcharts>
						</div>
						<div class="col-md-6">
							<highcharts :options="pneumoniaClassificationOptions"></highcharts>
						</div>
						<div class="col-md-4">
							<highcharts :options="diarrhoeaTreatmentOptions"></highcharts>
						</div>
						<div class="col-md-4">
							<highcharts :options="pneumoniaTreatmentOptions"></highcharts>
						</div>
						<div class="col-md-4">
							<highcharts :options="pneumoniaTreatmentOptions"></highcharts>
						</div>
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
				diarrhoeaClassificationOptions: {
					title: {
						text: 'Diarrhoea Classification'
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
						data: [7,9] // sample data
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
		mounted() {
			console.log('Component mounted.')
		},
	}
</script>