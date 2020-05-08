<template>
    <b-container class="">

        <b-row>
            <b-col lg="3">
                <b-overlay :show="showOverlay" rounded="sm">
                    <b-container>
                        <h6 class="text-center font-weight-bold">Категории</h6>

                        <b-list-group :key="category.id" v-for="category in categories">

                            <b-list-group-item :id="category.id"
                                               class="d-flex justify-content-between align-items-center">

                                <h6 class="w-100" v-if="updateInput!=category.id">
                                    {{category.name}}
                                </h6>
                                <b-input @blur="updateCategoryName(category)" required v-if="updateInput===category.id"
                                         v-model="category.name"/>
                                <a class="mr-1" v-if="updateInput!==category.id">
                                    <b-icon-pencil :id="'edit'+category.id" @click="updateInput=category.id"
                                                   @mouseout="mouseOver1=null" @mouseover="mouseOver1=category.id"
                                                   animation="throb" class="animated"
                                                   v-bind:class="{ rubberBand: mouseOver1 === category.id }"/>
                                </a>
                                <a class="mr-1" v-if="updateInput===category.id">
                                    <b-icon-check :id="'check'+category.id" @click="updateCategoryName(category)"
                                                  @mouseout="mouseOver1=null" @mouseover="mouseOver1=category.id"
                                                  animation="throb" class="animated"
                                                  v-bind:class="{ rubberBand: mouseOver1 === category.id }"/>
                                </a>
                                <a class="ml-1">
                                    <b-icon-trash :id="'trash'+category.id"
                                                  @click="deleteCategory(category, $event.target)"
                                                  @mouseout="mouseOver=null" @mouseover="mouseOver=category.id"
                                                  animation="throb" class="animated"
                                                  v-bind:class="{ rubberBand: mouseOver === category.id }"/>
                                </a>
                            </b-list-group-item>


                        </b-list-group>

                        <p class="text-center font-weight-bold" style="color: red"
                           v-if="Object.keys(categories).length<=0">Категории отсутсвуют </p>
                        <form @submit="AddCategory">
                            <b-input class="text-center mb-1 mt-5 " name="categoryName"
                                     placeholder="Имя новой категории" required v-model="NameCategory"></b-input>
                            <b-button block type="submit" variant="success">+</b-button>
                        </form>
                    </b-container>
                </b-overlay>
            </b-col>
            <b-col>
                <b-container class="mt-5 mb-5">
                    <b-overlay :show="overlayOnForm" rounded="sm">
                        <b-form @submit="createTask">
                            <h5 class="text-center font-weight-bold">Добавить новое задание</h5>

                            <b-form-row class="m-3">
                                <b-col sm="2">
                                    <label for="select-category">Категория:</label>
                                </b-col>
                                <b-col sm="10">
                                    <b-form-select
                                        :options="categories"
                                        class="mb-3"
                                        id="select-category"
                                        name="category_id"
                                        required
                                        text-field="name"
                                        v-model="selectedCategory"
                                        value-field="id"
                                    ></b-form-select>

                                </b-col>
                            </b-form-row>
                            <b-form-row class="m-3">


                                <b-col sm="2">
                                    <label for="radio-category">Тип:</label>
                                </b-col>
                                <b-col sm="10">
                                    <b-form-radio-group :options="radioOptions" id="radio-category" name="type" required
                                                        v-model="type">
                                        <b-form-invalid-feedback :state="state">Выбери что-то</b-form-invalid-feedback>

                                    </b-form-radio-group>
                                </b-col>
                            </b-form-row>
                            <b-form-row class="m-3">


                                <b-col sm="2">
                                    <label for="textarea-small">Вопрос:</label>
                                </b-col>
                                <b-col sm="10">
                                    <b-form-textarea
                                        id="textarea-small"
                                        max-rows="5"
                                        name="question"
                                        placeholder="Вопрос"
                                        required
                                        size="sm"
                                        v-model="question"
                                    ></b-form-textarea>
                                </b-col>
                            </b-form-row>

                            <b-form-row class="m-3">


                                <b-col sm="2">
                                    <label for="isActive">Активный:</label>
                                </b-col>
                                <b-col sm="10">
                                    <b-form-checkbox id="isActive" name="check-button" switch v-model="active">

                                    </b-form-checkbox>
                                </b-col>
                            </b-form-row>
                            <b-form-row class="justify-content-center align-content-center">

                                <div class="align-self-center">
                                    <b-button type="submit">Создать</b-button>
                                </div>

                            </b-form-row>
                        </b-form>
                    </b-overlay>
                </b-container>
            </b-col>
        </b-row>
        <b-modal :id="infoModal.id" :title="infoModal.title" @hide="resetInfoModal"
                 @ok="deleteCategoryConfirm( infoModal.delId)" ok-only ok-title="Да">
            <pre>{{ infoModal.content }}</pre>
        </b-modal>
    </b-container>
