<script setup>
import {ref, watch, computed} from 'vue';
import {lowerCase, startCase} from "lodash";

const props = defineProps({
    modelValue: Boolean,
    owners: Array,
});
const emit = defineEmits(['update:modelValue']);
const isOpen = ref(props.modelValue);

watch(() => props.modelValue, (newValue) => {
    isOpen.value = newValue;
});

const closeDrawer = () => {
    emit('update:modelValue', false);
};

const currentOwner = computed(() => {
    return props.owners.find(owner => owner.status);
})

const previousOwners = computed(() => {
    return props.owners.filter(owner => owner.owner_id !== currentOwner.value.owner_id);
})

function formatDate(dateString) {
    const date = new Date(dateString.replace(" ", "T"));
    return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
    })
}
</script>

<template>
    <div>
        <div
            class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
            v-if="isOpen"
            @click="closeDrawer"
        ></div>

        <div
            class="fixed top-0 right-0 w-full lg:w-1/3 h-full p-4 bg-white shadow-lg transform drawer"
            :class="isOpen ? 'translate-x-0' : 'translate-x-full'"
        >
            <!-- Header -->
            <div class="p-4 border-b border-gray-200 mt-10 lg:mt-0">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Vehicle Ownership History</h2>
                    <button
                        @click="closeDrawer"
                        class="text-gray-500 hover:text-gray-700 focus:outline-none"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="p-4 overflow-y-auto">
                <!-- Current Owner -->
                <div class="mb-6" v-if="currentOwner">
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Current Owner</h3>
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-medium text-gray-900">{{ startCase(lowerCase(currentOwner.ownerDetails.full_name)) }} ({{startCase(currentOwner.ownerDetails.identifier)}})</h4>
                                <p class="text-sm text-gray-600">Owner since: {{ formatDate(currentOwner.issued_at) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Previous Owners -->
                <div v-if="previousOwners.length">
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Previous Owners</h3>
                    <div class="space-y-4">
                        <div
                            v-for="owner in previousOwners"
                            :key="owner.owner_id"
                            class="bg-gray-50 p-4 rounded-lg border border-gray-200"
                        >
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">{{ startCase(lowerCase(owner.ownerDetails.full_name)) }} ({{startCase(owner.ownerDetails.identifier)}})</h4>
                                    <p class="text-sm text-gray-600">
                                        Owned: {{ formatDate(owner.issued_at) }} - {{ formatDate(owner.expired_at) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No owners message -->
                <div v-if="!owners.length" class="text-center text-gray-500 py-8">
                    No ownership history available
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.drawer {
    will-change: transform;
    transition: transform 0.3s ease-in-out;
}
</style>
