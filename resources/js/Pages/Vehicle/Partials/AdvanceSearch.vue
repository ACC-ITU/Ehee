<script setup>
import {computed, watch} from "vue";
import {useForm} from "@inertiajs/vue3";
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
    filters: {
        type: Object,
        default: () => ({})
    }
})

const emit = defineEmits(['search', 'clear']);

// Filter state

const form = useForm({
    registration_number: props.filters.registration_number || '',
    owner: props.filters.owner || '',
    registration_date: props.filters.registration_date || '',
    registration_date_operator: props.filters.registration_date_operator || '=',
    atoll: props.filters.atoll || '',
    island: props.filters.island || '',
    domain: props.filters.domain || '',
    status: props.filters.status || '',
    page: props.filters.page || 1
});

const filteredIslands = computed(() => {
    if (!form.atoll) {
        form.island = "";
        return [];
    }

    return props.islands
        .filter(island => form.atoll === island.atoll.name)
        .filter((value, index, self) => self.findIndex(island => island.id === value.id) === index)
        .sort((a, b) => a.id - b.id);
});

// Reset island when atoll changes
watch(() => form.atoll, () => {
    form.island = "";
});

const toggleFiltersVisible = () => {
    isFiltersVisible.value = !isFiltersVisible.value;
}

const filterEmptyFields = (data) => {
    return Object.keys(data).reduce((acc, key) => {
        const value = data[key];
        if (value !== null && value !== undefined && value !== '') {
            if (typeof value === 'object' && value !== null) {
                const filteredNestedObject = filterEmptyFields(value);
                if (Object.keys(filteredNestedObject).length > 0) {
                    acc[key] = filteredNestedObject;
                }
            } else {
                acc[key] = value;
            }
        }
        return acc;
    }, {});
}

const applyFilters = (page = 1) => {
    form.page = page;
    console.log(form);
    const cleanedData = filterEmptyFields(form.data());
    emit('search', cleanedData);
};

const clearFilters = () => {
    form.reset();
    emit('clear');
}

const cannotSubmitForm = computed(() => {
    return !form.owner.length
        && !form.atoll
        && !form.island
        && !form.registration_number
        && !form.domain
        && !form.registration_date;
});

// Expose applyFilters for parent component
defineExpose({
    applyFilters
});
</script>

<template>
    <!-- Filter Form -->
    <div class="mb-6 rounded-lg border bg-white p-6 shadow-sm">
        <form @submit.prevent="applyFilters(1)">

            <!-- ID Search and Filter button -->
            <div class="flex space-x-4 items-end justify-between">
                <div class="w-full">
                    <label for="ownerId" class="block text-sm font-medium text-gray-700">
                        Owner ID / Company Registration No
                    </label>
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
                <div class="flex space-x-2 h-12">
                    <button
                        type="button"
                        @click="clearFilters"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
                    >
                        <XIcon class="mr-2 h-4 w-4"/>
                        <span class="hidde md:block">Clear</span>
                    </button>
                    <button
                        :disabled="form.processing || cannotSubmitForm"
                        type="submit"
                        class="inline-flex items-center justify-center rounded-md px-4 py-2 text-sm font-medium bg-green-700 hover:bg-green-600 text-white disabled:bg-gray-300 disabled:cursor-not-allowed"
                    >
                        <SearchIcon class="mr-2 h-4 w-4"/>
                        Search
                    </button>
                </div>
            </div>


            <!-- Divider -->

            <!-- Filters Form -->
            <div
                class="grid grid-cols-2 gap-2 md:grid-cols-3 lg:grid-cols-4 mt-5 transition-all duration-300"
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
                        Number
                    </label>
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
                <div class="space-y-2 ">
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
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="operator" class="block text-sm font-medium text-gray-700">Date Operator</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <CalendarIcon class="h-4 w-4 text-gray-400"/>
                        </div>
                        <select
                            id="operator"
                            v-model="form.registration_date_operator"
                            class="block w-full rounded-md border-gray-300 pl-10 focus:border-primary focus:ring-primary sm:text-sm"
                        >
                            <option value="=">Equal</option>
                            <option value="<"> Grater Than</option>
                            <option value=">">Less Than</option>
                        </select>
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

        </form>
    </div>
</template>

<style scoped>

</style>
