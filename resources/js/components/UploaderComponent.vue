<template>
	<div>
		<loading :active.sync="isLoading" color="#2196F3" :can-cancel="false" :is-full-page="fullPage"></loading>
		<div class="row">
			<div class="col-md">
				<b-card title="Data Upload">
					<div class="form-group">
						<b-form-group label="Please select assessment type">
							<b-form-select v-model="form.assessmentType" name="assessmentType" :options="assessmentTypes"></b-form-select>
						</b-form-group>
					</div>
					<div class="form-group">
						<b-form-group label="Pick the number of counties that the data belongs to">
							<b-form-radio v-model="uploadType" name="no-counties" value="single">Single County</b-form-radio>
							<b-form-radio v-model="uploadType" name="no-counties" value="multiple">Multiple Counties</b-form-radio>
						</b-form-group>
					</div>

					<div class="form-group">
						<label class="control-label">Select county</label>
						<v-select v-model="form.county" :multiple="multiselect" label="text" :options="selectCounties"></v-select>
					</div>

					<div class="form-group"> 
						<label class="control-label">Select the period <span class="text-danger" v-if="form.assessmentType == 3">when the budget was approved</span></label>
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
						<!-- <b-form-text id="password-help-block" v-if="form.assessmentType == 3">
							This is only applicable for facility supervision activities
						</b-form-text> -->
					</div>
				</b-card>
			</div>
			<div class="col-md">
				<p>Place the mouse over the blue area to remove the file.</p>
				<vue-dropzone id="dropzone" ref = "dropzone" :options="dropzoneOptions" @vdropzone-sending="sendingEvent"></vue-dropzone>

				<div class="mt-3 float-right">
					<b-button variant="outline-success" @click="downloadTemplate">Download CSV Template</b-button>
					<b-button variant="info" @click="submitUpload" v-if="uploadBtnDisplay">Upload Data</b-button>
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
		var em = this
		return {
			dropzoneOptions: {
				url: '/api/data/uploadData',
				thumbnailWidth: 200,
				addRemoveLinks: true,
				autoProcessQueue: false,
				timeout: 0,
				acceptedFiles: "text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
				dictDefaultMessage: '<div class="dz-icon"><i class="demo-pli-upload-to-cloud" style="font-size: 5em;"></i></div><div><span class="dz-text">Drop file to upload</span><p class="text-sm text-muted">or click to pick manually</p><p><strong>Accepted file formats (.csv and .xlsx)</strong></p></div>',
				maxFiles: 1,
				init: function(){
					this.on("maxfilesexceeded", function(file){
						alert("You cannot upload more than 1 File");
						this.removeFile(file);
					})

					this.on("addedfile", function(file){
						em.uploadBtnDisplay = true
					})

					this.on("removedfile", function(file){
						em.uploadBtnDisplay = false
					})
				},
				success: function(){
					this.isLoading = false
					location.reload();
				}
			},
			uploadBtnDisplay: false,
			isLoading: false,
			fullPage: true,
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
			this.isLoading = true
			var data = this.form.data();
			formData = this.createFormData(data, formData)
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
			var initial = 2015;
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