<template>
    <div>
        <label>
            {{ label }}
        </label>
        <input
        class="form-control"
        maxlength="50" 
        v-bind="$attrs"
        v-bind:value="value"
        v-on:input="$emit('input', $event.target.value)"
       >
       
    </div>
</template>

<script>
    export default {
        inheritAttrs: false,
        props: ['label', 'value']
    }
</script>
