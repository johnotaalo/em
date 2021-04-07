<template>
    <div>
        <h3 class="text-center">{{ this.assessment }}</h3>
        <highcharts :options="graphOptions"></highcharts>
    </div>
</template>

<script>
export default {
    name: "DiseaseAreaGauge",
    props: {
        type: { type: String, default: null },
        diseaseArea: { type: String, default: null },
        assessmentData: { type: Array, default: null },
        assessment: { type: String, default: null }
    },
    computed: {
        graphOptions: function(){
            let gdata = 0
            let total_cases_after_dif = 0
            let total_classified = 0

            if (this.type === "classification") {
                if (this.diseaseArea === "pneumonia") {
                    total_cases_after_dif = _.sumBy(this.assessmentData, 'total_cases_after_dif')
                    total_classified = _.sumBy(this.assessmentData, 'total_classified')
                } else {
                    total_cases_after_dif = _.sumBy(this.assessmentData, 'TOTAL_CASES_AFTER_DIFF')
                    total_classified = _.sumBy(this.assessmentData, 'classified')
                }
            }

            if (this.type === "treatment") {
                if (this.diseaseArea === "pneumonia") {
                    total_cases_after_dif = _.sumBy(this.assessmentData, 'total_cases_after_dif')
                    total_classified = _.sumBy(this.assessmentData, 'total_classified')
                } else {
                    total_cases_after_dif = _.sumBy(this.assessmentData, 'TOTAL_CASES_AFTER_DIFF')
                    total_classified = _.sumBy(this.assessmentData, 'classified')
                }
            }

            gdata = _.round((total_classified / total_cases_after_dif) * 100)
            return {
                chart: {
                    type: 'solidgauge',
                    height: '150px'
                },

                title: null,

                pane: {
                    startAngle: -90,
                    endAngle: 90,
                    background: {
                        innerRadius: '60%',
                        outerRadius: '100%',
                        shape: 'arc'
                    }
                },

                tooltip: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },

                // the value axis
                yAxis: {
                    min: 0,
                    max: 100,
                    title: {
                        // text: '% Immunised',
                        text: null,
                        y: -50
                    },
                    stops: [
                        [0.1, '#DF5353'], // red,
                        [0.5, '#DDDF0D'], // yellow,
                        [0.9, '#55BF3B'] // green
                    ],
                    lineWidth: 0,
                    minorTickInterval: null,
                    tickAmount: 2,
                    labels: {
                        y: 16
                    }
                },

                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            y: -30,
                            borderWidth: 0,
                            useHTML: true
                        }
                    }
                },
                series: [{
                    name: this.assessment,
                    data: [gdata],
                    dataLabels: {
                        format: '<div style="text-align:center"><span style="font-size:18px;color:black;">{y}</span><span style="font-size:18px;color:silver">%</span></div>'
                    },
                    tooltip: {
                        valueSuffix: '%',
                    },
                }],
            }
        }
    }
}
</script>

<style scoped>

</style>
