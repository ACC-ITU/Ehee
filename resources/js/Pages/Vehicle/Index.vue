<script setup>
import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import {DotLottieVue} from '@lottiefiles/dotlottie-vue'
import EheeLayout from "@/Layouts/EheeLayout.vue";
import AdvanceSearch from "@/Pages/Vehicle/Partials/AdvanceSearch.vue";

const props = defineProps({
    errors: Object,
})

const params = route().params;
const search = ref('');
const filter = ref('owner');
const isSearching = ref(false);
const searchOptions = [
    {value: 'owner', label: 'Owner'},
    // {value: 'vehicle', label: 'Vehicle'},
];


// Methods
const handleSearch = async () => {
    if (!search.value) return

    isSearching.value = true;

    const params = {
        [filter.value]: search.value,
    }

    router.get(route('vehicles.index'), params, {
        preserveState: true,
        onSuccess: (data) => console.log(data),
        onError: (err) => console.log(err),
        onFinish: () => isSearching.value = false,
    });
}

const advanceSearch = ref(false);

</script>

<template>
    <EheeLayout>
        <div class="flex flex-col w-full h-full pt-20">
            <div class="text-center w-full">

                <DotLottieVue style="height: 100px; width: 100px" autoplay loop src="/images/car_pulse_animation.lottie"
                              class="mx-auto"/>

                <h1 class="text-5xl font-medium tracking-tight text-green-800 mb-3">
                    Ulhandhu Search
                </h1>

                <p class="text-l text-gray-700 max-w-3xl mx-auto font-light">
                    Search vehicles, domains registered at the Ministry of Transport and Civil Aviation
                </p>

                <div class="mt-6 flex justify-center">
                    <div class="h-px w-full bg-gradient-to-r from-transparent via-green-400 to-transparent"/>
                </div>

            </div>

            <form @submit.prevent="handleSearch">
                <div class="flex flex-col items-center mt-10 p-4 ">
                    <div class="flex space-x-5 w-full max-w-2xl">
                        <div class="w-48">
                        <Select
                            v-model="filter"
                            :options="searchOptions"
                            placeholder="Search By"
                            :disabled="isSearching"
                        />
                        </div>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search by NID..."
                            class="w-full px-4 py-3 text-lg bg-white border-gray-50 rounded-lg
                            focus:ring-1 focus:ring-green-200 border hover:border-gray-300 focus:border-green-500
                            outline-none transition-all duration-300"
                        />

                        <button class=" flex items-center justify-center rounded-lg bg-white px-3
                        focus:ring-1 focus:ring-green-200 border hover:border-gray-300 text-gray-500 hover:text-gray-400"
                                :disabled=isSearching
                                type="submit">
                            <i class='bx bx-search text-4xl w-10 h-10'></i>
                        </button>

                    </div>
                    <p class="text-red-600 text-sm mt-10" v-for="key in Object.keys(errors)">{{ errors[key] }}</p>
                </div>
            </form>
            <button type="button" class="flex items-center justify-center rounded-lg bg-white px-3 w-10 mx-auto h-10
                        focus:ring-1 focus:ring-green-200 border hover:border-gray-300 text-gray-500 hover:text-gray-400"
                    @click="advanceSearch=true"
                    :disabled=isSearching>
                <i class='bx bx-expand-horizontal'></i>
            </button>
        </div>

        <AdvanceSearch v-model="advanceSearch" v-if="advanceSearch"/>
    </EheeLayout>
</template>
