<template>
    <div>
        <vs-input placeholder="Search" v-model="searchText" style="width: 300px;">
            <template #icon>
                <i class="fas fa-search"></i>
            </template>
        </vs-input>
        <vs-button @click="$emit('search', $event)">Шукати</vs-button>
    </div>
</template>

<script>
export default {
    name: 'Search',
    props: {
        value: {
            type: String,
            required: true
        },

    },
    computed: {
        searchText: {
            get () {
                return this.value
            },
            set (value) {
                this.$emit('input', value)
            }
        }
    }
}
</script>

<style scoped>
    .vs-input {
        width: 500px
    }
</style>
