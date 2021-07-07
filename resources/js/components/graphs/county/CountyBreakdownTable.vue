<template>
    <div>
        <div class="card">
            <div class = "card-header">
                <h3 class="card-title">Classification</h3>
            </div>
            <!-- <div class="card-body">
                <b-button @click="displaySubcountiesClassificationData">Get Data</b-button>
                <pre>
                    {{ rawData }}
                </pre>
            </div> -->
            <table class = "table table-bordered table-sm card-table">
                <thead>
                    <tr>
                        <th rowspan="2"></th>
                        <th class="text-center" colspan="2" v-for="assessment in assessmentTitles" :key="assessment">{{ assessment }}</th>
                    </tr>
                    <tr>
                        <th v-for="disease in diseases">{{ disease }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(data, subcounty) in subcountiesClassification" :key="subcounty">
                        <td>{{ subcounty }}</td>
                        <td v-for="percentage in data">{{ percentage }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    name: "CountyBreakdownTable",
    props: {
        county: { type: String, default: "" },
        pneumoniaData: { type: null, default: null },
        diarrhoeaData: { type: null, default: null },
        assessments: { type: Array, defalt: () => {return []} },
        // subcounties: { type: Array, default: () => {return []} }
    },
    data(){
        return {
            rawData: null
        }
    },
    methods: {
        displaySubcountiesClassificationData: function(){
            let assessments = this.assessmentTitles
            let subcountyPneumoniaData = {}
            let subcountyDiarrhoeaData = {}
            let aggregatedData = {}

            _.each(this.pneumoniaData, (data, assessment) => {
                subcountyPneumoniaData[assessment] = _(data).groupBy('facility.SUB_COUNTY').map((facilityData, subcounty) => {
                    return {
                        subcounty: subcounty,
                        classified: _.sumBy(facilityData, 'total_classified'),
                        total_cases: _.sumBy(facilityData, 'total_cases_after_dif')
                    }
                }).value()
            })

            _.each(this.diarrhoeaData, (data, assessment) => {
                subcountyDiarrhoeaData[assessment] = _(data).groupBy('facility.SUB_COUNTY').map((facilityData, subcounty) => {
                    return {
                        subcounty: subcounty,
                        classified: _.sumBy(facilityData, 'classified'),
                        total_cases: _.sumBy(facilityData, 'TOTAL_CASES_AFTER_DIFF')
                    }
                }).value()
            })

            _.each(this.subcountiesList, (subcounty) => {
                if (typeof aggregatedData[subcounty] == "undefined") {
                    aggregatedData[subcounty] = {}
                }

                _.forEach(subcountyPneumoniaData ,(_pneumonia, assessment) => {
                    _.forEach(_pneumonia, (cdata) => {
                        if (cdata.subcounty == subcounty) {
                            aggregatedData[subcounty][`pneumonia_${assessment}`] = [cdata.classified, cdata.total_cases]
                        }
                    })
                })

                _.forEach(subcountyDiarrhoeaData ,(_diarrhoea, assessment) => {
                    _.forEach(_diarrhoea, (cdata) => {
                        if (cdata.subcounty == subcounty) {
                            aggregatedData[subcounty][`diarrhoea_${assessment}`] = [cdata.classified, cdata.total_cases]
                        }
                    })
                })
            })

            this.rawData = aggregatedData
        }
    },
    computed: {
        assessmentTitles: function(){
            return _.map(this.assessments, (assessment) => {
                return assessment.value
            })
        },
        diseases: function(){
            let diseasesArray = []

            _.each(this.assessmentTitles, (assessment) =>{
                diseasesArray.push("Pneumonia");
                diseasesArray.push("Diarrhoea");
            })

            return diseasesArray
        },
        subcountiesClassification: function(){
            let assessments = this.assessmentTitles
            let subcountyPneumoniaData = {}
            let subcountyDiarrhoeaData = {}
            let aggregatedData = {}

            _.each(this.pneumoniaData, (data, assessment) => {
                subcountyPneumoniaData[assessment] = _(data).groupBy('facility.SUB_COUNTY').map((facilityData, subcounty) => {
                    return {
                        subcounty: subcounty,
                        classified: _.sumBy(facilityData, 'total_classified'),
                        total_cases: _.sumBy(facilityData, 'total_cases_after_dif')
                    }
                }).value()
            })

            _.each(this.diarrhoeaData, (data, assessment) => {
                subcountyDiarrhoeaData[assessment] = _(data).groupBy('facility.SUB_COUNTY').map((facilityData, subcounty) => {
                    return {
                        subcounty: subcounty,
                        classified: _.sumBy(facilityData, 'classified'),
                        total_cases: _.sumBy(facilityData, 'TOTAL_CASES_AFTER_DIFF')
                    }
                }).value()
            })

            _.each(this.subcountiesList, (subcounty) => {
                if (typeof aggregatedData[subcounty] == "undefined") {
                    aggregatedData[subcounty] = {}
                }

                _.forEach(subcountyPneumoniaData ,(_pneumonia, assessment) => {
                    _.forEach(_pneumonia, (cdata, k) => {
                        if (cdata.subcounty == subcounty) {
                            aggregatedData[subcounty][`pneumonia_${assessment}`] = _.round((cdata.classified/ cdata.total_cases) * 100)
                            aggregatedData[subcounty][`diarrhoea_${assessment}`] = _.round((subcountyDiarrhoeaData[assessment][k].classified / subcountyDiarrhoeaData[assessment][k].total_cases ) * 100)
                        }
                    })
                })
            })

            return aggregatedData
        },
        xsubcountiesClassification: function(){
            let assessments = this.assessmentTitles
            let subcountyPneumoniaData = {}
            let subcountyDiarrhoeaData = {}

            _.each(this.subcountiesList, (subcounty) => {
                if (typeof subcountyPneumoniaData[subcounty] == "undefined"){
                    subcountyPneumoniaData[subcounty] = {}
                    subcountyDiarrhoeaData[subcounty] = {}
                }
                _.each(this.pneumoniaData, (assessmentData, key) => {
                    if (typeof subcountyPneumoniaData[subcounty][key] == "undefined"){
                        subcountyPneumoniaData[subcounty][key] = []
                    }
                    _.each(assessmentData, (data) => {
                        if (data.subcounty.subcounty_name === subcounty) {
                            subcountyPneumoniaData[subcounty][key].push({
                                cases: data.total_cases_after_dif,
                                classified: data.total_classified
                            })
                        }
                    })
                })

                _.each(this.diarrhoeaData, (assessmentData, key) => {
                    if (typeof subcountyDiarrhoeaData[subcounty][key] == "undefined"){
                        subcountyDiarrhoeaData[subcounty][key] = []
                    }
                    _.each(assessmentData, (data) => {
                        if (data.subcounty.subcounty_name === subcounty) {
                            subcountyDiarrhoeaData[subcounty][key].push({
                                cases: data.TOTAL_CASES_AFTER_DIFF,
                                classified: data.classified
                            })
                        }
                    })
                })
            })

            let classification = {}

            _.each(subcountyPneumoniaData, (assessmentData, subcounty) => {
                if ( typeof classification[subcounty] == "undefined"){
                    classification[subcounty] = {}
                }

                var innerData = {}

                _.each(this.assessmentTitles, (assessment) => {
                    var pneumonia = subcountyPneumoniaData[subcounty][assessment]
                    var diarrhoea = subcountyDiarrhoeaData[subcounty][assessment]

                    var pneumoniaTotalCases = _.sumBy(pneumonia, 'cases')
                    var pneumoniaClassified = _.sumBy(pneumonia, 'classified')

                    var diarrhoeaTotalCases = _.sumBy(diarrhoea, 'cases')
                    var diarrhoeaClassified = _.sumBy(diarrhoea, 'classified')

                    // innerData[`pneumonia_${assessment}`] = _.round((pneumoniaClassified / pneumoniaTotalCases) * 100)
                    // innerData[`diarrhoea_${assessment}`] = _.round((diarrhoeaClassified / diarrhoeaTotalCases) * 100)

                    innerData[`pneumonia_${assessment}`] = [
                        pneumoniaClassified , pneumoniaTotalCases
                    ]

                    innerData[`diarrhoea_${assessment}`] = [
                        diarrhoeaClassified , diarrhoeaTotalCases
                    ]
                })

                classification[subcounty] = innerData
            })

            return classification
        },
        subcountiesList: function () {
            var subcounties = []

            _.each(this.pneumoniaData, (data) => {
                _.each(data, (row) => {
                    if(!subcounties.includes(row.subcounty.subcounty_name)){
                        subcounties.push(row.subcounty.subcounty_name)
                    }
                })
            })

            return subcounties
        }
    }
}
</script>

<style scoped>

</style>
