<template>
    <div>
        <products-form 
            v-model="form"
            :showImage="false"
            :showCompanies="true"
            textBtn="Створити"
            @submit="createProduct"
        />
    </div>
</template>

<script>
import ProductsForm from './Form.vue'
import { storeProduct } from '../../../api/products'

export default {
    components: {ProductsForm},
    data() {
        return {
            form: {
                name: '',
                price: 0,
                description: '',
                image: '',
                company_id: ''
            },
        }
    },
    beforeRouteEnter (to, from, next) {
        next((vm) => {
            if (vm.currentAuthorizedUser !== null) {
                if (Object.keys(vm.currentAuthorizedUser.company).length > 0) {
                    if (Object.keys(vm.currentAuthorizedUser.company).length === 1) {
                        vm.form.company_id = vm.currentAuthorizedUser.company.id || vm.currentAuthorizedUser.company[0].id 
                    } else {
                        vm.form.company_id = ''
                    }
                } else {
                    vm.$router.push({name: 'companies.create'})
                    console.log('Спочатку зареєструйте компанію')
                }
            } else {
                window.location.href = '/login';
            }  
            
        })
    },
    methods: {
        createProduct () {
            const formData = new FormData();
            const config = { 'content-type': 'multipart/form-data' };
            formData.append('name', this.form.name);
            formData.append('price', this.form.price);
            formData.append('description', this.form.description);
            formData.append('image', this.form.image);
            formData.append('company_id', this.form.company_id);
            
            storeProduct(formData, config)
                .then(() => {
                    this.$router.go(-1);
                })
                .catch((err) => console.error(err))
        }
    }
}
</script>

<style scoped>

</style>