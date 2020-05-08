<template>
    <div class="container">
        <div class="row justify-content-center">

            <h1 class="text-center m-2">Топ 20 сообщений
                <b-icon-cursor @mouseout="fadeInd='pulse'" @mouseover="fadeInd='rubberBand'"
                               class="animated    h1 mb-2 " v-bind:class="fadeInd"></b-icon-cursor>
            </h1>

            <b-table
                :fields="fields" :head-variant="headVariant" :items="topMsg" hover
                striped
            ></b-table>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import r from '../route'

    export default {
        name: "TopMsg",

        data() {
            let topMsg;
            return {
                fadeInd: "",
                headVariant: 'dark',
                fields: [
                    {
                        key: 'id',
                        label: '№',

                    },
                    {
                        key: 'msg',
                        label: 'Сообщение',
                        sortable: false
                    },
                    {
                        key: 'count',
                        label: 'Количество сообщений',
                        sortable: true,
                        // Variant applies to the whole column, including the header and footer
                        //  variant: 'danger'
                    }
                ],

                topMsg

            }
        }, methods: {

            getCount: function () {
                axios.get(r('stat.index')).then((response) => {
                    this.topMsg = response.data.topMsg;
                    //console.log("dwqdwqdqwd", response.data.topMsg);
                    //  console.log(response);
                }, (error) => {
                    //console.log(error);
                });


            },
        },
        mounted() {
            this.getCount();
        },
    }
</script>

<style scoped>

</style>
