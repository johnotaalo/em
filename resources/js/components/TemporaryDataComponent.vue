<template>
	<div>
		<b-alert variant="warning" :show="warningShow">
			<span>The system can only upload 500 rows at a time. After this upload, you will be required to upload the next batch.</span>
		</b-alert>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md">
						<b-button>The current file upload has {{ counties.length }} counties (Hover for more details)</b-button>
						<!-- <div v-if="differences.counties.length || differences.uploadCounties.length">
							<p class="text-danger">Counties not matching with those selected</p>
						</div> -->
					</div>
					<div class="col-md">
						<div class="float-right">
							<b-button size="sm" variant="danger" @click="cancelUpload">Cancel Upload & Re-start</b-button>
							<b-button size="sm" variant="info" @click="uploadData">Upload Data</b-button>
						</div>
						
					</div>
				</div>
			</div>
			
		</div>
		<div class="card">
			<!-- <div class="card-body"> -->
				<v-client-table class = "table-sm table-card" v-if="!islegacy" small :data="filteredData" :columns="columns" :options="options"></v-client-table>
				<v-client-table v-if="islegacy" small :data="filteredData" :columns="legacyColumns" :options="options"></v-client-table>
			<!-- </div> -->
		</div>

		<loading :active.sync="isLoading" color="#2196F3" :can-cancel="false" :is-full-page="true"></loading>
		
		<b-modal id="modal-counties" title="BootstrapVue">
					<p class="my-4">Hello from modal!</p>
				</b-modal>
	</div>
</template>

