<template>
    <b-container>
        <!-- User Interface controls -->
        <Create class="mb-5" v-on:clickedSomething="getData"></Create>
        <b-row>
            <b-col class="my-1" lg="6">
                <b-form-group
                    class="mb-0"
                    label="Сортировка по категориям"
                    label-align-sm="right"
                    label-cols-sm="3"
                    label-for="sortBySelect"
                    label-size="sm"
                >
                    <b-input-group size="sm">
                        <b-form-select :options="sortOptions" class="w-75" id="sortBySelect" v-model="sortBy">
                            <template v-slot:first>
                                <option value="">-- none --</option>
                            </template>
                        </b-form-select>

                    </b-input-group>
                </b-form-group>
            </b-col>


            <b-col class="my-1" lg="6">
                <b-form-group
                    class="mb-0"
                    label="Filter"
                    label-align-sm="right"
                    label-cols-sm="3"
                    label-for="filterInput"
                    label-size="sm"
                >
                    <b-input-group size="sm">
                        <b-form-input
                            id="filterInput"
                            placeholder="Type to Search"
                            type="search"
                            v-model="filter"
                        ></b-form-input>
                        <b-input-group-append>
                            <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
            </b-col>


            <b-col class="my-1" md="6" sm="5">
                <b-form-group
                    class="mb-0"
                    label="Per page"
                    label-align-sm="right"
                    label-cols-lg="3"
                    label-cols-md="4"
                    label-cols-sm="6"
                    label-for="perPageSelect"
                    label-size="sm"
                >
                    <b-form-select
                        :options="pageOptions"
                        @change="getData"
                        id="perPageSelect"
                        size="sm"
                        v-model="perPage"
                    ></b-form-select>
                </b-form-group>
            </b-col>

            <b-col class="my-1" md="6" sm="7">
                <!--                <b-pagination-->
                <!--                    v-model="currentPage"-->
                <!--                    :total-rows="totalRows"-->
                <!--                    :per-page="perPage"-->
                <!--                    align="fill"-->
                <!--                    size="sm"-->
                <!--                    class="my-0"-->
                <!--                    @change="getData"-->
                <!--                ></b-pagination>-->
                <b-pagination
                    :per-page="perPage"
                    :total-rows="totalRows"
                    align="fill"
                    aria-controls="my-table"
                    v-model="currentPage"

                ></b-pagination>
            </b-col>
        </b-row>

        <!-- Main table element -->
        <b-table
            :busy="isBusy"
            :current-page="1"
            :fields="fields"
            :filter="filter"
            :filterIncludedFields="filterOn"
            :items="items"
            :per-page="perPage"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :sort-direction="sortDirection"
            @filtered="onFiltered"
            show-empty
            small
            stacked="md"
        >
            <template v-slot:table-busy>
                <div class="text-center text-danger my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Loading...</strong>
                </div>
            </template>
            <template v-slot:cell(name)="row">
                {{ row.value.first }} {{ row.value.last }}
            </template>

            <template v-slot:cell(actions)="row">
                <b-button @click="row.toggleDetails" size="sm" variant="warning">
                    <b-icon-pencil style="color: white"/>

                </b-button>
                <b-button @click="deleteTask(row.item, row.index, $event.target)" class="mr-1" size="sm"
                          variant="danger">

                    <b-icon-trash animation="throb"/>

                </b-button>

            </template>

            <template v-slot:row-details="row">
                <b-card>
                    <ul>
                        <li :key="key" v-for="(value, key) in row.item">{{ key }}: {{ value }}</li>
                    </ul>
                </b-card>
            </template>
        </b-table>

        <!-- Info modal -->
        <b-modal :id="infoModal.id" :title="infoModal.title" @hide="resetInfoModal"
                 @ok="deleteTaskConfirm( infoModal.delId)" ok-only ok-title="Да">
            <pre>{{ infoModal.content }}</pre>
        </b-modal>
    </b-container>
</template>