</template>

<script>


    import axios from "axios";

    import r from "../../route";

    export default {

        name: "Create",

        data() {

            return {
                show: true,
                showOverlay: false,
                overlayOnForm: false,
                updateInput: false,
                question: null,
                NameCategory: null,
                showInputToAdd: false,
                mouseOver: null,
                mouseOver1: null,
                categories: {},


                selectedCategory: "",
                active: true,
                type: null,
                radioOptions: [
                    {text: 'Правда', value: 'truth'},
                    {text: 'Действие', value: 'action'},
                ],
                infoModal: {
                    delId: null,
                    id: 'delete-modal',
                    title: '',
                    content: ''
                }
            }
        },

        computed: {
            state() {
                return Boolean(this.type)
            }
        },
        methods: {

            updateAllData() {
                this.getData()
                this.$emit('clickedSomething')
            },
            updateCategoryName(item) {
                this.updateInput = false
                const params = {
                    token: this.$cookies.get("user-token"),
                    nameCategory: item.name


                }
                const formData = new FormData();


                axios.put("api/categories/" + item.id,
                    params
                ).then((response) => {
                    this.showOverlay = false
                    //  console.log(response)
                    this.updateAllData()
                }, (error) => {
                    //  console.log(error)
                    if (error.response.status === 401) {
                        this.$cookies.remove('user-token')
                    }


                })

            },
            deleteCategory(item, button) {


                this.infoModal.delId = item.id
                this.infoModal.title = `Удалить Категорию № ${item.id}`
                this.infoModal.content = "Вы точно хотите удалить Категорию?\n id:" + item.id + "\n Имя: " + item.name


                this.$root.$emit('bv::show::modal', this.infoModal.id, button)


            },
            deleteCategoryConfirm(id) {
                const params = {
                    token: this.$cookies.get("user-token"),


                }
                this.showOverlay = true
                axios.delete("api/categories/" + id + "?token=" + params.token
                ).then((response) => {
                    this.showOverlay = false
                    // console.log(response)
                    this.updateAllData()
                }, (error) => {
                    //  console.log(error)
                    if (error.response.status === 401) {
                        this.$cookies.remove('user-token')
                    }


                })
            },
            getData() {
                const params = {
                    token: this.$cookies.get("user-token"),


                }


                axios.get(r("categories.index"), {
                        params
                    }
                ).then((response) => {
                    //  console.log(response)
                    this.categories = response.data.categories

                }, (error) => {
                    //    console.log(error)
                    if (error.response.status === 401) {
                        this.$cookies.remove('user-token')
                    }


                })

            },
            AddCategory(e) {
                this.showOverlay = true
                const params = {
                    token: this.$cookies.get("user-token"),


                }
                e.preventDefault();
                const formData = new FormData();
                formData.append('nameCategory', this.NameCategory);
                axios.post(r("categories.store") + "?token=" + params.token,

                    formData
                ).then((response) => {

                    // console.log(response)
                    this.updateAllData()
                    this.NameCategory = null
                    //  this.categories = response.data.categories
                    this.showOverlay = false
                }, (error) => {
                    // console.log(error)
                    if (error.response.status === 401) {
                        this.$cookies.remove('user-token')
                    }


                })
            },
            createTask(e) {
                this.overlayOnForm = true
                const params = {
                    token: this.$cookies.get("user-token"),


                }
                const formData = new FormData();

                formData.append('category_id', this.selectedCategory);
                formData.append('type', this.type);
                formData.append('question', this.question);
                formData.append('active', this.active);
                e.preventDefault();
                axios.post(r("tasks.store") + "?token=" + params.token,
                    formData
                ).then((response) => {
                    //   console.log(response)

                    this.selectedCategory = null
                    this.type = null
                    this.question = null
                    this.updateAllData()
                    this.overlayOnForm = false


                    //  this.categories = response.data.categories

                }, (error) => {
                    // console.log(error)
                    if (error.response.status === 401) {
                        this.$cookies.remove('user-token')
                    }


                })

            }, resetInfoModal() {
                this.infoModal.title = ''
                this.infoModal.content = ''
            },
        },
        mounted() {
            if (this.$cookies.get('user-token')) {
                this.updateAllData()
            }

        }
    }
</script>

<style scoped>

</style>
