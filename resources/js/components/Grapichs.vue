<template>
    <b-container>
        <router-view></router-view>

        <Navbar></Navbar>

        <b-container class=" ">

            <b-row class="row-cols-2" v-if="$route.params.id">

                <b-col class="row-cols-1" cols="3">
                    <template>
                        <b-avatar rounded size="6rem" v-bind:src="'/storage/avatars/'+ $route.params.id +'.jpg'"
                                  variant="info"></b-avatar>
                        <!--   <img  class="img-block" :height="200" :width="200" v-if="$route.params.id" v-bind:src="'/storage/avatars/'+ $route.params.id +'.jpg'" /> -->
                    </template>
                </b-col>
                <b-col class="row-cols row" cols="9">
                    <b-col class="row-cols" lg="5" md="6" sm="12">
                        <div>
                            <p>
                                Участников:
                                <b-badge>{{membersCount}}</b-badge>
                            </p>
                        </div>
                    </b-col>
                    <b-col class="row-cols" lg="5" md="6" sm="12">
                        <div>
                            <p>
                                Всего сообщений:
                                <b-badge>{{totalMessages}}</b-badge>
                            </p>
                        </div>
                    </b-col>
                </b-col>
            </b-row>
        </b-container>
        <b-container>
            <b-overlay
                :show=show
            >

                <b-container :aria-hidden="show ? 'true' : null">
                    <GChart :data="chartData"
                            :options="chartOprionBar"
                            @ready="onChartReady"
                            type="LineChart"
                            v-if="!$route.params.id"
                    />
                    <GChart :data="chartData" :options="chartOprionBar"
                            @ready="onChartReady"
                            type="LineChart"
                            v-if="$route.params.id "
                            v-show="chartData"
                    />
                </b-container>
            </b-overlay>
        </b-container>


        <topChats v-if="!$route.params.id">

        </topChats>
        <Triggers v-if="$route.params.id"></Triggers>
    </b-container>
</template>

<script>
    import {GChart} from 'vue-google-charts'
    import axios from 'axios'
    import Triggers from "./Triggers";

    import r from '../route'
    import Navbar from "./Navbar";
    import topChats from "./topChats";

    export default {
        name: 'App',
        components: {
            Triggers,
            topChats,
            Navbar,
            GChart
        },
        data() {
            return {

                chartOprionBar: {
                    chart: {
                        title: 'Company Performance',
                        subtitle: 'Sales, Expenses, and Profit: 2014-2017'
                    },
                    bars: 'horizontal', // Required for Material Bar Charts.
                    hAxis: {format: 'decimal'},
                    height: 500,
                    // colors: ['#1b9e77', '#d95f02', '#7570b3']
                },
                chartOptionsLine: {

                    title: "Hourly Page views",
                    curveType: 'function',
                    height: 500,
                },
                show: true,
                image: "/storage/avatars/" + this.$route.params.id + ".jpg",
                nn: "",
                membersCount: 0,
                totalMessages: 0,
                chartsLib: null,
                // Array will be automatically processed with visualization.arrayToDataTable function
                chartData: null,
            }
        },

        methods: {
            getCount: function () {


                if (this.$route.params.id) {
                    axios.get('/api/grapics/' + this.$route.params.id).then((response) => {


                        this.membersCount = response.data.membersCount
                        this.chartData = response.data.Chart
                        this.totalMessages = response.data.totalMessages

                        //console.log("dwqdwqdqwd", response.data.topMsg);
                        this.show = false
                        // console.log(response);
                    }, (error) => {
                        // console.log(error);
                    });

                } else {
                    axios.get(r('grapics.index')).then((response) => {

                        this.chartData = response.data.Chart;


                        //console.log("dwqdwqdqwd", response.data.topMsg);
                        //  console.log(response);
                        this.show = false
                    }, (error) => {
                        //  console.log(error);
                    });

                }


            },
            onChartReady(chart, google) {


                this.getCount();

                this.chartsLib = google
            }
        },
        mounted() {

            // localStorage.clear();


        },


    }
</script>
