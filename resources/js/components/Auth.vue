<template>
    <b-container class="h-100">
        <router-view></router-view>

        <Navbar></Navbar>


        <b-container class=" h-100  d-flex justify-content-center ">
            <div class=" align-self-center ">
                <b-form @submit="ClickHandler">
                    <h2 class="text-center"> Авторизация </h2>

                    <b-row class="my-1 m-2 col">
                        <b-col sm="3">
                            <label for="email">Email </label>
                        </b-col>
                        <b-col sm="9">
                            <b-form-input
                                :state="isEmailValid"
                                id="email"
                                placeholder="Введите email"
                                v-model="email"
                            ></b-form-input>
                        </b-col>
                    </b-row>

                    <b-row class="my-1 m-2 col">

                        <b-col sm="3">
                            <label for="password">Пароль </label>
                        </b-col>
                        <b-col sm="9">
                            <b-form-input
                                :state="PasswordState"
                                aria-describedby="input-live-feedback"
                                id="password"

                                placeholder="Введите пароль"
                                type="password"
                                v-model="password"
                            ></b-form-input>
                            <b-form-invalid-feedback
                                id="input-live-feedback">
                                Длина пароля должна быть больше 6.
                            </b-form-invalid-feedback>
                            <div style="color: red" v-if="authError">
                                Неверные логин или пароль
                            </div>
                            <!-- This is a form text block (formerly known as help block) -->

                        </b-col>

                    </b-row>
                    <b-row class="my-1  m-2  d-flex justify-content-center ">
                        <b-button
                            type="submit"
                        >Login
                        </b-button>
                    </b-row>
                </b-form>
            </div>
        </b-container>
    </b-container>
</template>

<script>
    import axios from 'axios'


    import Navbar from "./Navbar";

    export default {
        components: {
            Navbar

        },
        name: "Auth",
        computed: {
            PasswordState() {
                if (this.password.length >= 6) {
                    this.passwordChecked = true
                    return true
                } else {
                    this.passwordChecked = false
                    return false
                }
            },
            isEmailValid() {
                if (this.email === "") {
                    this.emailChecked = false
                    return false
                } else if (this.reg.test(this.email)) {
                    this.emailChecked = true
                    return true
                } else {
                    this.emailChecked = false
                    return false
                }
            },
        },
        data() {
            return {
                passwordChecked: false,
                emailChecked: false,
                password: '',
                email: '',
                authError: false,
                reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/
            }

        },
        methods: {


            ClickHandler: function (evt) {
                evt.preventDefault()
                let bodyFormData = new FormData();
                console.log("CLICKED")
                if (this.passwordChecked && this.emailChecked) {
                    bodyFormData.set('email', this.email);
                    bodyFormData.set('password', this.password);


                    axios(
                        {
                            method: 'post',
                            url: 'api/login',
                            data: bodyFormData,
                            headers: {'Content-Type': 'multipart/form-data'}
                        }
                    ).then((response) => {

                        //console.log("dwqdwqdqwd", response.data.topMsg);
                        console.log(response);
                        this.authError = false;
                        //sessionStorage.setItem('user-token', response.data.token) // store the token in localstorage


                        localStorage.setItem("user-token", response.data.token);
                        this.$cookies.remove("user-token");
                        this.$cookies.set("user-token", response.data.token, '7h');
                        const status =
                            JSON.parse(response.status);


                        // axios.get('/api/getstatus').then(response=>{
                        //
                        //     console.log(response)
                        // }).catch(error=>{
                        //     console.log(error)
                        // })
                        //redirect logic
                        if (this.$cookies.get('user-token')) {
                            this.$router.push('/welcome')
                        }
                        // if(this.$cookies.isKey('user-token')){
                        //     this.$router.push('/welcome')
                        // }
                    }, (error) => {
                        this.authError = true
                        console.log(error);
                    });
                }
            }
        },
        mounted() {
            if (this.$cookies.get('user-token')) {
                this.$router.push('/welcome')
            }
        }
    }

</script>

<style scoped>

</style>
