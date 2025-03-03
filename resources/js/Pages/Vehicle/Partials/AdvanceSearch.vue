<script setup>
import {computed} from "vue";
import {useForm} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    islands: Array,
    atolls: Array,
})
const emit = defineEmits(["update:modelValue"]);

const visible = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
})

function closeModal() {
    emit("update:modelValue", false);
}


const form = useForm({
    owner: '',
    registration_date: null,
    registration_number: '',
    atoll: '',
    island: ''
})


const filteredIslands = computed(() => {
    let filtered = props.islands;
    if (form.atoll) {
        filtered = props.islands.filter(island => form.atoll === island.atoll.name)
            .filter((value, index, self) => self.findIndex(island => island.id === value.id) === index)
    }
    return filtered.sort((a, b) => a.id - b.id);
})

function handleSearch() {
    form.get(route('vehicles.search'));
}

function resetFilters() {
    form.reset()
}
</script>

<template>
    <div>
        <!-- Backdrop -->
        <Transition
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
            enter-active-class="transition-opacity duration-300"
            leave-active-class="transition-opacity duration-300"
        >
            <div
                v-if="visible"
                @click="closeModal"
                class="fixed inset-0 bg-black/20 z-40 "
            />
        </Transition>

        <!-- Sidebar -->
        <Transition
            enter-from-class="-translate-x-full"
            enter-to-class="translate-x-0"
            leave-from-class="translate-x-0"
            leave-to-class="-translate-x-full"
            enter-active-class="transition-transform duration-300 ease-in-out"
            leave-active-class="transition-transform duration-300 ease-in-out"
        >
            <div
                v-if="visible"
                class="fixed top-0 right-0 h-full w-full md:w-1/2 xl:w-1/3 bg-gray-100 shadow-lg z-50 flex flex-col items-center overflow-y-auto"
            >
                <!-- Main Container -->
                <div class="w-full mt-10 px-5 flex flex-col items-start gap-4">
                    <div class="flex justify-between items-center w-full">
                        <h1 class="font-medium text-2xl ">Advance Search</h1>
                        <button class="" @click.prevent="closeModal">X</button>
                    </div>
                    <form class="mt-5" @submit.prevent="handleSearch">

                        <!-- Owner -->
                        <div class="flex flex-col items-start justify-between space-y-1">
                            <div class="flex flex-col items-start justify-start gap-2">
                                <label for="owner"
                                       class="text-nowrap font-semibold text-gray-700">Identifier</label>
                                <input
                                    id="owner"
                                    v-model="form.owner"
                                    type="text"
                                    placeholder="ID/Reg number"
                                    class="bg-white border-0 rounded-lg outline-none focus:ring-gray-400"
                                />
                            </div>
                            <p class="text-xs text-gray-500 leading-loose ">For individuals use National ID Number, and
                                for companies use Registration Number</p>
                        </div>

                        <!-- Registration Number -->
                        <div class="flex flex-col items-start justify-between space-y-1">
                            <div class="flex flex-col items-start justify-start gap-2">
                                <label for="registration_number"
                                       class="text-nowrap font-semibold text-gray-700">Registration No</label>
                                <input
                                    id="registration_number"
                                    v-model="form.registration_number"
                                    type="text"
                                    placeholder="Registration No"
                                    class="bg-white border-0 rounded-lg outline-none focus:ring-gray-400"
                                />
                            </div>
                        </div>

                        <!-- Registration Date -->
                        <div class="flex flex-col w-full items-start justify-between space-y-1 mt-5">
                            <div class="flex flex-col items-start justify-start gap-2">
                                <label for="registration_date" class="w-64 text-nowrap font-semibold text-gray-700">Registration
                                    Date</label>
                                <input
                                    id="registration_date"
                                    v-model="form.registration_date"
                                    type="date"
                                    class="w-full bg-white border-0 rounded-lg outline-none focus:ring-gray-400"
                                />
                            </div>
                        </div>

                        <!-- Atoll -->
                        <div class="flex flex-col w-full items-start justify-between space-y-1 mt-5">
                            <div class="flex flex-col items-start justify-start gap-2">
                                <label for="registration_date" class="w-64 text-nowrap font-semibold text-gray-700">Atoll</label>
                                <Select v-model="form.atoll" :options="atolls" optionLabel="name" optionValue="name"
                                        placeholder="Atoll"/>
                            </div>
                        </div>

                        <!-- Island -->
                        <div class="flex flex-col w-full items-start justify-between space-y-1 mt-5">
                            <div class="flex flex-col items-start justify-start gap-2">
                                <label for="registration_date" class="w-64 text-nowrap font-semibold text-gray-700">Island</label>
                                <Select v-model="form.island" :options="filteredIslands" optionLabel="name"
                                        optionValue="name" placeholder="Island"/>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="flex justify-start items-center space-x-10">
                            <button type="button"
                                    @click="resetFilters"
                                    class="text-gray-800 bg-gray-400 hover:bg-gray-500 px-5 py-2 rounded-lg mt-10">
                                <i class="bx bx-reset"/>
                            </button>
                            <button type="submit"

                                    class="text-green-100 bg-green-800 hover:bg-green-700 px-5 py-2 rounded-lg mt-10">
                                Submit
                            </button>
                        </div>
                    </form>

                </div>

                <!-- Logout button -->
            </div>
        </Transition>
    </div>
</template>

<style scoped>

</style>
