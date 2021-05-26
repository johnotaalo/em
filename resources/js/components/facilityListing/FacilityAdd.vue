<template>
	<div>
		<div class="card">
			<div class="card-body">
                <b-alert :show="showAlert" variant="danger" dismissible>
                    <p>There are some errors with your request</p>
                    <ul>
                        <li v-for="(error, key, index) in form.errors.errors">{{ error }}</li>
                    </ul>
                </b-alert>
				<b-form>
					<b-form-group label="Facility Name">
						<b-input v-model="form.facility_name"></b-input>
					</b-form-group>

					<b-row>
						<b-col md>
							<b-form-group label="County">
								<v-select v-model="form.county" :options="countyOptions"></v-select>
							</b-form-group>
						</b-col>

						<b-col md>
							<b-form-group label="Sub County">
								<v-select v-model="form.sub_county" :options="subcountyOptions"></v-select>
							</b-form-group>
						</b-col>
					</b-row>

					<b-row>
						<b-col md>
							<b-form-group label="Facility Type">
								<b-select v-model="form.facility_type" :options="facilityTypeList"></b-select>
							</b-form-group>
						</b-col>

						<b-col md>
							<b-form-group label="Status">
								<b-select v-model="form.status" :options="statusList"></b-select>
							</b-form-group>
						</b-col>
					</b-row>

					<b-button variant="success" @click="submitData">Submit Data</b-button>
				</b-form>
			</div>
		</div>
	</div>
</template>

<script type="text/javascript">
	import Form from '../../core/Form'
	export default {
		data(){
			return {
				countyList: "",
                showAlert: false,
				facilityTypeList: [],
				statusList: [
					{value: 1, text: "Active"},
					{value: 0, text: "Inactive"}
				],
				form: new Form({
					facility_name: "",
					county: null,
					sub_county: null,
					facility_type: "",
					status: ""
				})
			}
		},
		mounted(){
			this.getData()
		},
		methods: {
			getData: function(){
				let countiesRequest = axios.get("/api/data/counties-and-subcounties");
				let facilityTypesRequest = axios.get("/api/data/facility-types");
				// axios.get();

				axios.all([countiesRequest, facilityTypesRequest]).then(axios.spread((...responses) => {
					this.countyList = responses[0].data
					this.facilityTypeList = _.map(responses[1].data, (facilityType) => {
						return {
							value: facilityType.facility_type,
							text: facilityType.facility_type
						}
					})
				}))
				.catch(errors => {
					alert(errors);
				});
			},
			submitData: function(){
				this.form.post('/api/facilities')
				.then(res => {
					alert('Successfully added facility to listing')
                    window.location = "/data/facilities"
				})
				.catch(error => {
					alert(error.message)
                    this.form.errors = error
                    this.showAlert = true
				})
			}
		},
		computed: {
			countyOptions: function(){
				let counties = this.countyList

				let options = _.map(counties, (county) => {
					return {
						label: `${county.county} - ${county.subcounties.length} subcounties` ,
						value: county.cto_id
					}
				})

				return options
			},
			subcountyOptions: function(){
				let selectedCounty = this.form.county
				let allCounties = this.countyList

				if(selectedCounty !== null){
					let filteredCounty = _.find(allCounties, {'cto_id': selectedCounty.value})

					if (filteredCounty) {
						let options = _.map(filteredCounty.subcounties, (subcounty) => {
							return {
								label: subcounty.subcounty_name,
								value: subcounty.cto_id
							}
						})

						return options
					}
				}
				return []
			}
		},
		watch: {
			'form.county': function(newVal, oldVal){
				// console.log(newVal.value)
			}
		}
	}
</script>
