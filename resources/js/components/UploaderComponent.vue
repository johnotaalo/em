<template>
	<div>
		<div class="row">
			<div class="col-md">
				<b-card title="Submission Details">
					<div class="form-group">
						<b-form-group label="Please select assessment type">
							<b-form-select v-model="form.assessmentType" name="assessmentType" :options="assessmentTypes"></b-form-select>
						</b-form-group>
					</div>
					<div class="form-group">
						<b-form-group label="Pick the number of counties that the data belongs to">
							<b-form-radio v-model="uploadType" name="no-counties" value="single">1 County</b-form-radio>
							<b-form-radio v-model="uploadType" name="no-counties" value="multiple">Multiple Counties</b-form-radio>
						</b-form-group>
					</div>

					<div class="form-group">
						<label class="control-label">Select county</label>
						<v-select v-model="form.county" :multiple="multiselect" label="text" :options="selectCounties"></v-select>
					</div>

					<div class="form-group"> 
						<label class="control-label">Select the period</label>
						<div class="row">
							<div class="col-sm">
								<label class="control-label">Month</label>
								<b-form-select v-model="form.duration.month" :options="selectMonths"></b-form-select>
							</div>
							<div class="col-sm">
								<label class="control-label">Year</label>
								<b-form-select v-model="form.duration.year" :options="years"></b-form-select>
							</div>
						</div>
					</div>
				</b-card>
			</div>
			<div class="col-md">
				<vue-dropzone id="dropzone" ref = "dropzone" :options="dropzoneOptions" @vdropzone-sending="sendingEvent"></vue-dropzone>	
			</div>
		</div>
		<div class="row mt-3">
			<div class="col-md">
				<div class="float-right">
					<b-button variant="outline-info" @click="submitUpload">Upload Data</b-button>
					<b-button variant="outline-success" @click="downloadTemplate">Download CSV Template</b-button>
				</div>
				
			</div>
		</div>
	</div>
</template>

<script type="text/javascript">
import vue2Dropzone from 'vue2-dropzone'

import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import Form from '../core/Form'

export default {
	components: {
		vueDropzone: vue2Dropzone
	},
	data(){
		return {
			dropzoneOptions: {
				url: '/api/data/uploadData',
				thumbnailWidth: 200,
				addRemoveLinks: true,
				autoProcessQueue: false,
				acceptedFiles: "text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
				dictDefaultMessage: '<div class="dz-icon"><i class="demo-pli-upload-to-cloud" style="font-size: 5em;"></i></div><div><span class="dz-text">Drop file to upload</span><p class="text-sm text-muted">or click to pick manually</p></div>',
				maxFiles: 1,
				init: function(){
					this.on("maxfilesexceeded", function(file){
						alert("You cannot upload more than 1 File");
						this.removeFile(file);
					})
				},
				success: function(){
					// location.reload();
				}
			},
			counties: [],
			assessmentTypes: [],
			uploadType: "multiple",
			months: ["January", "February", "March", 'April', "May", "June", "July", "August", "September", "October", "November", "December"],
			form: new Form({
				assessmentType: "",
				county: "",
				duration: {
					month: "",
					year: ""
				}
			})
		}
	},
	created() {
		this.getCounties();
		this.getAssessmentTypes();
	},
	methods: {
		submitUpload: function() {
			this.$refs.dropzone.processQueue();
		},
		downloadTemplate: function(){
			window.location.href = "/templates/supervision_data_template.csv"
		},
		getCounties: function(){
			var _this = this;
			axios.get("/api/data/counties")
			.then((res) => {
				_this.counties = res.data;
			});
		},
		getAssessmentTypes: function(){
			var _this = this;
			axios.get("/api/data/assessmentTypes")
			.then((res) => {
				_this.assessmentTypes = _.map(res.data, item => ({ value: item.id, text: item.assessment_type }))
			});
		},
		sendingEvent: function (file, xhr, formData) {
			var data = this.form.data();
			formData = this.createFormData(data, formData)
			console.log(formData);
		},

		createFormData(data, formData = null, previousKey = null){
			if (!formData) { formData = new FormData() }

			if (data instanceof Object) {
				Object.keys(data).forEach(key => {
					const value = data[key]

					if (previousKey) {
						key = `${previousKey}[${key}]`
					}

					if (value instanceof Object && !Array.isArray(value)) {
						this.createFormData(value, formData, key)
					}

					if (Array.isArray(value)) {
						value.forEach((val, index) => {
							let arrayIndex = index
							if (val.id) { arrayIndex = val.id }

							this.createFormData(val, formData, key + `[${arrayIndex}]`)
							// formData.append(`${key}[]`, val);
						})
					} else if (value instanceof Blob) {
						formData.append(key, value)
					} else if (!(value instanceof Object)) {
						formData.append(key, value)
					}
				})

				return formData
			}
		}
	},
	computed: {
		years: function() {
			var dt = new Date();
			var initial = 2017;
			var thisYear = dt.getFullYear();

			var allYears = [];

			for (var i = initial; i <= thisYear; i++) {
				allYears.push({ value: i, text: i });
			}

			return allYears;
		},
		selectCounties: function(){
			var selectCounties = _.map(this.counties, item => ({ value: item.county, text: item.county }))

			return selectCounties
		},
		selectMonths: function(){
			var selectMonths = _.map(this.months, item => ({ value: item, text: item }))
			return selectMonths
		},
		multiselect: function(){
			return ( this.uploadType == "single" ) ? false : true;
		}
	}
}
</script>