<template>
    <div class="text-center">
        <search v-model="searchText" @search="searchFunction"/>
        <vs-button 
            v-if="checked != ''"
            danger
            class="btn"
            @click="showModal(checked)"
        >
            Видалити вибране
        </vs-button>
        <div class="checkAll">
            <label for="checkAll">Вибрати все на сторінці</label>
            <input type="checkbox" 
                name="checkAll" 
                class="checkbox" 
                :checked="checkIfSelectAll()" 
                @change="selectAll($event.target)"/>
        </div>
        <vs-table>
        <template #thead>
            <vs-tr>
                <vs-th>Вибрані</vs-th>
                <vs-th>Лого</vs-th>
                <vs-th>Назва</vs-th>
                <vs-th>Адреса</vs-th>
                <vs-th>Власник</vs-th>
                <vs-th>Переглянути</vs-th>
                <vs-th>Редагувати</vs-th>
                <vs-th>Видалити</vs-th>
            </vs-tr>
        </template>
        <template #tbody>
            <spinner v-if="loading"/>
            <vs-tr
                :key="i"
                v-for="(company, i) in companies"
                :data="company"
            >
                <vs-td>
                    <input type="checkbox" 
                        name="ids" 
                        class="checkbox" 
                        :checked="checked.includes(company.id)" 
                        :value="company.id" 
                        @change="selectItem($event.target, company.name)"
                    />
                </vs-td>
                <vs-td>
                    <img :src="`/images/${company.image}`"/> 
                </vs-td>
                <vs-td>
                    {{ company.name }}
                </vs-td>
                <vs-td>
                    {{ company.address }}
                </vs-td>
                <vs-td>
                    {{ company.user.name }}
                </vs-td>
                <vs-td>
                    <router-link :to="{name: 'companies.show', params: { id: company.id} }">
                        <i class="fas fa-eye"></i>
                    </router-link>
                </vs-td>
                <vs-td>
                    <router-link :to="{name: 'companies.edit', params: { id: company.id} }">
                        <i class="fas fa-edit"></i>
                    </router-link>
                </vs-td>
                <vs-td>
                    <i class="fas fa-trash-alt" @click="showModal(company.id, company.name)"></i>
                </vs-td>
            </vs-tr>
        </template>
        <template #footer>
            <vs-pagination v-if="totalPages > 1" v-model="page" :length="totalPages" @input="changePage" />
        </template>
      </vs-table>
      <delete-modal
            v-if="openModal && (typeof companyIdForDelete) != 'object'"
            title="Видалення компанії"
            mainText="Ви дійсно хочете видалити цю компанію:"  
            :deleteFunc="deleteCompany"  
            :items="companyNameForDelete"
            :id="companyIdForDelete"
            @cancel="cancelModal"
        />
        <delete-modal
            v-if="openModal && (typeof companyIdForDelete) == 'object'"
            title="Видалення компаній"
            mainText="Ви дійсно хочете видалити вибрані компанії:"  
            :deleteFunc="deleteCompanies"  
            :items="selectedCompaniesNames"
            :id="companyIdForDelete"
            @cancel="cancelModal"
        />
    </div>
</template>

<script>
import {loadCompanies, destroyCompany, deleteSelectedCompanies} from '../../../../api/companies'
import DeleteModal from '../../../elements/DeleteModal.vue'
import store from '../../../../store/index'
import Spinner from '../../../elements/Spinner.vue'
import Search from '../../../elements/Search.vue'

