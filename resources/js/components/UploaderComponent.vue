<template>
	<div class="row">
		<div class="col-md-12">
			<div class="panel">
				<div class="panel-head">
					<h3 class="panel-title">
						<i class="ti-server"></i>&nbsp;&nbsp;The Data Uploader
					</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-6">
							<form>
								<legend>Supervision Details</legend>
								<div class="form-group">
									<label class="control-label">Supervision</label>
									<select class="form-control">
										<option value="1">Sample Supervision</option>
									</select>
								</div>

								<div class="form-group">
									<label class="control-label">County</label>
									<select class="form-control">
										<option value="1">Select a County</option>
									</select>
								</div>

								<button class="btn btn-sm btn-block btn-primary">Upload Data</button>
							</form>
						</div>
						<div class="col-md-6">
							<vue-dropzone id="dropzone" ref = "dropzone" :options="dropzoneOptions"></vue-dropzone>
						</div>
					</div>
					
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
				url: 'https://httpbin.org/post',
				thumbnailWidth: 200,
				addRemoveLinks: true,
				autoProcessQueue: false,
				acceptedFiles: "text/csv,application/vnd.ms-excel",
				dictDefaultMessage: '<div class="dz-icon"><i class="demo-pli-upload-to-cloud icon-5x"></i></div><div><span class="dz-text">Drop file to upload</span><p class="text-sm text-muted">or click to pick manually</p></div>',
				maxFiles: 1,
				init: function(){
					this.on("maxfilesexceeded", function(file){
						alert("You cannot upload more than 1 File");
						this.removeFile(file);
					})
				}
			}
		}
	},
	methods: {

	}
}
</script>