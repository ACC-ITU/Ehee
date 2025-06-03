<script setup>
import {computed, ref} from "vue";
import { useForm} from "@inertiajs/vue3";
import {
    ActivityIcon,
    CalendarIcon,
    FileTextIcon,
    HashIcon,
    MapIcon, MapPinIcon,
    SearchIcon,
    UserIcon,
    XIcon
} from "lucide-vue-next";

const props = defineProps({
    islands: Array,
    atolls: Array,
    pages: Array,
    current_page: 1
})
const emit = defineEmits(["update:modelValue"]);

const processing = computed({
    get: () => form.processing,
    set: (value) => emit("update:modelValue", value),
})


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
    page: props.current_page
});

const errors = ref([]);
const applyFilters = (page) => {
    form.page = page;
    const cleanedData = filterEmptyFields(form.data())

    form.get(route('vehicles.index', cleanedData), {
        only: ['registries'],
        preserveState: true,
        preserveScroll: true,
        onError: (error) => errors.value = error,
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

const clearFilters = () => {
    form.reset();
}

const cannotSubmitForm = () => {
    return !form.owner.length
        && !form.atoll
        && !form.island
        && !form.registration_number
        && !form.domain
        && !form.registration_date
}

</script>

<template>
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
                    <p v-if="form.errors.owner" class="text-red-600">{{ form.errors.owner }}</p>
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
                                <option value="<"> Grater Than</option>
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
                    :disabled="form.processing || cannotSubmitForm()"
                    type="submit"
                    class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white shadow-sm
                    hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:bg-gray-400
                    hover:disabled:bg-gray-400 disabled:cursor-not-allowed"
                >
                    <SearchIcon class="mr-2 h-4 w-4"/>
                    Search
                </button>
            </div>

        </form>
    </div>
</template>

<style scoped>

</style>
