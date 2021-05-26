<template>
    <div>
        <loading :active.sync="dataLoading" :color="loaderColor" :can-cancel="false"
                 :is-full-page="true"></loading>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8">

                    <!-- Header -->
                    <div class="header mt-md-5">
                        <div class="header-body">
                            <div class="row align-items-center">
                                <div class="col">

                                    <!-- Pretitle -->
                                    <h6 class="header-pretitle">
                                        Facility
                                    </h6>

                                    <!-- Title -->
                                    <h1 class="header-title">
                                        Edit Facility
                                    </h1>

                                </div>
                            </div> <!-- / .row -->
                        </div>
                    </div>

                    <!-- Form -->
                    <form class="mb-4">

                        <!-- Facility name -->
                        <b-form-group label="Facility Name">
                            <b-input v-model="form.facility_name"></b-input>
                        </b-form-group>

                        <b-row>
                            <b-col md>
                                <b-form-group label="County">
                                    <v-select class="bg-white" v-model="form.county" :options="countyOptions"></v-select>
                                </b-form-group>
                            </b-col>

                            <b-col md>
                                <b-form-group label="Sub County">
                                    <v-select class="bg-white" v-model="form.sub_county" :options="subcountyOptions"></v-select>
                                </b-form-group>
                            </b-col>
                        </b-row>

                        <b-form-group label="Facility Type">
                            <b-select v-model="form.facility_type" :options="facilityTypeList"></b-select>
                        </b-form-group>

                        <!-- Divider -->
                        <hr class="mt-5 mb-5">

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <!-- Label -->
                                    <label class="form-label mb-1">
                                        Facility Status
                                    </label>
                                    <!-- Text -->
                                    <small class="form-text text-muted">
                                        This makes the facility available for export and import into the SurveyCTO tool.
                                    </small>

                                    <!-- Switch -->
                                    <b-form-checkbox v-model="form.status" switch></b-form-checkbox>

                                </div>

                            </div>
                            <div class="col-12 col-md-6">
                                <!-- Warning -->
                                <div class="card bg-light border">
                                    <div class="card-body">
                                        <!-- Heading -->
                                        <h4 class="mb-2">
                                            <i class="fe fe-alert-triangle"></i> Warning
                                        </h4>

                                        <!-- Text -->
                                        <p class="small text-muted mb-0">
                                            If the facility is not active, you cannot add it to SurveyCTO.
                                        </p>

                                    </div>
                                </div>

                            </div>
                        </div> <!-- / .row -->

                        <!-- Divider -->
                        <hr class="mt-5 mb-5">

                        <!-- Buttons -->
                        <a href="#" class="btn w-100 btn-primary" @click="updateFacility">
                            Update Facility
                        </a>
                        <a href="#" class="btn w-100 btn-link text-muted mt-2" @click="cancelUpdate">
                            Cancel Update
                        </a>

                    </form>

                </div>
            </div> <!-- / .row -->
        </div>
    </div>
</template>

<script>
import Form from '../../core/Form'
export default {
    name: "FacilityEdit",
    props: {
        id: { type: Number, default: 0 }
    },
    data(){
        return {
            dataLoading: false,
            countyList: "",
            loaderColor: "#2196F3",
            facilityTypeList: [],
            facilityDetails: {},
            form: new Form({
                survey_cto_id: "",
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
            this.dataLoading = true
            let countiesRequest = axios.get("/api/data/counties-and-subcounties");
            let facilityTypesRequest = axios.get("/api/data/facility-types");
            let facilityDetailsRequest = axios.get(`/api/facilities/${this.id}`)
            // axios.get();

            axios.all([countiesRequest, facilityTypesRequest, facilityDetailsRequest]).then(axios.spread((...responses) => {
                this.countyList = responses[0].data
                this.facilityTypeList = _.map(responses[1].data, (facilityType) => {
                    return {
                        value: facilityType.facility_type,
                        text: facilityType.facility_type
                    }
                })
                this.facilityDetails = responses[2].data

                this.form.survey_cto_id = this.facilityDetails.SURVEY_CTO_ID
                this.form.facility_name = this.facilityDetails.FACILITY_NAME
                this.form.facility_type = this.facilityDetails.FACILITY_TYPE
                this.form.status = (this.facilityDetails.STATUS === "1")
                this.form.county = {
                    label: this.facilityDetails.COUNTY,
                    value: this.facilityDetails.COUNTY_ID
                }

                this.form.sub_county = {
                    label: this.facilityDetails.SUB_COUNTY,
                    value: this.facilityDetails.SUB_COUNTY_ID
                }
                this.dataLoading = false
            }))
                .catch(errors => {
                    this.dataLoading = false
                    this.$swal('Error', errors, 'error');
                });
        },
        updateFacility: function(){
            this.dataLoading = true

            this.form.put('/api/facilities')
            .then(res => {
                this.dataLoading = false
                this.$swal('Success', 'Successfully updated facility data', 'success')
                .then(()=>{
                    window.location = "/data/facilities"
                })
            })
            .catch(error => {
                this.dataLoading = false
                this.$swal('Error', error.message, 'error')
            })
        },
        cancelUpdate: function(){
            window.location = "/data/facilities"
        }
    },
    computed: {
        countyOptions: function(){
            let counties = this.countyList

            let options = _.map(counties, (county) => {
                return {
                    label: `${county.county}` ,
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
    }
}
</script>

<style scoped>

</style>
