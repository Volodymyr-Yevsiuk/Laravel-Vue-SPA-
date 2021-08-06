<template>
    <v-dialog :clickToClose="false"/>
</template>

<script>
export default {
    name: 'DeleteModal',
    props: {
        title: {
            type: String, 
            required: true
        },
        mainText: {
            type: String,
            required: true
        },
        deleteFunc: {
            type: Function,
            required: true
        },
        id: {
            required: true
        },
        items: {
            required: true
        }
    },
    methods: {
        closeModal () {
            this.$modal.hide('dialog')
            this.$emit('cancel')
        },

        show () {
            this.$modal.show('dialog', {
                title: this.title,
                text: `<span class="vue-dialog-sentence">${this.mainText}</span> <br/>${this.items} ?`,
                buttons: [
                    {   
                        title: 'Так',
                        handler: () => {
                            this.deleteFunc(this.id, this.items)
                            this.$modal.hide('dialog')
                            this.$emit('cancel')
                        }
                    },
                    {
                        title: 'Скасувати',
                        handler: () => { 
                            this.closeModal()
                        }
                    },
                ]
            })
        },
    },
    mounted () {
        this.show()
    }
}
</script>

<style>

    .vue-dialog-content-title {
        font-size: 20px;
        font-weight: 700;
        text-decoration: underline;
    }

    .vue-dialog-sentence {
        font-size: 16px;
        font-weight: 600;
    }

</style>