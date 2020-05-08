<template>
    <div class="container">
        <router-view></router-view>

        <Navbar></Navbar>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card  animated  fadeInUp faster ">
                    <b-card-header :header-bg-variant="dark" class="justify-content-center">
                        <h1 class="text-white font-weight-bold  ">
                            Статистика
                        </h1>
                    </b-card-header>
                    <div class="card-body">
                        <div class="container">
                            <div class="row text-center">

                                <div class="col-md-3">
                                    <div class="counter">
                                        <i class="fa fa-code fa-2x"></i>


                                        <h2 class="  mb-0 count1">
                                            <ICountUp
                                                :delay="chats.delay"
                                                :endVal="chats.endVal"
                                                :options="chats.options"
                                                @ready="onReadyChats"
                                                v-if="chats.endVal!==0"></ICountUp>
                                            <b-spinner label="Spinning" type="grow" v-if="chats.endVal===0"></b-spinner>

                                        </h2>
                                        <p class="count-text ">Чатов</p>
                                        <b-icon-people-fill @mouseout="fadeInd='pulse'"
                                                            @mouseover="fadeInd='rubberBand'" class="animated"
                                                            font-scale="5" v-bind:class="fadeInd"></b-icon-people-fill>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="counter">
                                        <i class="fa fa-code fa-2x"></i>


                                        <h2 class="  mb-0 count1">
                                            <ICountUp
                                                :delay="messages.delay"
                                                :endVal="messages.endVal"
                                                :options="messages.options"
                                                @ready="onReadyCustomers"
                                                v-if="messages.endVal!==0"></ICountUp>
                                            <b-spinner label="Spinning" type="grow"
                                                       v-if="messages.endVal===0"></b-spinner>
                                        </h2>


                                        <p class="count-text ">Сообщений обработано</p>
                                        <b-icon-envelope-open @mouseout="fadeInd2='pulse'"
                                                              @mouseover="fadeInd2='rubberBand'" class="animated"
                                                              font-scale="5"
                                                              v-bind:class="fadeInd2"></b-icon-envelope-open>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="counter">
                                        <i class="fa fa-code fa-2x"></i>


                                        <h2 class="  mb-0 count1">
                                            <ICountUp
                                                :delay="triggers.delay"
                                                :endVal="triggers.endVal"
                                                :options="triggers.options"
                                                @ready="onReadyTriggers"
                                                v-if="triggers.endVal!==0"></ICountUp>
                                            <b-spinner label="Spinning" type="grow"
                                                       v-if="triggers.endVal===0"></b-spinner>

                                        </h2>
                                        <p class="count-text ">Триггеров
                                        </p>
                                        <b-icon @mouseout="fadeInd3='pulse'" @mouseover="fadeInd3='rubberBand'"
                                                class="animated" font-scale="5" icon="exclamation-circle"
                                                v-bind:class="fadeInd3"></b-icon>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>

            </div>

        </div>
        <b-container>
            <b-row>
                <b-col lg="6">
                    <top-msg></top-msg>
                </b-col>
                <b-col lg="6">
                    <top-chats></top-chats>
                </b-col>
            </b-row>
        </b-container>


    </div>


</template>

<script>
    import ICountUp from 'vue-countup-v2'
    import axios from 'axios'
    import r from '../route'
    import TopMsg from "./TopMsg";
    import Navbar from "./Navbar";
    import Grapichs from "./Grapichs";
    import TopChats from "./topChats";

    export default {
        components: {
            TopChats,
            Grapichs,
            Navbar,
            TopMsg,
            ICountUp
        },
        data() {

            let topMsg;

            return {
                dark: "dark",
                fadeInd: '',
                fadeInd2: '',
                fadeInd3: '',
                topMsg,
                messages: {
                    delay: 100,
                    endVal: 0,
                    options: {
                        useEasing: true,
                        useGrouping: true,
                        separator: ',',
                        decimal: '.',
                        prefix: '',
                        suffix: ''
                    }
                },
                chats: {
                    delay: 100,
                    endVal: 0,
                    options: {
                        useEasing: true,
                        useGrouping: true,
                        separator: ',',
                        decimal: '.',
                        prefix: '',
                        suffix: ''
                    },

                },
                triggers: {
                    delay: 100,
                    endVal: 0,
                    options: {
                        useEasing: true,
                        useGrouping: true,
                        separator: ',',
                        decimal: '.',
                        prefix: '',
                        suffix: ''
                    }
                }
            }
        },
        methods: {

            getCount: function () {
                axios.get(r('stat.index')).then((response) => {
                    this.chats.endVal = response.data.countOfChats;
                    this.messages.endVal = response.data.messages;
                    this.triggers.endVal = response.data.triggersCount;
                    this.topMsg = response.data.topMsg;
                    //console.log("dwqdwqdqwd", response.data.topMsg);
                    // console.log(response);
                }, (error) => {
                    //   console.log(error);
                });


            },
            loop() {
                setInterval(() => {
                    this.getCount();
                }, 5000);

            },

            onReadyCustomers: function (instance, CountUp) {

                const that = this.messages;


                instance.update(that.endVal + 1); //onReadyProjects

            },
            onReadyChats: function (instance, CountUp) {
                const that = this.chats;
                instance.update(that.endVal + 1); //onReadyProjects
            },
            onReadyTriggers: function (instance, CountUp) {
                const that = this.triggers;
                instance.update(that.endVal + 1); //onReadyProjects
            }

        },
        mounted() {
            // if (localStorage.chats) {
            //     this.chats = localStorage.chats;
            // }
            // if (localStorage.topMsg) {
            //     this.topMsg = localStorage.topMsg;
            // }

            this.getCount();
            this.loop();


        },
        name: "User"
    }
</script>

<style>
    .counter {
        /*background-color:#f5f5f5;*/
        padding: 20px 0;
        border-radius: 5px;
        font-size: 17px;
    }

    .count-title {
        font-size: 40px;
        font-weight: normal;
        margin-top: 10px;
        margin-bottom: 0;
        text-align: center;
    }

    .count-text {
        font-size: 15px;
        font-weight: normal;
        margin-top: 10px;
        margin-bottom: 0;
        text-align: center;
    }

    .fa-2x {
        margin: 0 auto;
        float: none;
        display: table;
        color: #4ad1e5;
    }
</style>
