<template>
    <img :src="url" />
</template>

<script>
    export default {
        name: "Chart",
        data: () =>{
            return {
                'url': '',
            }
        },
        methods: {
            getChart(){
                const quickchart_url = "https://quickchart.io/chart?bkg=white&c={ type: 'bar', data: { labels: ['feb', 'Q2', 'Q3', 'Q4'], datasets: [{ label: 'Users', data: [50, 60, 70, 180] }, { label: 'Revenue', data: [100, 200, 300, 400] }] }}"
                const post_data = {'chart': {'type': 'bar', 'data': {'labels': ['Hello', 'World'],
                            'datasets': [{'label': 'Foo', 'data': [1, 2]}]}}}
                axios.get(quickchart_url).then(response => console.log(response))
            }
        },
        mounted(){
            this.getChart();
        }
    }
</script>

<style scoped>

</style>