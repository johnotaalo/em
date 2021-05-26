<template>
	<div>
        <div class="alert alert-warning alert-dismissable" role="alert" v-if="temporaryListing > 0">
            There are some facilities in the temporary listing. Click <a href="/data/facilities/uploads/temporary">here</a> to manage them
        </div>
		<div class="card">
			<div class="card-header">
				<div class="row align-items-center">
					<div class="col">
						<form class="row align-items-center">
							<div class="col-auto pr-0">
								<span class="fe fe-search text-muted"></span>
							</div>

							<div class="col">
								<b-input type="search" class="form-control form-control-flush search" v-model = "searchTerm" placeholder="Search" v-on:input="applySearchFilter(searchTerm)"/>
							</div>
						</form>
					</div>
					<div class="col-auto">
						<b-button size="sm" variant="success" v-b-modal.upload-listing><i class="fe fe-upload"></i>&nbsp;Upload Facility Listing</b-button>&nbsp;
						<a class= "btn btn-sm btn-primary float-right" :href="`${baseUrl}/data/facilities/add`"><i class="fe fe-plus-circle"></i>&nbsp;Add Facility</a>
					</div>
				</div>
			</div>
			<div class="card-title">
				<v-server-table
				ref="facilitiesTable"
				class="table-sm table-nowrap card-table"
				:columns="table.columns"
				:options="table.options"
                @loaded="onLoaded"
				size="sm">
					<template slot="STATUS" slot-scope="data">
                        <b-form-checkbox switch size="sm" v-model="checked" :value="data.row.SURVEY_CTO_ID" @change="updateRowStatus(data.row.SURVEY_CTO_ID)"></b-form-checkbox>
					</template>

                    <template slot="ACTIONS" slot-scope="data">
                        <b-dropdown text="Actions" variant="primary" size="sm">
                            <b-dropdown-item :href="`/data/facilities/edit/${data.row.SURVEY_CTO_ID}`"><i class="fe fe-edit-3"></i>&nbsp;&nbsp;Edit</b-dropdown-item>
<!--                            <b-dropdown-item><i class="fe fe-trash"></i>&nbsp;&nbsp;Delete</b-dropdown-item>-->
                        </b-dropdown>
                    </template>
				</v-server-table>
			</div>
		</div>

        <b-modal title="Upload Facility Listing" size="lg" id="upload-listing" ok-title="Upload Listing" @ok="handleUpload">
            <loading :active.sync="dataUploading" :color="loaderColor" :can-cancel="false"
                     :is-full-page="false"></loading>
            <vue-dropzone id="dropzone" ref = "dropzone" :options="dropzoneOptions" @vdropzone-sending="sendingEvent"></vue-dropzone>
            <p class="mt-2 text-center">Download the template here: <a href="/templates/facility_upload_template.xlsx">Facility Upload Template (Excel)</a></p>
        </b-modal>
	</div>
</template>

<script type="text/javascript">
	import VueTables from 'vue-tables-2'
	const Event = VueTables.Event

    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'

	export default{
	    components: {
            vueDropzone: vue2Dropzone
        },
		data(){
	        var em = this
			return {
				searchTerm: "",
                checked: [],
				baseUrl: window.Laravel.baseUrl,
                temporaryListing: 0,
                dataUploading: false,
                loaderColor: "#2196F3",
				table: {
					columns: [
						'SURVEY_CTO_ID',
						'FACILITY_NAME',
						'COUNTY',
						'SUB_COUNTY',
						'FACILITY_TYPE',
						'STATUS',
						'ACTIONS'
					],
					options: {
						perPage: 50,
						perPageValues: [],
						filterable: false,
						customFilters: ['normalSearch'],
						columnsDropdown: false,
						sortIcon: {
							base: 'icon',
							up: 'icon-sort-up',
							down: 'icon-sort-down',
							is: 'icon-sort'
						},
						pagination: {
							nav: "fixed",
							dropdown: false,
							edge: true,
						},
						requestFunction: (data) => {
							return axios.get(`/api/data/facility-listing`, {
								params: data
							})
							.catch(function (e) {
								console.log('error', e);
							}.bind(this));
						}
					}
				},
                dropzoneOptions: {
				    url: "/api/data/facility/upload-listing",
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
                            // em.uploadBtnDisplay = true
                        })

                        this.on("removedfile", function(file){
                            // em.uploadBtnDisplay = false
                        })
                    },
                    success: function(){
				        em.dataUploading = false
				        em.$swal('Success', 'Successfully uploaded facility listing', 'success')
                        .then(() => {
                            window.location = "/data/facilities/uploads/temporary"
                        })
                    }
                }
			}
		},
		mounted(){
	        this.getTemporaryListing()
		},
		methods: {
            sendingEvent: function (file, xhr, formData) {
                var data = {};
                formData = this.createFormData(data, formData)
            },
			applySearchFilter: _.debounce(function(e){
				Event.$emit('vue-tables.filter::normalSearch', e);
			}, 500),
            onLoaded(data){
			    var tableData = data.data.data

                _.forOwn(tableData, (row) => {
                    if(row.STATUS === "1"){
                        this.checked.push(row.SURVEY_CTO_ID)
                    }
                })
            },
            updateRowStatus(id){
			    axios.put(`/api/data/facility/update-status/${id}`)
                .then(res => {
                    console.log("Successfully updated facility")
                })
                .catch(error => {
                    alert("There was an error updating facility")
                })
            },
            handleUpload: function (bvModalEvt){
                bvModalEvt.preventDefault()
                let filesCount = this.$refs.dropzone.dropzone.files.length
                if(filesCount > 0) {
                    this.dataUploading = true
                    this.$refs.dropzone.processQueue()
                }else {
                    alert("Please upload a file...")
                }
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
            },
            getTemporaryListing(){
                axios.get('/api/data/facility-temporary-listing-count')
                .then(res => {
                    this.temporaryListing = res.data.count
                });
            }
		}
	}
</script>
