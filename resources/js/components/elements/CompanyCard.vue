<template>
    <div class="card shadow-md" @click="$emit('view', company.id)">
        <div class="card_container d-flex flex-col content-center items-center ">
            <img :src="`/images/${company.image}`" class="mx-auto" />
            <div class="name-block">
                <p class="name">{{ company.name }}</p>
                <div v-if="isUserCompany === true"> 
                    <router-link  :to="{name: 'companies.edit', params: { id: company.id} }">
                        <i class="fas fa-edit"></i>
                    </router-link>
                    <i class="fas fa-trash-alt" @click="$emit('delete', company.id)"></i>
                </div>
            </div>
            <div class="def-block">
                <label for="employees">К-сть співробітників: </label>
                <span id="employees" class="sp">Понад {{company.employees}} працівників</span>
            </div>
            <div class="def-block">
                <label for="company">Адреса: </label>
                <span id="company" class="sp">{{company.address}}</span>
            </div>
        </div>
        <div class="view text-center">
            <router-link :to="{name: 'companies.show', params:{ id: company.id }}" class="mx-auto">Переглянути >></router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CompanyCard',
    props: {
        company: {
            type: Object,
            required: true
        },
    },
    data () {
        return {
            usersIds: [],
            isUserCompany: null
        }
    },
    mounted () {
        this.isUserCompany = this.company.user.id === this.currentAuthorizedUser.id ? true : false
    }
}
</script>

<style scoped>
    .card {
        width: 400px;
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
        /* border-bottom: .5px solid rgb(126, 124, 124); */
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

    .fa-trash-alt {
        margin-left: 10px;
        color: rgb(170, 27, 27);
        transition: .5s all;
    }

    .fa-trash-alt:hover {
        font-size: 18px;
        color: rgb(126, 22, 22);
    }

</style>