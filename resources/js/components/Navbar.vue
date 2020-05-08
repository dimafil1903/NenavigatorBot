<template>
    <div>
        <!-- As a link -->
        <router-view></router-view>

        <b-navbar toggleable="lg" type="light" variant="faded">
            <b-navbar-brand :to=welcome>Nenavigator</b-navbar-brand>
            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
            <b-collapse id="nav-collapse" is-nav>
                <b-nav-item :to=stat>Stat</b-nav-item>
                <b-nav-item :to="game" v-if="auth">Game</b-nav-item>

                <b-navbar-nav class="ml-auto">
                    <b-nav-item class="right" right v-if="auth" v-on:click="Logout">Logout</b-nav-item>

                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
    </div>
</template>

<script>
    import route from "../route";
    import axios from "axios";

    export default {
        data() {
            // this.GetToken()
            return {
                welcome: route('welcome'),
                game: route('game'),
                stat: route('statistics'),
                auth: false,
                online: false,
            }
        },
        methods: {


            Logout() {
                const params = {
                    token: this.$cookies.get("user-token")

                }
                axios.get("api/logout", {
                        params
                    }
                ).then((response) => {


                    // console.log(response)
                }, (error) => {
                    //console.log(error)

                })
                this.$router.push('/welcome')
                this.$cookies.remove('user-token')
                this.auth = false
            }


        },

        mounted() {
            // console.log(this.$cookies.keys())
            if (this.$cookies.get('user-token')) {
                this.auth = true
            }


        },
        name: "Navbar"
    }
</script>

<style scoped>

</style>
