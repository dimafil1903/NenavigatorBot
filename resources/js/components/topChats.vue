<template>
    <div class="container">
        <div class="row justify-content-center">

            <h1 class="text-center m-2">Топ 10 Чатов
                <b-icon-cursor @mouseout="fadeInd='pulse'" @mouseover="fadeInd='rubberBand'"
                               class="animated    h1 mb-2 " v-bind:class="fadeInd"></b-icon-cursor>
            </h1>
            <b-container>
                <b-list-group v-bind:key="chat.chat_id" v-for="chat in tChats">
                    <b-list-group-item class="d-flex justify-content-between align-items-center">
                        {{chat.chat_title}}
                        <b-badge pill variant="primary"> {{chat.total_msg}}</b-badge>
                    </b-list-group-item>
                </b-list-group>
            </b-container>

        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import r from '../route'

    export default {
        name: "TopChats",

        data() {

            return {
                fadeInd: "",
                headVariant: 'dark',
                show: true,
                tChats: {},


            }
        }, methods: {
            sortArrays(arrays) {
                return _.orderBy(arrays, 'id', 'desc');
            },
            getCount: function () {


                axios.get(r('chat.index')).then((response) => {


                    this.tChats = response.data.topChats;


                    //console.log("dwqdwqdqwd", response.data.topMsg);
                    //   console.log(response);
                    this.show = false
                }, (error) => {
                    //   console.log(error);
                });


            },


        },
        mounted() {


            this.getCount();

            // localStorage.clear();


        },
    }
</script>

<style scoped>

</style>
