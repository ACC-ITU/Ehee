<script setup>
import OwnershipDrawer from "@/Pages/Vehicle/Partials/OwnershipDrawer.vue";
import {ref} from "vue";
import VehiclePopup from "@/Pages/Vehicle/Partials/VehiclePopup.vue";

const props = defineProps({
    registry: {
        type: Object,
        required: true
    },
    searchedOwner: {
        type: Object,
        required: true
    }
})

function empty(value) {
    if (value === undefined || value === null || value === '' || value.length === 0) {
        return null
    }
    return value
}

function formatDate(dateString) {
    const date = new Date(dateString.replace(" ", "T"));
    return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "numeric",
        minute: "numeric",
        second: "numeric",
        hour12: false
    })
}

const isDrawerOpen = ref(false);


function openOwnersDrawer() {
    isDrawerOpen.value = true;
}

function isTheOwner() {
    const owner = props.registry.owners.find(owner => props.searchedOwner.owner_id === owner.owner_id);
    return owner.status
}

const isPopupOpen = ref(false);
const selectedVehicle = ref(null)
function openVehiclePopup(vehicle) {
    isPopupOpen.value = true;
    selectedVehicle.value = vehicle;
}
</script>

<template>
    <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-green-600">{{ registry.registration_no }}</h3>
            <div class="space-x-4">
                <span
                    class="px-3 py-1 rounded-full text-sm"
                    :class="registry.active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                >
                {{ registry.active ? 'Active' : 'InActive' }}
              </span>
                <span v-if="searchedOwner"
                    class="px-3 py-1 rounded-full text-sm"
                    :class="isTheOwner() ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                >
            {{ isTheOwner() ? 'Owned' : 'Not Owned' }}
          </span>
            </div>

        </div>

        <div class="space-y-3">
            <div class="flex space-x-5 items-center text-gray-600">
                <span class="text-sm">Old Registration No:</span>
                <span class="font-medium">{{ empty(registry.old_registration_no) ?? '-' }}</span>
            </div>
            <div class="flex space-x-5 items-center text-gray-600">
                <span class="text-sm">Garage Registration No:</span>
                <span class="font-medium">{{ empty(registry.garage_registration_no) ?? '-' }}</span>
            </div>
            <div class="flex space-x-5 items-center text-gray-600">
                <span class="text-sm">Registered At:</span>
                <span
                    class="font-medium">{{ empty(registry.registration_at) ? formatDate(registry.registration_at) : '-' }}</span>
            </div>
            <div class="flex space-x-5 items-center text-gray-600">
                <span class="text-sm">Domains</span>
                <span class="font-medium flex space-x-2 "
                      :class="domain.active ? 'text-green-400' : 'text-red-300'"
                      v-for="domain in registry.domains" :key="domain">
                    {{ domain.domain_no }}
                </span>
            </div>
            <div class="flex space-x-5 items-center text-gray-600">
                <span class="text-sm">Owners</span>
                <span class="font-medium flex space-x-2  text-xs"
                    :class="owner.status ? 'text-green-400' : 'text-red-300'"
                    v-for="owner in registry.owners" :key="owner.owner_id">
                {{ owner.ownerDetails.full_name }}
                </span>
                <button @click="openOwnersDrawer()">
                    <i class='bx bx-expand' />
                </button>
            </div>
            <div class="flex space-x-5 items-center text-gray-600">
                <span class="text-sm">Vehicle</span>
                <span class="font-medium cursor-pointer" @click="openVehiclePopup(registry.vehicle)">{{ registry.vehicle?.description }}</span>
            </div>
        </div>
    </div>

    <OwnershipDrawer v-model="isDrawerOpen"  v-if="isDrawerOpen" :owners="registry.owners"/>
    <VehiclePopup v-model="isPopupOpen" :vehicle="selectedVehicle" />
</template>
