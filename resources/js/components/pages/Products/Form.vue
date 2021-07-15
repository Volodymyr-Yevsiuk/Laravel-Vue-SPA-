<template>
    <div>
        <div class="container mx-auto">
            <form method="POST" enctype="multipart/form-data" @submit.prevent="$emit('submit')">
                <div class="form-block">
                    <label for="name">Назва:</label>
                    <vs-input 
                        type="text" 
                        name="name" 
                        id="name" 
                        v-model="form.name" 
                        class="form-input" 
                        placeholder="Введіть назву продукта"
                    />
                </div>
                <div class="form-block">
                    <label for="price">Ціна(грн):</label>
                    <vs-input 
                        type="number" 
                        name="price" 
                        id="price" 
                        v-model="form.price" 
                        class="form-input" 
                        placeholder="Введіть ціну продукта"/>
                </div>
                <div class="form-block" v-if="Object.keys(currentAuthorizedUser.company).length > 1">
                    <label for="companies">Компанія:</label>
                    <vs-select 
                        type="text" 
                        name="company_id" 
                        id="companies" 
                        v-model="form.company_id" 
                        class="form-input" 
                        placeholder="Виберіть компанію"
                    >
                        <vs-option 
                            v-for="company in currentAuthorizedUser.company"
                            :key="company.id"
                            :label="company.name"
                            :value="company.id"
                        >
                            {{ company.name }}
                        </vs-option>
                    </vs-select>
                </div>
                 <div class="form-block">
                    <label for="image">Зображення:</label>
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
                <div class="textarea-block">
                    <label for="description">Опис:</label>
                    <textarea name="description" id="description" v-model="form.description" placeholder="Опишіть продукт"></textarea>
                </div>
                <vs-button type="submit" class="create-button">{{ textBtn }}</vs-button>
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
        },
        textBtn: {
            type: String,
            required: true
        },
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