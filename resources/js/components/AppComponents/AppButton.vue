<template>
    <div>
        <br>
        <button type="button" class="btn btn-success float-right" v-bind="$attrs" v-on:click="$emit(btnMethod)">{{btnName}} </button>       
    </div>
</template>

<script>
    export default {
        inheritAttrs: false,
        props: ['btnName','btnMethod']
    }
</script>
