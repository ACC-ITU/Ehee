<script setup>
defineProps({
    vehicle: {
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
</script>

<template>
    <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-green-600">{{ vehicle.registration_no }}</h3>
<!--            <span-->
<!--                class="px-3 py-1 rounded-full text-sm"-->
<!--                :class="vehicle.active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"-->
<!--            >-->
<!--            {{ registry.active ? 'Active' : 'InActive' }}-->
<!--          </span>-->
        </div>

        <div class="space-y-3">
            <div class="flex space-x-5 items-center text-gray-600">
                <span class="text-sm">Family:</span>
                <span class="font-medium">{{ empty(vehicle.family) ?? '-'}}</span>
            </div>

            <div class="flex space-x-5 items-center text-gray-600">
                <span class="text-sm">Description:</span>
                <span class="font-medium">{{ empty(vehicle.description) ?? '-'}}</span>
            </div>

            <div class="flex space-x-5 items-center text-gray-600">
                <span class="text-sm">Category:</span>
                <span class="font-medium">{{ empty(vehicle.category) ?? '-'}}</span>
            </div>

            <div class="flex space-x-5 items-center text-gray-600">
                <span class="text-sm">Imported At:</span>
                <span class="font-medium">{{ empty(vehicle.imported_date) ? formatDate(vehicle.imported_date) : '-'}}</span>
            </div>

            <div class="flex space-x-5 items-center text-gray-600">
                <span class="text-sm">Manufactured At:</span>
                <span class="font-medium">{{ empty(vehicle.manufactured_date) ? formatDate(vehicle.manufactured_date) : '-'}}</span>
            </div>

            <div class="flex space-x-5 items-center text-gray-600">
                <span class="text-sm">Model</span>
                <span class="font-medium">{{vehicle.model}}</span>
            </div>

            <div class="flex space-x-5 items-center text-gray-600">
                <span class="text-sm">Engine No</span>
                <span class="font-medium">{{vehicle.engine_no}}</span>
            </div>

            <div class="flex space-x-5 items-center text-gray-600">
                <span class="text-sm">Chassis No</span>
                <span class="font-medium">{{vehicle.chassis_no}}</span>
            </div>

            <div class="flex space-x-5 items-center text-gray-600">
                <span class="text-sm">Model Number</span>
                <span class="font-medium">{{vehicle.model_no}}</span>
            </div>

            <div class="flex space-x-5 items-center text-gray-600">
                <span class="text-sm">Tonnage</span>
                <span class="font-medium">{{vehicle.tonnage}}</span>
            </div>
        </div>
    </div>
</template>
