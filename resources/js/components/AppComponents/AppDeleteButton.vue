<template>
    <button @click="deleteUser">
        <slot>Delete</slot>
    </button>
</template>

<script>
    export default{
        props: ['id', 'firstname'],
        methods: {
            deleteUser() {
                alert(`I am ${this.firstname}!`)
            }
        },
    }
</script>