<template>
    <b-container>
        <Navbar></Navbar>
        <Tasks></Tasks>
        <Categories></Categories>

    </b-container>
</template>

<script>
    import Categories from "./Categories";
    import Navbar from "../Navbar";
    import Tasks from "./Tasks";
    import Create from "./Create";
    import router from "../../router";

    export default {
        name: "TruthOrAction",
        components: {Create, Tasks, Categories, Navbar},

        methods: {
            GetToken() {
                setInterval(function () {   //  call a 3s setTimeout when the loop is called

                    if (this.$cookies.get('user-token')) {
                        this.auth = true
                    } else {


                        this.auth = false
                        if (router.app.$route.path === "/game") {
                            router.push('/auth')
                        }

                    }                     //  ..  setTimeout()
                }, 5000)
            },
        },
        mounted() {

            if (!this.$cookies.isKey('user-token')) {
                this.$router.push('/auth')
            }
            this.GetToken()
        }
    }
</script>

<style scoped>

</style>