<script>
    import axios from 'axios'
    import EventBus from "../../event-bus"
    import Create from "./Create";
    import r from "../../route";

    export default {
        components: {
            Create
        },
        props: {
            needRefresh: false
        },
        data() {
            return {

                items: [],
                fields: [
                    {key: 'id', label: 'Id', sortable: true, sortDirection: 'desc'},
                    //  { key: 'category_id', label: 'category Id', sortable: true, class: 'text-center' },
                    {key: 'category', label: 'category', sortable: true, class: 'text-center'},
                    {key: 'question', label: 'Question', sortable: true, class: 'text-center'},
                    {key: 'type', label: 'Type', sortable: true, class: 'text-center'},
                    {
                        key: 'active',
                        label: 'is Active',
                        formatter: (value, key, item) => {
                            return value ? 'Yes' : 'No'
                        },
                        sortable: true,
                        sortByFormatted: true,
                        filterByFormatted: true
                    },
                    {key: 'actions', label: 'Actions'}
                ],
                isBusy: false,
                totalRows: 1,
                currentPage: 1,
                perPage: 10,
                pageOptions: [10, 20, 50],
                sortBy: '',
                sortDesc: false,
                sortDirection: 'asc',
                filter: null,
                filterOn: [],
                categories: [],
                infoModal: {
                    delId: null,
                    id: 'info-modal',
                    title: '',
                    content: ''
                }
            }
        },
        computed: {
            sortOptions() {
                // Create an options list from our fields
                return this.categories
                    .map(f => {
                        return {text: f.name, value: f.id}
                    })
            }
        },
        mounted() {
            // Set the initial number of items
            if (this.$cookies.get('user-token')) {
                this.getData()
            }
            EventBus.$on('refreshTable', function (data) {
                this.getData()
            })
        },
        methods: {
            getData() {

                this.isBusy = true


                const params = {
                    token: this.$cookies.get("user-token"),
                    perPage: this.perPage,
                    page: this.currentPage,
                    categoryId: this.sortBy,


                }

                // console.log(this.currentPage)

                axios.get(r("categories.index"), {
                        params
                    }
                ).then((response) => {
                    //   console.log(response)
                    this.categories = response.data.categories

                }, (error) => {
                    // console.log(error)
                    if (error.response.status === 401) {
                        this.$cookies.remove('user-token')
                    }


                })
                axios.get("api/tasks", {
                        params
                    }
                ).then((response) => {
                    console.log(response)
                    this.items = response.data.tasks.data
                    this.isBusy = false
                    this.totalRows = response.data.CountTasks
                }, (error) => {
                    console.log(error)
                    if (error.response.status === 401) {
                        this.$cookies.remove('user-token')
                    }


                })

            },

            deleteTask(item, index, button) {
                this.infoModal.delId = item.id
                this.infoModal.title = `Удалить поле № ${index + 1}`
                this.infoModal.content = "Вы точно хотите удалить елемент с полями?\n id:" + item.id + "\n Вопрос: " + item.question + "\n Категория: " + item.category
                this.$root.$emit('bv::show::modal', this.infoModal.id, button)


            },
            deleteTaskConfirm(id) {
                const params = {
                    token: this.$cookies.get("user-token"),
                    perPage: this.perPage,
                    page: this.currentPage,
                    categoryId: this.sortBy,


                }

                axios.delete("api/tasks/" + id, {
                    params
                }).then((response) => {
                    console.log(response)

                }, (error) => {
                    console.log(error)
                    if (error.response.status === 401) {
                        this.$cookies.remove('user-token')
                    }


                })
                this.getData()
            },
            resetInfoModal() {
                this.infoModal.title = ''
                this.infoModal.content = ''
            },
            onFiltered(filteredItems) {
                // Trigger pagination to update the number of buttons/pages due to filtering
                this.totalRows = filteredItems.length
                this.currentPage = 1
            }
        },
        watch: {
            currentPage: function () {
                this.getData()
            },
            sortBy: function () {
                this.getData()
            }
        }
    }
</script>

<style scoped>

</style>
