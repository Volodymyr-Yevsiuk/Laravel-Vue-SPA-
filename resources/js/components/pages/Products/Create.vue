<template>
    <div>
        <products-form 
            v-model="form"
            @submit="createProduct"
        />
    </div>
</template>

<script>
import ProductsForm from './Form.vue'
import { storeProduct } from '../../../api/products'
import axios from 'axios' 

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
            }
        }
    },
    async beforeRouteEnter (to, from, next) {
        next((vm) => {
            vm.form.company_id = vm.currentAuthorizedUser.company.id
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
                    this.$router.push({ name: 'products.index' });
                })
                .catch((err) => console.error(err))
        }
    }
}
</script>

<style scoped>

</style>