export default {
    components: {
        DeleteModal,
        Spinner,
        Search
    },
    data() {
        return {
            companies: [],
            checked: [],
            selectedCompaniesNames: [],
            page: 1,
            totalPages: 0,
            openModal: false,
            companyIdForDelete: null,
            companyNameForDelete: '',
            loading: false,
            searchText: '',
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

                Object.keys(localStorage).map(key => {
                    if (key.includes('company')) {
                        const length = 'company-'.length
                        let id = key.slice(length)
                        vm.checked.push(id)
                        vm.selectedCompaniesNames.push(localStorage.getItem(key))
                    }
                })
            })
            .catch(err => {
                console.error(err)
                vm.loading = true
            })
        })
    },
    methods: {
        async changePage(page = 1) {
            this.loading = true
            await loadCompanies ({page : page, q: this.searchText})
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
        showModal(id, name) {
            this.companyIdForDelete = id
            this.companyNameForDelete = name
            this.openModal = true
        },

        cancelModal() {
            this.companyIdForDelete = null
            this.companyNameForDelete = ''
            this.openModal = false
        },

        async deleteCompany(id, company) {
            await destroyCompany(id)
                .then( response => {
                    store.commit('deleteAuthUserCompany', response.data.data)
                    this.loading = true
                    if (this.checked.includes(id) && localStorage.getItem(`company-${id}`) && this.selectedCompaniesNames.includes(company)) {
                        let index = this.checked.indexOf(id)
                        this.checked.splice(index, 1)
                        index = this.selectedCompaniesNames.indexOf(company)
                        this.selectedCompaniesNames.splice(index, 1)
                        localStorage.removeItem(`company-${id}`)
                    }
                    loadCompanies({q: this.searchText})
                        .then(response => {
                            this.companies = response.data.data
                            this.page = response.data.meta.current_page
                            this.totalPages = response.data.meta.last_page
                            this.loading = false
                        })  
                        .catch(err => {
                            this.loading = true   
                            console.error(err)
                        })
                    console.log(`Компанію з ${id} було видалено`)
                })
                .catch(err => console.error(err))
        },

        async deleteCompanies(ids) {
            await deleteSelectedCompanies({'ids' : ids})
                .then(response => {
                    store.commit('deleteSomeCompanies', response.data.data)
                    this.loading = true
                    this.checked = []
                    this.selectedCompaniesNames = []
                    
                    Object.keys(localStorage).map(key => {
                        if (key.includes('company')) {
                            localStorage.removeItem(key)
                        }                
                    })

                    loadCompanies({q: this.searchText})
                        .then(response => {
                            this.companies = response.data.data
                            this.page = response.data.meta.current_page
                            this.totalPages = response.data.meta.last_page
                            this.loading = false
                        })  
                        .catch(err => {
                            this.loading = true
                            console.error(err)
                        })
                })
                .catch(err => console.error(err))
        },

        searchFunction(event) {
            this.loading = true
            loadCompanies({q: this.searchText})
                .then(response => {
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

        selectItem(checkbox, name) {
            if (checkbox.checked) {
                this.checked.push(checkbox.value) 
                this.selectedCompaniesNames.push(name)
                localStorage.setItem(`company-${checkbox.value}`, name)
            } else {
                let index = this.checked.indexOf(checkbox.value)
                this.checked.splice(index, 1)
                index = this.selectedCompaniesNames.indexOf(name)
                this.selectedCompaniesNames.splice(index, 1)
                localStorage.removeItem(`company-${checkbox.value}`)
            }
        },

        selectAll(checkbox) {
            if (checkbox.checked) {
                this.companies.map(company => {
                    this.checked.push(company.id)
                    this.selectedCompaniesNames.push(company.name)
                    localStorage.setItem(`company-${company.id}`, company.name)
                })
                this.getUniqueCheckedValues()
            } else {
                this.companies.map(company => {
                    let index = this.checked.indexOf(company.id)
                    this.checked.splice(index, 1)
                    index = this.selectedCompaniesNames.indexOf(company.name)
                    this.selectedCompaniesNames.splice(index, 1)
                    Object.keys(localStorage).map(key => {
                        if (key.includes(`company-${company.id}`)) {
                            localStorage.removeItem(key)
                        }                
                    })
                })
            }
        },

        checkIfSelectAll() {
            if (this.companies != '') {
                return this.companies.every(company => this.checked.includes(company.id))
            }
        },
 
        getUniqueCheckedValues() {
            const unique = (value, index, self) => {
                return self.indexOf(value) === index
            }

            let uniq = this.checked.filter(unique)
            this.checked = uniq
            uniq = this.selectedCompaniesNames.filter(unique)
            this.selectedCompaniesNames = uniq
        }
    }
}
</script>

<style lang="scss" scoped>

    .d-flex {
        display: flex;
    }

    .checkAll {
        text-align: left;
        padding-left: 50px;
    }

    .btn {
        font-size: 18px;
        padding: 5px;
    }

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

    .checkbox {
        width: 20px;
        height: 20px;
    }

</style>
