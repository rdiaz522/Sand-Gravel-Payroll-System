<template>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <AppTextBox label="Username" v-model="username" placeholder="Enter your username..."> </AppTextBox>
                <AppTextBox label="Password" v-model="password" placeholder="Enter your password..."> </AppTextBox>
                <AppButton name="Login" v-on:save="onSave"> </AppButton>  
            </div>
        </div>
    </div>
</template>

<script>
    import AppTextBox from '../AppComponents/AppTextBox.vue';
    import AppButton from '../AppComponents/AppButton.vue';
export default {
    data() {
        return {
            username:'',
            password:''
        }
    },
    components: {
        AppTextBox,
        AppButton
    },
    methods: {
        onSave: function() {
            const data = {
                username:this.username,
                password:this.password
            }
            const app_url = window.location.origin;
            const request = '/users/';
            const base_url = app_url + request;
            axios.get(base_url, data)
            .then(function (response) {
                // handle success
                console.log(response);
            })
        }
    }
}
</script>

<style>

</style>