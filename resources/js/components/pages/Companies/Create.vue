<template>
    <div>
        <companies-form 
            v-model="form"
            :showImage="false"
            @submit="createCompany"
        />
    </div>
</template>

<script>
import CompaniesForm from './Form.vue'
import { storeCompany } from '../../../api/companies'
import store from '../../../store/index'

export default {
    components: {CompaniesForm},
    data() {
        return {
            form: {
                name: '',
                employees: 0,
                description: '',
                image: '',
                address: '',
                user_id: ''
            },
            prevRoutePath: '',
            showAlert: false
        }
    },
    mounted () {
        if (this.showAlert) {
            alert('Спочатку зареєструйте компанію!')
            this.showAlert = false
        }
    },
    beforeRouteEnter (to, from, next) {
        
        next((vm) => {
            vm.prevRoutePath = from.path
            vm.showAlert = from.name === 'products.create' ? true : false
            if (vm.currentAuthorizedUser !== null) {
                vm.form.user_id = vm.currentAuthorizedUser.id
            } else {
                window.location.href = '/login'
                console.log('Авторизуйтесь будь ласка')
            }
        })
    },
    methods: {
        createCompany () {
            const formData = new FormData();
            const config = { 'content-type': 'multipart/form-data' };
            formData.append('name', this.form.name);
            formData.append('employees', this.form.employees);
            formData.append('description', this.form.description);
            formData.append('image', this.form.image);
            formData.append('address', this.form.address);
            formData.append('user_id', this.form.user_id);
            
            storeCompany(formData, config)
            .then((response) => {
                this.$router.push(this.prevRoutePath);
                store.commit('createAuthUserCompany', response.data.data)
                
            })
            .catch((err) => console.error(err))
        }
    },
}
</script>

<style scoped>

</style>