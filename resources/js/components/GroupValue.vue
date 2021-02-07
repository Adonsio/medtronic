<template>
    <div class="container">
        <line-chart
                v-if="loaded"
                :chartdata="chartdata"
                />
    </div>
</template>

<script>
    import LineChart from './LineChart.js'

    export default {
        name: "GroupValue",
        components: {
            LineChart
        },
        data: () => ({
            loaded: false,
            chartdata: {}
        }),
        async mounted () {
            this.loaded = false
            try {
                let suppliers = null;
                //await axios.get('/api/getX').then(response => (this.chartdata.labels = response.data.xaxis))
                let filter = window.location.href.split('?')[1];
                this.chartdata.labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug', 'Sep', 'Nov', 'Dez'];
                await axios.get('/api/datasets?'+filter).then(response => (
                    console.log(Object.keys(response.data.datasets)),
                    suppliers = response.data.datasets,
                    console.log(response.data.datasets)))
                this.chartdata.datasets = [];
                let randomColor = [
                    '#7909b9',
                    '#d56b0e',
                    '#1ef5a6',
                    '#e474d1',
                    '#378bc1',
                    '#495179',
                    '#c53d28',
                    '#9cd6ca',
                    '#135b51',
                    '#bf7b63',
                    '#4559cd',
                    '#0a8ac5',
                    '#6d52ae',
                    '#9d5d78',
                    '#31462b',
                    '#0055b9',
                    '#41000b',
                    '#005251',
                    '#0014b9',
                    '#b975ab',
                    '#2db8b9',
                    '#7909b9',
                    '#d56b0e',
                    '#1ef5a6',
                    '#e474d1',
                    '#378bc1',
                    '#495179',
                    '#c53d28',
                    '#9cd6ca',
                    '#135b51',
                    '#bf7b63',
                    '#4559cd',
                    '#0a8ac5',
                    '#6d52ae',
                    '#9d5d78',
                    '#31462b',
                    '#0055b9',
                    '#41000b',
                    '#005251',
                    '#0014b9',
                    '#b975ab',
                    '#2db8b9',

                ];
                for (let i = 0; i < Object.keys(suppliers).length; i++){

                    this.chartdata.datasets.push({
                        label: Object.keys(suppliers)[i],

                        backgroundColor: randomColor[i],
                        data: suppliers[Object.keys(suppliers)[i]],
                    })

                }
                //suppliers[Object.keys(suppliers)[1]]
                /*
                this.chartdata.datasets = [
                    {
                        label: Object.keys(suppliers)[2],
                        backgroundColor: 'rgba(238,238,144 , 0.9 )',
                        data: suppliers[Object.keys(suppliers)[2]],
                    },


                ],
                this.chartdata.datasets.push({
                    label: Object.keys(suppliers)[1],
                    backgroundColor: 'rgba(144,238,144 , 0.9 )',
                    data: suppliers[Object.keys(suppliers)[1]],
                });




                 */

                this.loaded = true;
            } catch (e) {
                console.error(e)
            }
        },
        methods: {
            random_rgba() {
                var o = Math.round, r = Math.random, s = 255;
                return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ', 0,9 )';
            },
            fillData ()
            {
                this.datacollection = {
                    labels: this.xdata,
                    datasets: [
                        {
                            label: 'Ventas',
                            backgroundColor: '#FF0066',
                            data: [ 20, 40, 50, 20, 50, 40]
                        },
                    ]
                }
            },
            getX(){
                let app = this;
                axios.get('/api/getX').then(response => app.xdata = response.data.suppliers);

                console.log(app.xdata)
            },
        }
    }
</script>

<style scoped>
    .small {
        max-width: 800px;
        /* max-height: 500px; */
        margin:  50px auto;
    }
</style>