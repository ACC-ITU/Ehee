<script setup>
import {ref, computed} from 'vue';
import {Link, router, useForm} from "@inertiajs/vue3";
import {
    SearchIcon,
    XIcon,
    UserIcon,
    HashIcon,
    FileTextIcon,
    CalendarIcon,
    MapPinIcon,
    MapIcon,
    ActivityIcon,
    EyeIcon,
    PencilIcon,
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

defineOptions({
    layout: EheeLayout,
})
const props = defineProps({
    islands: Array,
    atolls: Array,
    registries: Object,
    currentOwner: Object,
});

// Filter state
const isFiltersVisible = ref(false);

function toggleFiltersVisible() {
    isFiltersVisible.value = !isFiltersVisible.value;
}

const filteredIslands = computed(() => {
    form.island = "";
    let filtered = props.islands;
    if (form.atoll) {
        filtered = props.islands.filter(island => form.atoll === island.atoll.name)
            .filter((value, index, self) => self.findIndex(island => island.id === value.id) === index)
    }
    return filtered.sort((a, b) => a.id - b.id);
})

const form = useForm({
    registration_number: '',
    owner: '',
    registration_date: '',
    registration_date_operator: '=',
    atoll: '',
    island: '',
    domain: '',
    status: '',
    page: props.registries?.currentPage ?? 1
});

// Methods
const applyFilters = (page) => {
    form.page = page;
    const cleanedData = filterEmptyFields(form.data())

    router.get(route('vehicles.search'), cleanedData, {
        preserveState: true,
        onError: (error) => console.log(error),
        onSuccess: (data) => console.log(data),

    });
};

const filterEmptyFields = (data) => {
    return Object.keys(data).reduce((acc, key) => {
        const value = data[key]
        // Check for null, undefined, empty string
        if (value !== null && value !== undefined && value !== '') {
            // Handle nested objects
            if (typeof value === 'object' && value !== null) {
                const filteredNestedObject = filterEmptyFields(value)
                if (Object.keys(filteredNestedObject).length > 0) {
                    acc[key] = filteredNestedObject
                }
            } else {
                acc[key] = value
            }
        }
        return acc
    }, {})
}

const goToPage = (page) => {

    if (page > 0 && page <= props.registries.lastPage) {
        console.log(page);
        applyFilters(page);
    }
}

const showEllipsis = computed(() => {
    return pages.value[0] > 1 || pages.value[pages.value.length - 1] < props.registries.lastPage;
});

const pages = computed(() => {
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


const clearFilters = () => {
    form.reset();
}

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

            <!-- Filter Form -->
            <div class="mb-6 rounded-lg border bg-white p-6 shadow-sm">
                <form @submit.prevent="applyFilters(1)">

                    <!-- ID Search and Filter button -->
                    <div class="flex space-x-4 items-end justify-between">
                        <div class="w-full">
                            <label for="ownerId" class="block text-sm font-medium text-gray-700">Owner ID / Company
                                Registration No</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <UserIcon class="h-4 w-4 text-gray-400"/>
                                </div>
                                <input
                                    type="text"
                                    id="ownerId"
                                    v-model="form.owner"
                                    class="block w-full rounded-md border-gray-300 pl-10 focus:border-primary focus:ring-primary sm:text-sm h-12"
                                    placeholder="e.g. A123456"
                                />
                            </div>
                        </div>
                        <button
                            type="button"
                            @click="toggleFiltersVisible"
                            class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
                        >
                            <FileTextIcon class="mr-3 h-4 w-4"/>
                            Filters
                        </button>
                    </div>

                    <!-- Divider -->

                    <!-- Filters Form -->
                    <div class="w-full border-b border-gray-300 my-10" v-if="isFiltersVisible"></div>
                    <div
                        v-if="isFiltersVisible"
                        class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mt-5 transition-all duration-300"
                    >
                        <!-- Registration Number -->
                        <div class="space-y-2">
                            <label for="registration_number" class="block text-sm font-medium text-gray-700">Registration
                                Number</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 flex items-center pl-3">
                                    <HashIcon class="h-4 w-4 text-gray-400"/>
                                </div>
                                <input
                                    type="text"
                                    id="registration_number"
                                    v-model="form.registration_number"
                                    class="block w-full rounded-md border-gray-300 pl-10 focus:border-primary focus:ring-primary sm:text-sm"
                                    placeholder="e.g. A0A1234"
                                />
                            </div>
                        </div>

                        <!-- Domain Number -->
                        <div class="space-y-2">
                            <label for="domain" class="block text-sm font-medium text-gray-700">Domain
                                Number</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <FileTextIcon class="h-4 w-4 text-gray-400"/>
                                </div>
                                <input
                                    type="number"
                                    id="domain"
                                    v-model="form.domain"
                                    class="block w-full rounded-md border-gray-300 pl-10 focus:border-primary focus:ring-primary sm:text-sm"
                                    placeholder="e.g. 8008"
                                />
                            </div>
                        </div>

                        <!-- Registration Date Range -->
                        <div class="space-y-2 sm:col-span-2 lg:col-span-3">
                            <label class="block text-sm font-medium text-gray-700">Registration Date</label>
                            <div class="flex flex-col space-y-2 sm:flex-row sm:space-x-4 sm:space-y-0">
                                <div class="relative flex-1 rounded-md shadow-sm">
                                    <div
                                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <CalendarIcon class="h-4 w-4 text-gray-400"/>
                                    </div>
                                    <input
                                        type="date"
                                        v-model="form.registration_date"
                                        class="block w-full rounded-md border-gray-300 pl-10 focus:border-primary focus:ring-primary sm:text-sm"
                                    />
                                </div>
                                <div class="flex items-center justify-center">
                                    <span class="text-sm text-gray-500">Operator</span>
                                </div>
                                <div class="relative flex-1 rounded-md shadow-sm w-full lg:w-64">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <CalendarIcon class="h-4 w-4 text-gray-400"/>
                                    </div>
                                    <select
                                        id="operator"
                                        v-model="form.registration_date_operator"
                                        class="block w-full lg:w-64 rounded-md border-gray-300 pl-10 focus:border-primary focus:ring-primary sm:text-sm"
                                    >
                                        <option value="=">Equal</option>
                                        <option value="<"> Grater Than </option>
                                        <option value=">">Less Than</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Atoll -->
                        <div class="space-y-2">
                            <label for="atoll" class="block text-sm font-medium text-gray-700">Atoll</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <MapPinIcon class="h-4 w-4 text-gray-400"/>
                                </div>
                                <select
                                    id="atoll"
                                    v-model="form.atoll"
                                    class="block w-full rounded-md border-gray-300 pl-10 focus:border-primary focus:ring-primary sm:text-sm"
                                >
                                    <option value="">Select Atoll</option>
                                    <option v-for="atoll in atolls" :key="atoll.id" :value="atoll.name">
                                        {{ atoll.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Island -->
                        <div class="space-y-2">
                            <label for="island" class="block text-sm font-medium text-gray-700">Island</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <MapIcon class="h-4 w-4 text-gray-400"/>
                                </div>
                                <select
                                    id="island"
                                    v-model="form.island"
                                    class="block w-full rounded-md border-gray-300 pl-10 focus:border-primary focus:ring-primary sm:text-sm"
                                    :disabled="!form.atoll"
                                >
                                    <option value="">Select Island</option>
                                    <option v-for="island in filteredIslands" :key="island.id" :value="island.name">
                                        {{ island.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <ActivityIcon class="h-4 w-4 text-gray-400"/>
                                </div>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="block w-full rounded-md border-gray-300 pl-10 focus:border-primary focus:ring-primary sm:text-sm"
                                >
                                    <option value="">All Statuses</option>
                                    <option value="1">Active</option>
                                    <option value="0">InActive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex flex-col-reverse justify-end gap-3 sm:flex-row">
                        <button
                            v-if="isFiltersVisible"
                            type="button"
                            @click="clearFilters"
                            class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
                        >
                            <XIcon class="mr-2 h-4 w-4"/>
                            Clear Filters
                        </button>
                        <button
                            :disabled="form.processing"
                            type="submit"
                            class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
                        >
                            <SearchIcon class="mr-2 h-4 w-4"/>
                            Search
                        </button>
                    </div>

                </form>
            </div>

            <!-- Results Section -->
            <div class="rounded-lg border bg-white shadow-sm">
                <!-- Results Header -->
                <div class="border-b border-gray-200 px-6 py-4">
                    <div class="flex flex-col items-start justify-between sm:flex-row sm:items-center">
                        <h2 class="text-lg font-medium text-gray-900">Search Results</h2>
                        <p class="mt-1 text-sm text-gray-500 sm:mt-0">
                            {{ form.processing ? 'Searching...' : `${registries.total} Registries found` }}
                        </p>
                    </div>
                </div>
                <!-- Loading State -->
                <div v-if="form.processing" class="flex items-center justify-center p-12">
                    <div class="flex flex-col items-center">
                        <div class="h-12 w-12 animate-spin rounded-full border-b-2 border-t-2 border-primary"></div>
                        <p class="mt-4 text-sm text-gray-500">Searching for registries...</p>
                    </div>
                </div>

                <!-- Empty State -->

                <div v-else-if="!registries.items"
                     class="flex flex-col items-center justify-center p-12 text-center">
                    <div class="rounded-full bg-gray-100 p-3">
                        <SearchIcon class="h-6 w-6 text-gray-400"/>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No registries found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Try adjusting your search filters to find what you're looking for.
                    </p>
                </div>


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
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
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

.bg-primary {
    background-color: var(--primary);
}

.text-primary {
    color: var(--primary);
}

.border-primary {
    border-color: var(--primary);
}

.focus\:border-primary:focus {
    border-color: var(--primary);
}

.focus\:ring-primary:focus {
    --tw-ring-color: var(--primary);
}

.focus\:ring-offset-2:focus {
    --tw-ring-offset-width: 2px;
}

.bg-primary\/90 {
    background-color: rgba(8, 221, 11, 0.9);
}

.hover\:bg-primary\/90:hover {
    background-color: rgba(49, 184, 4, 0.9);
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
