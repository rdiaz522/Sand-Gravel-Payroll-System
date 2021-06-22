<template>
   <div class="row">
        <div class="col-xl-4 col-lg-4">
            <PositionComponent 
            title="Add new Position" 
            name="Save" event="save">
             </PositionComponent>
        </div>
        <div class="col-xl-4 col-lg-4">
            <LocationComponent 
            title="Add new Location" 
            name="Save" event="save">
             </LocationComponent>
        </div>
    </div>
</template>

<script>
import PositionComponent from './PositionComponent.vue';
import LocationComponent from './LocationComponent.vue';
export default {
    components: {
        PositionComponent,
        LocationComponent
    }
}
</script>

<style>

</style>