<template>
    <div>
        <companies-form 
            v-model="form"
            :showImage="true"
            @submit="editCompany"
        />
    </div>
</template>

<script>
import CompaniesForm from './Form.vue'
import { loadCompany } from '../../../api/companies'
import { updateCompany } from '../../../api/companies'
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
            companyId: ''
        }
    },
    beforeRouteEnter (to, from, next) {
        next((vm) => {
            vm.prevRoutePath = from.path
            if (vm.currentAuthorizedUser !== null) {
                vm.form.user_id = vm.currentAuthorizedUser.id
                vm.companyId = to.params.id

                loadCompany(to.params.id)
                    .then(response => vm.form = response.data.data)
                    .catch(err => console.error(err))
            } else {
                window.location.href = '/login'
                console.log('Авторизуйтесь будь ласка')
            }
        })
    },
    methods: {
        editCompany () {
            const formData = new FormData();
            const config = { 'content-type': 'multipart/form-data' };
            formData.set('name', this.form.name);
            formData.set('employees', this.form.employees);
            formData.set('description', this.form.description);
            formData.set('image', this.form.image);
            formData.set('address', this.form.address);
            formData.append('_method', 'PATCH')
            
            updateCompany(formData, config, this.companyId)
            .then((response) => {
                this.$router.push(this.prevRoutePath);
                store.commit('updateAuthUserCompany', response.data.data)
            })
            .catch((err) => console.error(err))
        }
    }
}
</script>

<style scoped>

</style>