<script setup>
import {computed} from 'vue';
import {Link} from "@inertiajs/vue3";
import {
    SearchIcon,
    EyeIcon,
    MoreHorizontalIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    CarIcon,
    TruckIcon,
    BikeIcon,
    TractorIcon
} from 'lucide-vue-next';
import {lowerCase, startCase} from "lodash";
import EheeLayout from "@/Layouts/EheeLayout.vue";
import AdvanceSearch from "@/Pages/Vehicle/Partials/AdvanceSearch.vue";

defineOptions({
    layout: EheeLayout,
})
const props = defineProps({
    islands: Array,
    atolls: Array,
    registries: Object,
    currentOwner: Object,
});

const goToPage = (page) => {
    if(!props.registries) return;

    if (page > 0 && page <= props.registries.lastPage) {
        console.log(page);
        // applyFilters(page);
    }
}

const showEllipsis = computed(() => {
    if(!props.registries) return false;
    return pages.value[0] > 1 || pages.value[pages.value.length - 1] < props.registries.lastPage;
});

const pages = computed(() => {
    if(!props.registries) return [];
    const range = [];
    let start = props.registries.currentPage - 2;
    let end = props.registries.currentPage + 2;

    if (start < 1) start = 1;
    if (end > props.registries.lastPage) end = props.registries.lastPage;

    for (let i = start; i <= end; i++) {
        range.push(i);
    }

    return range;
});



const formatDate = (dateString) => {
    const options = {year: 'numeric', month: 'short', day: 'numeric'};
    return new Date(dateString).toLocaleDateString(undefined, options);
}
</script>

<template>
    <div class="h-full bg-gray-50 p-4 md:p-8">
        <div class="mx-auto max-w-7xl">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 md:text-3xl">Vehicle Registry Search</h1>
                <p class="text-sm text-gray-500">Search for vehicle registries using multiple filters</p>
            </div>

            <!-- Search filter component -->
            <AdvanceSearch :islands="islands" :atolls="atolls" :pages="pages" :current_page="registries?.currentPage ?? 1"/>


            <!-- Results Section -->
            <div class="rounded-lg border bg-white shadow-sm">
                <!-- Results Header -->
                <div class="border-b border-gray-200 px-6 py-4">
                    <div class="flex flex-col items-start justify-between sm:flex-row sm:items-center">
                        <h2 class="text-lg font-medium text-gray-900">Search Results</h2>
                        <p class="mt-1 text-sm text-gray-500 sm:mt-0">
                            {{`${registries ? registries.total : 0} Registries found` }}
                        </p>
                    </div>
                </div>

                <!-- Empty State -->

                <div v-if="!registries || !registries.total"
                     class="flex flex-col items-center justify-center p-12 text-center">
                    <div class="rounded-full bg-gray-100 p-3">
                        <SearchIcon class="h-6 w-6 text-gray-400"/>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No registries found</h3>

                    <p class="mt-1 text-sm text-red-500" v-if="!registries">
                        Please provide at least one search criteria.
                    </p>

                    <p class="mt-1 text-sm text-gray-500" v-else>
                        Try adjusting your search filters to find what you're looking for.
                    </p>
                </div>


                <!-- Results Table -->
                <div v-else class="overflow-x-auto" >
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
                                    <span
                                        class="mr-2 flex h-7 w-7 items-center justify-center rounded-full bg-gray-100 text-gray-500">
                                      <CarIcon v-if="registry.vehicle.description === 'Car'" class="h-4 w-4"/>
                                      <BikeIcon v-else-if="registry.vehicle.description === 'MOTOR CYCLE' ||
                                                  registry.vehicle.description === 'ELECTRIC BICYCLE' ||
                                                  registry.vehicle.description === 'BATTERY SCOOTER' "
                                                class="h-4 w-4"/>
                                      <TruckIcon
                                          v-else-if="registry.vehicle.description === 'PICK-UP' || registry.vehicle.description === 'Truck'"
                                          class="h-4 w-4"/>
                                      <TractorIcon v-else-if="registry.vehicle.description === 'DUMPER'" class="h-4 w-4"/>
                                      <CarIcon v-else class="h-4 w-4"/>
                                    </span>
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
                <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                    <div class="flex flex-1 justify-between sm:hidden">
                        <button
                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Previous
                        </button>
                        <button
                            class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Next
                        </button>
                    </div>
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between" v-if="registries">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing <span class="font-medium">{{ registries.from }}</span> to <span
                                class="font-medium">{{ registries.to }}</span> of
                                <span class="font-medium">{{ registries.total }}</span> results
                            </p>
                        </div>
                        <div>
                            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm"
                                 aria-label="Pagination">
                                <button
                                    @click="goToPage(registries.currentPage-1)"
                                    class="relative inline-flex items-center rounded-l-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20"
                                >
                                    <span class="sr-only">Previous</span>
                                    <ChevronLeftIcon class="h-5 w-5"/>
                                </button>
                                <button
                                    v-for="page in pages"
                                    :key="page"
                                    :class="['relative inline-flex items-center border px-4 py-2 text-sm font-medium', {
                                        'bg-primary text-white': page === registries.currentPage,
                                        'text-gray-500 hover:bg-gray-50': page !== registries.currentPage
                                    }]"
                                    @click="goToPage(page)"
                                >
                                    {{ page }}
                                </button>
                                <span v-if="showEllipsis"
                                      class="relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700"
                                >
                                  ...
                                </span>
                                <button
                                    @click="goToPage(registries.currentPage+1)"
                                    class="relative inline-flex items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20"
                                >
                                    <span class="sr-only">Next</span>
                                    <ChevronRightIcon class="h-5 w-5"/>
                                </button>
                            </nav>
                        </div>
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
