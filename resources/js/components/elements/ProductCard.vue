<template>
    <div class="card shadow-md" @click="$emit('view', product.id)">
        <div class="card_container d-flex flex-col content-center items-center ">
            <img :src="`/images/${product.image}`" />
            <div class="name-block">
                <p class="name">{{ product.name }}</p>
                <router-link :to="{name: 'products.edit', params: { id: product.id} }">
                    <i class="fas fa-edit"></i>
                </router-link>
                <router-link>
                    <i class="fas fa-trash-alt"></i>
                </router-link>
            </div>
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
        height: 400px;
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

    .name-block {
        width: 100%;
        display: flex;
        align-items: center;
        border-bottom: .5px solid rgb(126, 124, 124);
    }

    .fa-edit {
        margin-left: 15px;
        color: rgb(15, 144, 196);
        transition: .5s all;
    }

    .fa-edit:hover {
        font-size: 18px;
        color: rgb(11, 97, 131);
    }

    .fas:last-child {
        margin-left: 5px;
    }

    .name {
        font-size: 18px;
        font-weight: 600;
        margin: 5px auto;
    }

    .def-block {
        display: flex;
        padding-left: 15px;
        margin-top: 25px;
    }

    .def-block:last-child {
        margin-top: 15px;
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