<template>
    <v-dialog/>
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
        productId: {
            type: String,
            required: true
        }
    },
    methods: {
        show () {
            this.$modal.show('dialog', {
                title: this.title,
                text: this.mainText,
                buttons: [
                    {   
                        title: 'Так',
                        handler: () => {
                            this.deleteFunc(this.productId)
                            this.$modal.hide('dialog')
                            this.$emit('cancel')
                        }
                    },
                    {
                        title: 'Скасувати',
                        handler: () => {
                            this.$modal.hide('dialog')
                            this.$emit('cancel')
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