<script type="text/javascript">
	export default{
		props: {
			islegacy: { type: null, default: null }
		},
		data() {
			return {
				warningShow: false,
				uploaded: 0,
				temporaryData: [],
				upload: "",
				differences: {
					counties: [],
					uploadCounties: []
				},
				options: {
					filterable: false,
					perPageValues: [],
					perPage: 500
				},
				isBusy: false,
				isLoading: false,
				columns: [
				'supname',
				'county',
				'sub_county',
				'facility_name',
				'sev_cases',
				'sev_amoxdt',
				'sev_amoxsy',
				'sev_oxygen',
				'sev_ctx',
				'sev_benz',
				'sev_anti',
				'sev_other',
				'sev_notx',
				'pneu_cases',
				'pn_amox',
				'pn_amoxsy',
				'pn_oxygen',
				'pn_ctx',
				'pn_benz',
				'pn_gent',
				'pn_benzgent',
				'pn_anti',
				'pn_other',
				'pn_notx',
				'noc_cases',
				'noc_amox',
				'noc_amoxsy',
				'noc_oxygen',
				'noc_ctx',
				'noc_benz',
				'noc_gent',
				'noc_benzgent',
				'noc_anti',
				'noc_other',
				'noc_notx',
				'noclass_cases',
				'noclass_amox',
				'noclass_amoxsy',
				'noclass_oxygen',
				'noclass_ctx',
				'noclass_benz',
				'noclass_gent',
				'noclass_benzgent',
				'noclass_anti',
				'noclass_other',
				'noclass_notx',
				'd_shock_cases',
				'd_shock_cop',
				'd_shock_zinc',
				'd_shock_ors',
				'd_shock_iv',
				'd_shock_a',
				'd_shock_other',
				'd_shock_no',
				'd_sev_cases',
				'd_sev_cop',
				'd_sev_zinc',
				'd_sev_ors',
				'd_sev_iv',
				'd_sev_a',
				'd_sev_other',
				'd_sev_no',
				'd_some_cases',
				'd_some_cop',
				'd_some_zinc',
				'd_some_ors',
				'd_some_iv',
				'd_some_a',
				'd_some_other',
				'd_some_no',
				'd_nodehy_cases',
				'd_nodehy_cop',
				'd_nodehy_zinc',
				'd_nodehy_ors',
				'd_nodehy_iv',
				'd_nodehy_a',
				'd_nodehy_other',
				'd_nodehy_no',
				'd_dys_cases',
				'd_dys_cop',
				'd_dys_zinc',
				'd_dys_ors',
				'd_dys_iv',
				'd_dys_a',
				'd_dys_other',
				'd_dys_no',
				'd_noclass_cases',
				'd_noclass_cop',
				'd_noclass_zinc',
				'd_noclass_ors',
				'd_noclass_iv',
				'd_noclass_a',
				'd_noclass_other',
				'd_noclass_no'],
				legacyColumns: [
					'county',
					'sub_county',
					'facility_name',
					'facility_code',
					'sum_tx_ors_zn',
					'sum_tx_ors',
					'sum_tx_zn',
					'sum_tx_antibiotics',
					'sum_other',
					'sum_tx_none',
					'sum_no_dehydration',
					'sum_some_dehydration',
					'sum_sev_dehydration',
					'sum_shock',
					'sum_dysentry',
					'sum_no_class',
					'sum_tx_amox',
					'sum_tx_cotri',
					'sum_tx_benz',
					'sum_tx_genta',
					'sum_tx_chlor',
					'sum_tx_pn_other',
					'sum_tx_pn_none',
					'sum_no_pneu',
					'sum_pneu',
					'sum_sev_pneu',
					'sum_very_sev_dis',
					'sum_no_pn_class'
				],
				selected : []
			}
		},
		mounted() {
			if (this.islegacy) {
				this.getLegacyTemporaryData();
			}else{
				this.getTemporaryData();
			}
		},
		computed: {
			counties: function(){
				var counties = _.sortBy(this.getUniqueValuesOfKey(this.temporaryData, "county"))
				var selectedUploadCounties = _.split(this.upload.counties, ", ")
				selectedUploadCounties = _.sortBy(selectedUploadCounties)

				var differenceCounties = _.difference(counties, selectedUploadCounties)
				var differenceUploadCounties = _.difference(selectedUploadCounties, counties)

				this.differences.counties = differenceCounties
				this.differences.uploadCounties = differenceUploadCounties
				var sortedCounties = [];
				_.forOwn(counties, (county, key)=>{
					sortedCounties.push({ text: county + " ( 2 Previous Supervisions)", value: county })
				});

				return sortedCounties;
			},

			filteredData: function(){
				var em = this;
				if(this.selected.length == 0){
					return this.temporaryData;
				}
				else{
					var data = _.find(this.temporaryData, (o) => {
						return o.county == "Mombasa"
					});
					// console.log(data);
					return data;
				}
			}
			
		},
		methods: {
			getTemporaryData(){
				this.isLoading = true;
				axios.get('/api/data/temporary')
				.then((res) => {
					this.isLoading = false;
					this.temporaryData = res.data.temporaryData
					this.upload = res.data.upload

					this.warningShow = this.temporaryData.length > 500
				});
			},
			getLegacyTemporaryData(){
				this.isLoading = true;
				axios.get('/api/data/temporary/legacy')
				.then((res) => {
					this.isLoading = false;
					this.temporaryData = res.data.temporaryData
					this.upload = res.data.upload

					this.warningShow = this.temporaryData.length > 500
				});
			},
			getUniqueValuesOfKey: (array, key) => {
				return array.reduce(function(carry, item){
					if(item[key] && !~carry.indexOf(item[key])) carry.push(item[key]);
					return carry;
				}, []);
			},

			cancelUpload(){
				this.$swal({
					title: "Cancel Upload?",
					text: "This action cannot be undone",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willCancel) => {
					if (willCancel) {
						location.replace("/data/upload/cancel")
					}
				});
			},

			uploadData(){
				this.$swal({
					title: "Upload Data",
					text: "Are you sure you want to upload the data? You may be redirected to the Upload Data page to continue uploading data.",
					icon: "info",
					buttons: true,
					dangerMode: true,
				})
				.then((proceed) => {
					if (proceed) {
						this.isLoading = true;
						return fetch('/api/data/upload/' + this.warningShow);
					}
				})
				.then(result => {
					this.isLoading = false;
					location.reload();
				})
				.catch(err => {
					this.isLoading = false;
					if (err) {
						this.$swal("Error!", "There was an error with the request", "error");
					} else {
						this.$swal.stopLoading();
						this.$swal.close();
					}
				});
			}
		}
	}
</script>