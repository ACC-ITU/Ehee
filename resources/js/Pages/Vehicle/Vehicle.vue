<script setup>

// Tab configuration
import DomainCard from "@/Pages/Vehicle/Partials/DomainCard.vue";
import OwnerCard from "@/Pages/Vehicle/Partials/OwnerCard.vue";
import TabBar from "@/Components/TabBar.vue";
import RegistryCard from "@/Pages/Vehicle/Partials/RegistryCard.vue";
import {computed} from "vue";
import EheeLayout from "@/Layouts/EheeLayout.vue";
import VehicleCard from "@/Pages/Vehicle/Partials/VehicleCard.vue";

const props = defineProps({
    registries: Array,
});

const tabs = [
    {id: 'registries', name: 'Registries'},
    {id: 'vehicles', name: 'Vehicles'},
    {id: 'domains', name: 'Domains'},
]

const domains = computed(() => {
    const domains = [];
    props.registries.map((registry) => {
        return domains.push(...registry.domains);
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
        <div class="min-h-screen bg-gray-50 py-8">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Owner Profile -->
                <OwnerCard :owner="registries[0].owners[0].ownerDetails" class="mb-8"/>

                <!-- Tab System -->
                <TabBar :tabs="tabs">
                    <!-- Vehicles Tab -->
                    <template #vehicles>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <VehicleCard
                                v-for="vehicle in vehicles"
                                :key="vehicle.id"
                                :vehicle="vehicle"
                            />
                        </div>
                    </template>

                    <!-- Domains Tab -->
                    <template #domains>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <DomainCard
                                v-for="domain in domains"
                                :key="domain.number"
                                :domain="domain"
                            />
                        </div>
                    </template>

                    <!-- Registries Tab -->
                    <template #registries>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <RegistryCard
                                v-for="vehicle in registries"
                                :key="vehicle.id"
                                :registry="vehicle"
                            />
                        </div>
                    </template>

                </TabBar>
            </div>
        </div>

    </EheeLayout>
</template>
