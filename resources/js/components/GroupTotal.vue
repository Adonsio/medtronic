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
        name: "GroupTotal",
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
                console.log(window.location.href.split('?')[1]);
                let filter = window.location.href.split('?')[1];
                this.chartdata.labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug', 'Sep', 'Nov', 'Dez'];
                await axios.get('/api/grouptotal?'+filter).then(response => (
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


                this.loaded = true;
            } catch (e) {
                console.error(e)
            }
        },
    }
</script>

<style scoped>

</style>