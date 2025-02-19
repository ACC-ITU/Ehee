<script setup>
defineProps({
    domain: {
        type: Object,
        required: true
    }
});

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
            <h3 class="text-xl font-semibold text-green-600">{{ domain.domain_no }}</h3>
            <span
                class="px-3 py-1 rounded-full text-sm"
                :class="domain.active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
            >
        {{ domain.active ? 'Active' : 'Inactive' }}
      </span>
        </div>

        <div class="space-y-3">
            <div class="flex items-center text-gray-600">
                <span class="w-36 text-sm">Registered No:</span>
                <span class="font-medium">{{ domain.vehicle_registration_no ?? '-'}}</span>
            </div>
            <div class="flex items-center text-gray-600">
                <span class="w-36 text-sm">Issued At:</span>
                <span class="font-medium">{{ formatDate(domain.issued_at) ?? '-' }}</span>
            </div>
            <div class="flex items-center text-gray-600">
                <span class="w-36 text-sm">Expiry At:</span>
                <span class="font-medium">{{ formatDate(domain.expiry_at) ?? '-' }}</span>
            </div>
            <div class="flex items-center text-gray-600">
                <span class="w-36 text-sm">Base:</span>
                <span class="font-medium">{{ domain.base }}</span>
            </div>
        </div>
    </div>
</template>
