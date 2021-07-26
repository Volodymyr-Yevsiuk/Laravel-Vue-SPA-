<template>
    <div class="text-center">
        <span class="center">Компанії</span>
         <vs-table>
        <template #thead>
          <vs-tr>
            <vs-th>
              Лого
            </vs-th>
            <vs-th>
              Назва
            </vs-th>
            <vs-th>
              Адреса
            </vs-th>
            <vs-th> 
              Власник
            </vs-th>
            <vs-th> 
              Переглянути
            </vs-th>
            <vs-th> 
              Редагувати
            </vs-th>
            <vs-th> 
              Видалити
            </vs-th>
          </vs-tr>
        </template>
        <template #tbody>
            <spinner v-if="loading"/>
            <vs-tr
                :key="i"
                v-for="(tr, i) in companies"
                :data="tr"
            >
                <vs-td>
                    <img :src="`/images/${tr.image}`"/> 
                </vs-td>
                <vs-td>
                    {{ tr.name }}
                </vs-td>
                <vs-td>
                    {{ tr.address }}
                </vs-td>
                <vs-td>
                    {{ tr.user.name }}
                </vs-td>
                <vs-td>
                    <router-link :to="{name: 'companies.show', params: { id: tr.id} }">
                        <i class="fas fa-eye"></i>
                    </router-link>
                </vs-td>
                <vs-td>
                    <router-link :to="{name: 'companies.edit', params: { id: tr.id} }">
                        <i class="fas fa-edit"></i>
                    </router-link>
                </vs-td>
                <vs-td>
                    <i class="fas fa-trash-alt" @click="showModal(tr.id)"></i>
                </vs-td>
            </vs-tr>
        </template>
        <template #footer>
            <vs-pagination v-if="totalPages > 1" v-model="page" :length="totalPages" @input="changePage" />
        </template>
      </vs-table>
      <delete-modal
            v-if="openModal"
            title="Видалення компанії"
            mainText="Ви дійсно хочете видалити цю компанію?"  
            :deleteFunc="deleteCompany"  
            :id="companyIdForDelete"
            @cancel="cancelModal"
        />
    </div>
</template>

<script>
import {loadCompanies} from '../../../../api/companies'
import {destroyCompany} from '../../../../api/companies'
import DeleteModal from '../../../elements/DeleteModal.vue'
import store from '../../../../store/index'
import Spinner from '../../../elements/Spinner.vue'

export default {
    components: {
        DeleteModal,
        Spinner
    },
    data() {
        return {
            companies: [],
            page: 1,
            totalPages: 0,
            openModal: false,
            companyIdForDelete: null,
            loading: false
        }
    },
    async beforeRouteEnter (to, from, next) {
        
        await next(vm => {

            if (vm.currentAuthorizedUser === null) {
                window.location.href = '/login'
            } else {
                if (vm.currentAuthorizedUser.role.name !== 'Admin') {
                    vm.$router.push({name: 'forbidden'})
                }
            }

            vm.loading = true
            loadCompanies()
            .then(response => {
                vm.companies = response.data.data
                vm.page = response.data.meta.current_page
                vm.totalPages = response.data.meta.last_page
                vm.loading = false
            })
            .catch(err => {
                console.error(err)
                vm.loading = true
            })
        })
    },
    async beforeRouteUpdate (to, from, next) {
        await loadCompanies()
            this.loading = true
            .then(response => {
                this.companies = response.data.data
                this.page = response.data.meta.current_page
                this.totalPages = response.data.meta.last_page
                this.loading = false
            })
            .catch(err => {
                console.error(err)
                this.loading = true
            })            
            next()
    },
    methods: {
        async changePage(page = 1) {
            this.loading = true
            await loadCompanies (page)
                .then((response) => {
                    this.companies = response.data.data
                    this.totalPages = response.data.meta.last_page
                    this.page = response.data.meta.current_page
                    this.loading = false
                })
                .catch(err => {
                    console.error(err)
                    this.loading = true
                })  
        },
        showModal(id) {
            this.companyIdForDelete = id.toString()
            this.openModal = true
        },

        cancelModal() {
            this.companyIdForDelete = null
            this.openModal = false
        },

        async deleteCompany(id) {
            await destroyCompany(id)
                .then( response => {
                    store.commit('deleteAuthUserCompany', response.data.data)
                    this.loading = true
                    loadCompanies()
                        .then(response => {
                            this.companies = response.data.data
                            this.page = response.data.meta.current_page
                            this.totalPages = response.data.meta.last_page
                            this.loading = false
                        })  
                        .catch(err => console.error(err))
                    console.log(`Компанію з ${id} було видалено`)
                })
                .catch(err => console.error(err))
        }
    }
}
</script>

<style lang="scss" scoped>

    .center {
        font-size: 72px;
        font-weight: 600;
        margin-top: 250px;
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
        cursor:pointer;
        font-size: 18px;
        color: rgb(126, 22, 22);
    }

    
</style>
