<template>
    <div>
        <products-form 
            v-model="form"
            :showImage="false"
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
            prevRoutePath: ''
        }
    },
    beforeRouteEnter (to, from, next) {
        next((vm) => {
            vm.prevRoutePath = from.path
            if (vm.currentAuthorizedUser !== null) {
                if (vm.currentAuthorizedUser.company !== null) {
                    vm.form.company_id = vm.currentAuthorizedUser.company.id
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
            
            return storeProduct(formData, config)
                .then(() => {
                    this.$router.push({ path: this.prevRoutePath });
                })
                .catch((err) => console.error(err))
        }
    }
}
</script>

<style scoped>

</style>