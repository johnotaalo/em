<template>
	<div>
		<div class="row">
			<div class="col-md">
				<vue-dropzone id="dropzone" ref = "dropzone" :options="dropzoneOptions"></vue-dropzone>	
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
					location.reload();
				}
			}
		}
	},
	methods: {
		submitUpload: function() {
			this.$refs.dropzone.processQueue();
		},
		downloadTemplate: function(){
			window.location.href = "/templates/supervision_data_template.csv"
		}
	}
}
</script>