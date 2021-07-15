<template>
    <div>
        <div class="container mx-auto">
            <form method="POST" @submit.prevent="$emit('submit')" >
                <div class="form-block">
                    <label for="name">Назва:</label>
                    <vs-input 
                        type="text" 
                        name="name" 
                        id="name" 
                        v-model="form.name" 
                        class="form-input" 
                        placeholder="Введіть назву компанії"
                    />
                </div>
                <div class="form-block">
                    <label for="employees">Кількість працівників:</label>
                    <vs-select 
                        type="number" 
                        name="employees" 
                        id="employees" 
                        v-model="form.employees" 
                        class="form-input" 
                        placeholder="Виберіть приблизну к-сть працівників"
                    >
                        <vs-option 
                            v-for="(number, i) in numbers"
                            :key="i"
                            :label="`Понад ${number} працівників`"
                            :value="number"
                        >
                            Понад {{ number }}
                        </vs-option>
                    </vs-select>
                </div>
                 <div class="form-block">
                    <label for="image">Логотип компанії(100х100):</label>
                    <vs-input 
                        type="file" 
                        name="image" 
                        id="image" 
                        class="form-input" 
                        @change="loadImage"
                    />
                    <img   
                        v-if="showImage && typeof(form.image) == 'string'"
                        id="image" 
                        :src="`/images/${form.image}`" 
                    />
                </div>
                 <div class="form-block">
                    <label for="address">Адреса:</label>
                    <vs-input 
                        type="text" 
                        name="address" 
                        id="address" 
                        v-model="form.address" 
                        class="form-input" 
                        placeholder="Місто, вулиця"
                    />
                </div>
                <div class="textarea-block">
                    <label for="description">Опис:</label>
                    <textarea name="description" id="description" v-model="form.description" placeholder="Опишіть компанію"></textarea>
                </div>
                <vs-button type="submit" class="create-button">Створити</vs-button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        value: {
            type: Object,
            required: true
        },
        showImage: {
            type: Boolean,
            required: true
        }
    },
    data() {
        return {
            numbers: [10, 20, 30, 40, 50, 100, 250, 500, 1000]
        }
    },
    computed: {
        form: {
            get () {
                return this.value
            },
            set (value) {
                this.$emit('input', value)
            }
        }
    },
    methods: {
        loadImage (e) {
            this.value.image = e.target.files[0]
        } 
    }
}
</script>

<style scoped>
    .container{
        width: 900px;
        margin-top: 50px;
        border: .5px solid rgb(212, 210, 210);
        padding: 25px;
    }

    .create-button {
        font-size: 15px;
        padding: 5px;
        margin-top: 50px;
    }

    .textarea-block {
        display: flex;
        flex-direction: column;
    }

    textarea {
        width: 300px;
        height: 150px;
        border: 1px solid rgb(187, 183, 183);
        font-size: 16px;
        border-radius: 5px;
    }

    .form-input {
        font-size: 16px;
    }

    .form-block {
        margin-bottom: 20px;
    }
</style>