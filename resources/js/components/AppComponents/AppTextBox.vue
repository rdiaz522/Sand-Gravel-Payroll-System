<template>
    <div>
        <label>
            {{ label }}
        </label>
        <input
        type="text"
        class="form-control"
        maxlength="20" 
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
