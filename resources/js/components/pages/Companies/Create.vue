<template>
    <div>
        <companies-form 
            v-model="form"
            @submit="createCompany"
        />
    </div>
</template>

<script>
import CompaniesForm from './Form.vue'
import { storeCompany } from '../../../api/companies'

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
            prevRoutePath: ''
        }
    },
    beforeRouteEnter (to, from, next) {
        next((vm) => {
            vm.prevRoutePath = from.path
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
            .then(() => {
                this.$router.push(this.prevRoutePath);
            })
            .catch((err) => console.error(err))
        }
    }
}
</script>

<style scoped>

</style>