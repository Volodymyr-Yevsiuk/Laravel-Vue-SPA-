<template>
    <div class="text-center">
        <span class="center">Користувачі</span>
         <vs-table>
        <template #thead>
          <vs-tr>
            <vs-th>
              Фото
            </vs-th>
            <vs-th>
              Ім'я
            </vs-th>
            <vs-th>
              Електронна пошта
            </vs-th>
            <vs-th> 
              Роль
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
          <vs-tr
            :key="i"
            v-for="(tr, i) in users"
            :data="tr"
          >
            <vs-td>
                <img :src="tr.profile_photo_path"/> 
            </vs-td>
            <vs-td>
                {{ tr.name }}
            </vs-td>
            <vs-td>
                {{ tr.email }}
            </vs-td>
            <vs-td>
                {{ tr.role.name }}
            </vs-td>
            <vs-td>
                <router-link :to="{name: 'admin.users.show', params: { id: tr.id} }">
                    <i class="fas fa-eye"></i>
                </router-link>
            </vs-td>
            <vs-td>
                <router-link :to="{name: 'admin.users.edit', params: { id: tr.id} }">
                    <i class="fas fa-edit"></i>
                </router-link>
            </vs-td>
            <vs-td>
                <i class="fas fa-trash-alt" @click="$emit('delete', tr.id)"></i>
            </vs-td>
          </vs-tr>
        </template>
        <template #footer>
            <vs-pagination v-if="totalPages > 1" v-model="page" :length="totalPages" @input="changePage" />
        </template>
      </vs-table>
    </div>
</template>

<script>
import {loadUsers} from '../../../../api/users'

export default {
    data() {
        return {
            users: [],
            page: 1,
            totalPages: 0
        }
    },
    async beforeRouteEnter (to, from, next) {
        
        await next(vm => {

            if (vm.currentAuthorizedUser === null) {
                window.location.href = '/login'
            }

            loadUsers()
            .then(response => {
                vm.users = response.data.data
                vm.page = response.data.meta.current_page
                vm.totalPages = response.data.meta.last_page
            })
            .catch(err => console.error(err))
        })
    },
    async beforeRouteUpdate (to, from, next) {
        await loadUsers()
            .then(response => {
                this.users = response.data.data
                this.page = response.data.meta.current_page
                this.totalPages = response.data.meta.last_page
            })
            .catch(err => console.error(err))
            next()
    },
    methods: {
        async changePage(page = 1) {
            await loadUsers (page)
                .then((response) => {
                    this.users = response.data.data
                    this.totalPages = response.data.meta.last_page
                    this.page = response.data.meta.current_page
                })
                .catch((err) => console.error(err))
        },
    }
}
</script>

<style scoped>
    .center {
        font-size: 72px;
        font-weight: 600;
        margin-top: 250px;
    }
</style>
