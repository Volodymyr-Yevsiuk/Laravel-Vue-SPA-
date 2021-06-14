<template>
    <div class="card shadow-md" @click="$emit('view', product.id)">
        <div class="card_container d-flex flex-col content-center items-center ">
            <img :src="product.image" />
            <p class="name">{{ product.name }}</p>
            <div class="def-block">
                <label for="price">Ціна: </label>
                <span id="price" class="sp">{{product.price}} грн</span>
            </div>
            <div class="def-block">
                <label for="company">Виробник: </label>
                <span id="company" class="sp">{{product.company.name}}</span>
            </div>
        </div>
        <div class="view text-center">
            <router-link :to="{name: 'products.show', params:{ id: product.id }}" class="mx-auto">Переглянути >></router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ProductCard',
    props: {
        product: {
            type: Object,
            required: true
        },
    },
}
</script>

<style scoped>
    .card {
        width: 300px;
        height: 370px;
        display: inline-block;
        margin-bottom: 50px;
        padding: 15px;
        margin-left: 40px;
        box-shadow: inset 0px 0px 3px 0px rgb(232 230 230);
        position: relative;
    }

    .card:hover {
        cursor: pointer;
        box-shadow: 0px 0px 5px 0px rgb(232 230 230);
    }

    .card_container {
        position: relative;
    }

    .name {
        margin: 3px;
        text-align: center;
        font-size: 18px;
        font-weight: 600;
        border-bottom: .5px solid rgb(126, 124, 124);
    }

    .def-block {
        display: flex;
        padding-left: 15px;
        margin-top: 25px;
    }

    .view {
        width: 100%;
        border-top: .5px solid rgb(209, 206, 206);
        padding-top: 5px;
        position:absolute;
        bottom: 15px;
        left: 0;
    }

    .sp {
        margin-left: 5px;
    }

</style>