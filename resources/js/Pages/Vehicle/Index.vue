<script setup>
import {computed, ref} from 'vue';
import {Link, router} from "@inertiajs/vue3";
import {
    EyeIcon,
    MoreHorizontalIcon,
    CarIcon,
    TruckIcon,
    BikeIcon,
    TractorIcon
} from 'lucide-vue-next';
import {lowerCase, startCase} from "lodash";
import EheeLayout from "@/Layouts/EheeLayout.vue";
import AdvanceSearch from "@/Pages/Vehicle/Partials/AdvanceSearch.vue";
import EmptyStateCard from "@/Pages/Vehicle/Partials/EmptyStateCard.vue";
import Pagination from "@/Pages/Vehicle/Partials/Pagination.vue";
import SearchResultInfo from "@/Pages/Vehicle/Partials/SearchResultInfo.vue";

defineOptions({
    layout: EheeLayout,
})

const props = defineProps({
    islands: Array,
    atolls: Array,
    registries: Object,
    currentOwner: Object,
    filters: {
        type: Object,
        default: () => ({})
    }
});

const advanceSearchRef = ref(null);
const errors = ref([]);

const handleSearch = (searchData) => {
    router.get(route('vehicles.index'), searchData, {
        only: ['registries'],
        preserveState: true,
        preserveScroll: true,
        onError: (error) => errors.value = error,
        onSuccess: () => console.log('Search completed'),
    });
};

const handleClear = () => {
    router.get(route('vehicles.index'), {}, {
        only: ['registries'],
        preserveState: true,
        preserveScroll: true,
    });
};

const goToPage = (page) => {
    console.log('goToPage', page);
    if (!props.registries || page < 1 || page > props.registries.lastPage) return;

    if (advanceSearchRef.value) {
        advanceSearchRef.value.applyFilters(page);
    }
};

const showEllipsis = computed(() => {
    if (!props.registries) return false;
    return pages.value[0] > 1 || pages.value[pages.value.length - 1] < props.registries.lastPage;
});

const pages = computed(() => {
    if (!props.registries) return [];

    const range = [];
    let start = Math.max(1, props.registries.currentPage - 2);
    let end = Math.min(props.registries.lastPage, props.registries.currentPage + 2);

    for (let i = start; i <= end; i++) {
        range.push(i);
    }

    return range;
});

const formatDate = (dateString) => {
    const options = {year: 'numeric', month: 'short', day: 'numeric'};
    return new Date(dateString).toLocaleDateString(undefined, options);
};

const getVehicleIcon = (description) => {
    const desc = description?.toLowerCase() || '';

    if (desc.includes('motor cycle') || desc.includes('electric bicycle') || desc.includes('battery scooter')) {
        return BikeIcon;
    }
    if (desc.includes('pick-up') || desc.includes('truck')) {
        return TruckIcon;
    }
    if (desc.includes('dumper')) {
        return TractorIcon;
    }
    return CarIcon;
};
</script>

<template>
    <div class="h-full bg-gray-50 p-4 md:p-8 ml-6">
        <div class="mx-auto max-w-7xl">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 md:text-3xl">Vehicle Registry Search</h1>
                <p class="text-sm text-gray-500">Search for vehicle registries using multiple filters</p>
            </div>

            <!-- Search filter component -->
            <AdvanceSearch
                ref="advanceSearchRef"
                :islands="islands"
                :atolls="atolls"
                :filters="filters"
                @search="handleSearch"
                @clear="handleClear"
            />


            <!-- Results Section -->
            <div class="rounded-lg border bg-white shadow-sm">
                <!-- Results Header -->
                <div class="border-b border-gray-200 px-6 py-4">
                    <div class="flex flex-col items-start justify-between sm:flex-row sm:items-center">
                        <h2 class="text-lg font-medium text-gray-900">Search Results</h2>
                        <p class="mt-1 text-sm text-gray-500 sm:mt-0">
                            {{ `${registries ? registries.total : 0} Registries found` }}
                        </p>
                    </div>
                </div>

                <!-- Empty State -->

                <EmptyStateCard
                    v-if="!registries || !registries.total"
                    :message="registries ?
                    'Try adjusting your search filters to find what you\'re looking for.'
                    : ' Please provide at least one search criteria.' "
                />


                <!-- Results Table -->
                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Reg. No
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Owner
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Vehicle
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Location
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="registry in registries.items" :key="registry.id" class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                {{ registry.registration_no }}
                                <div class="text-xs text-gray-500">{{ formatDate(registry.registration_at) }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ startCase(lowerCase(registry.owners[0].ownerDetails.full_name)) }}
                                <div class="text-xs">{{
                                        startCase(registry.owners[0].ownerDetails.identifier)
                                    }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <component
                                        :is="getVehicleIcon(registry.vehicle.description)"
                                        class="h-4 w-4"
                                    />
                                    <div>
                                        {{ registry.vehicle.model }}
                                        <div class="text-md block">{{ registry.vehicle.model_no }}</div>
                                    </div>

                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ registry.island.atoll.name }}. {{ registry.island.name }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span
                                    :class="[
                                      registry.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800',
                                      'bg-gray-100 text-gray-800',
                                      'inline-flex rounded-full px-2 py-1 text-xs font-medium'
                                    ]"
                                >
                                    {{ registry.active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                <div class="flex space-x-2">
                                    <Link :href="route('vehicles.show', registry.registration_no)"
                                          class="rounded-md p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-500"
                                    >
                                        <EyeIcon class="h-4 w-4"/>
                                    </Link>
                                    <button
                                        class="rounded-md p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-500">
                                        <MoreHorizontalIcon class="h-4 w-4"/>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-8">
                    <div class="w-full flex flex-col sm:flex-row space-y-6 sm:space-y-0 justify-between items-start sm:items-center" v-if="registries">
                        <SearchResultInfo
                            :from="registries.from"
                            :to="registries.to"
                            :total="registries.total"
                        />
                        <Pagination
                            :pages="pages"
                            :showEllipsis="showEllipsis"
                            :currentPage="registries.currentPage"
                            @pageChange="goToPage"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
:root {
    --primary: #00a009;
    --primary-foreground: #ffffff;
}

/* Fix for input styling */
input, select {
    border-width: 1px;
    border-color: #e5e7eb;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    line-height: 1.25rem;
}

input:focus, select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 1px var(--primary);
}
</style>
