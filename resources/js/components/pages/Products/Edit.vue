<template>
    <div>
        <products-form 
            v-model="form"
            :showImage="true"
            :showCompanies="false"
            textBtn="Редагувати"
            @submit="editProduct"
        />
    </div>
</template>

<script>
import ProductsForm from './Form.vue'
import { loadProduct } from '../../../api/products'
import { updateProduct } from '../../../api/products'

export default {
    components: {ProductsForm},
    data() {
        return {
            form: {
                name: '',
                price: 0,
                description: '',
                image: '',
            },
            prevRoutePath: '',
            productId: ''
        }
    },
    async beforeRouteEnter (to, from, next) {
        await next((vm) => {
            vm.prevRoutePath = from.path
            if (vm.currentAuthorizedUser !== null) {
                if (vm.currentAuthorizedUser.company !== null) {
                    vm.productId = to.params.id

                    loadProduct(to.params.id)
                        .then(response => vm.form = response.data.data)
                        .catch(err => console.error(err))
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
        editProduct (form) {
            let formData = new FormData();
            const config = { 'content-type': 'multipart/form-data' };
            formData.set('name', this.form.name);
            formData.set('price', this.form.price);
            formData.set('description', this.form.description);
            formData.set('image', this.form.image);
            formData.append('_method', 'PATCH')
            
            updateProduct(formData, config, this.productId)
                .then(() => {
                    this.$router.push({name: 'dashboard'})
                })
                .catch((err) => console.error(err))
        }
    }
}
</script>

<style scoped>

</style>