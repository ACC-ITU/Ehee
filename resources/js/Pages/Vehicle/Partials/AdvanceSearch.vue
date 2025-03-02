<script setup>
import {computed} from "vue";
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    }
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
})

function handleSearch() {
    form.get(route('vehicles.search'));
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
                class="fixed top-0 right-0 h-full w-full md:w-1/2 xl:w-1/3 bg-gray-100 shadow-lg z-50 flex flex-col items-center "
            >
                <!-- Main buttons container -->
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

                        <button class="text-green-100 bg-green-800 hover:bg-green-700 px-5 py-2 rounded-lg mt-10">Submit</button>
                    </form>

                </div>

                <!-- Logout button -->
            </div>
        </Transition>
    </div>
</template>

<style scoped>

</style>
