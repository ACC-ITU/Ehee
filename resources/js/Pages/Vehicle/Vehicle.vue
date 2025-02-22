<script setup>
import OwnerCard from "@/Pages/Vehicle/Partials/OwnerCard.vue";
import TabBar from "@/Components/TabBar.vue";
import {computed} from "vue";
import EheeLayout from "@/Layouts/EheeLayout.vue";
import DomainTab from "@/Pages/Vehicle/Partials/DomainTab.vue";
import VehicleTab from "@/Pages/Vehicle/Partials/VehicleTab.vue";
import RegistryTab from "@/Pages/Vehicle/Partials/RegistryTab.vue";

const props = defineProps({
    registries: Array,
    currentOwner: Object,
});

const tabs = [
    {id: 'registries', name: 'Registries'},
    {id: 'vehicles', name: 'Vehicles'},
    {id: 'domains', name: 'Domains'},
]

const domains = computed(() => {
    const domains = [];
    props.registries.map((registry) => {
        domains.push(...registry.domains);
    })
    return domains;
});

const vehicles = computed(() => {
    const vehicles = [];
    props.registries.map((registry) => {
        if(!registry.vehicle) return [];
        const vehicle = registry.vehicle;
        vehicle['registration_no'] = registry.registration_no;
        return vehicles.push(vehicle);
    })
    return vehicles;
});
</script>


<template>
    <EheeLayout>
        <div class=" py-8">
            <div class="max-w-7xl mx-auto px-4">

                <!-- Owner Profile -->
                <OwnerCard :owner="currentOwner.ownerDetails" class="mb-8" v-if="currentOwner"/>

                <!-- Tab System -->
                <TabBar :tabs="tabs">

                    <!-- Vehicles Tab -->
                    <template #vehicles>
                        <VehicleTab :vehicles="vehicles" />
                    </template>

                    <!-- Domains Tab -->
                    <template #domains>
                        <DomainTab :domains="domains"/>
                    </template>

                    <!-- Registries Tab -->
                    <template #registries>
                        <RegistryTab :registries="registries" :currentOwner="currentOwner" />
                    </template>

                </TabBar>
            </div>
        </div>

    </EheeLayout>
</template>
