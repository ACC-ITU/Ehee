<script setup>
defineProps({
    owner: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-start space-x-6">
            <!-- Profile Image -->
            <div class="w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-green-200">
                <svg class="w-16 h-16 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>

            <!-- Profile Details -->
            <div class="flex-1">
                <h2 class="text-2xl font-semibold text-gray-800">{{ owner.full_name }}</h2>
                <div class="mt-4 space-y-2">
                    <div class="flex items-center text-gray-600">
                        <span class="w-32 text-sm">Identity Type:</span>
                        <span class="font-medium">{{ owner.identifier_type }}</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <span class="w-32 text-sm">Identity:</span>
                        <span class="font-medium">{{ owner.identifier }}</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <span class="w-32 text-sm">Address:</span>
                        <span class="font-medium">{{ owner.address }}</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <span class="w-32 text-sm">Mobile:</span>
                        <span class="font-medium">{{ owner.mobile_no }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
