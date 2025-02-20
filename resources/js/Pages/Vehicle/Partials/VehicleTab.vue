<script setup>
import EmptyCard from "@/Components/EmptyCard.vue";
import VehicleCard from "@/Pages/Vehicle/Partials/VehicleCard.vue";

const props = defineProps({
    vehicles: Array,
})
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" v-if="vehicles">
        <VehicleCard
            v-for="vehicle in vehicles"
            :key="vehicle.id"
            :vehicle="vehicle"
        />
    </div>
    <div v-else class="">
        <EmptyCard />
    </div>
</template>
