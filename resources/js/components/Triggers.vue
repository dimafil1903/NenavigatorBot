<template>

    <div>
        <router-view></router-view>


        <b-container>

            <div>


                <div class="float-right mb-3">
                    <ul class="pagination ">
                        <p class="align-middle mr-2 mb-0">Страница</p>

                        <li>
                            <b-button :disabled="!(currentPage>1)" rel="prev" v-on:click="clickHanler(false,show)">
                                <span><</span></b-button>
                        </li>
                        <b-input :max="last_page" @change="ChangePage" lazy-formatter style="width: 50px"
                                 v-model="currentPage"></b-input>
                        <li>
                            <b-button :disabled="!(currentPage<last_page) " rel="next"
                                      v-on:click="clickHanler(true,show)">>
                            </b-button>
                        </li>

                    </ul>
                </div>
                <b-table :busy="show"
                         :fields="fields"
                         :items="triggers"
                         responsive

                >
                    <template v-slot:cell(delete)="row">

                        <b-button @click="deleteTrigger(row.item.id)" class="mr-2" size="sm">
                            Удалить
                        </b-button>
                    </template>
                    <template v-slot:table-busy>
                        <div class="text-center text-danger my-2">
                            <b-spinner class="align-middle"></b-spinner>
                            <strong>Loading...</strong>
                        </div>
                    </template>
                </b-table>

            </div>
        </b-container>


    </div>
</template>

<script>

    import axios from 'axios'

    export default {
        name: 'App',

        data() {
            let triggers
            return {
                currentPage: 0,
                last_page: 0,
                show: true,
                fadeInd: "",
                headVariant: 'dark',
                fields: [
                    {
                        key: 'delete',
                        label: 'ACTION',

                        tdClass: "col text-break text-truncate",

                        // Variant applies to the whole column, including the header and footer
                        //  variant: 'danger'
                    },
                    {
                        key: 'id',
                        label: '№',
                        sortable: true,
                        tdClass: "col text-break text-truncate",

                    },
                    {
                        key: 'trigger_message',
                        label: 'Сообщение',
                        tdClass: "col text-break text-truncate",

                    },
                    {
                        key: 'answer_message',
                        label: 'Ответ',
                        sortable: true,
                        tdClass: "col text-break text-truncate",

                        // Variant applies to the whole column, including the header and footer
                        //  variant: 'danger'
                    },
                ],

                triggers,
                done: false,

            }
        },

        methods: {
            clickHanler: function (increment, show) {
                if (!show) {


                    if (increment) {
                        this.currentPage++;
                    } else {
                        this.currentPage--;
                    }
                    this.show = true;
                    setTimeout(this.getTriggers, 2000);
                }
            },
            deleteTrigger(id) {

                axios.delete('/api/triggers/' + id).then((response) => {
                    console.log(response);
                    alert('Тригер удален')
                }), (error) => {
                    console.log(error);
                }
                this.getTriggers()
            },
            ChangePage() {
                this.show = true;
                if (this.currentPage > this.last_page) {
                    this.currentPage = this.last_page
                }

                setTimeout(this.getTriggers, 2000);
            },
            getTriggers: function () {

                if (this.$route.params.id) {

                    axios.get('/api/triggers/' + this.$route.params.id + '?page=' + this.currentPage).then((response) => {
                        this.currentPage = response.data.triggers.current_page
                        this.last_page = response.data.triggers.last_page

                        this.triggers = response.data.triggers.data
                        //  localStorage.triggers=this.triggers
                        this.show = false
                        //   console.log(response);
                        this.done = true
                    }, (error) => {
                        //   console.log(error);
                        //done = false
                    });
                }

                // if(this.done===false){
                //     this.getTriggers()
                // }


            },

        },

        mounted() {
            this.getTriggers();
            // localStorage.clear();
            //  if(localStorage.triggers) this.triggers = localStorage.triggers;

        },
        watch: {
            // triggers(newTriggers) {
            //     localStorage.triggers = newTriggers;
            // }
        }
    }
</script>
