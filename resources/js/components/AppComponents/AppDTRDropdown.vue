<template>
    <div>
       <label>
        {{label}}
       </label>
        <select  class="form-control" v-bind="$attrs" v-bind:value="value" v-on:change="$emit('input',$event.target.value)">
         <option value="" disabled selected>Select your option</option>
            <option v-for="option in options" :key="option.id" :value="option.name"> 
               {{option.name}}
           </option>
        </select>
    </div>
</template>

<script>
    export default {
        inheritAttrs: false,
        props: ['label','value','options', 'elementType']
    }
</script>
