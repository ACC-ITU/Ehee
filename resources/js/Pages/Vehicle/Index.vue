<script setup>
import {ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import DomainCard from "@/Pages/Vehicle/DomainCard.vue";

const props = defineProps({
    domains: Object,
})

const query = ref('');
const hasResults = ref(false);
const filter = ref('owner');


// Methods
const handleSearch = async () => {
    if (!query.value) return

    const params = {
        [filter.value]: query.value,
    }

    router.get(route('domains.index'), params, {
        preserveState: true,
        onSuccess: (data) => console.log(data),
        onError: (err) => console.log(err),
    });
    hasResults.value = true;
}
</script>

<template>
    <form @submit.prevent="handleSearch">
        <div class="flex flex-col items-center justify-center h-screen p-4 transition-all"
             :class="{ 'pt-6 h-auto': hasResults }">
            <div class="flex space-x-5 w-full max-w-xl transition-all duration-300 "
                 :class="hasResults ? '-translate-y-1/2' : ''">
                <input
                    v-model="query"
                    type="text"
                    placeholder="Search..."
                    @focus="hasResults = true"
                    class="w-full px-4 py-3 text-lg border rounded-lg shadow-md outline-none transition-all duration-300 focus:outline-none"
                />
                <select v-model="filter">
                    <option value="owner" >Owner</option>
                    <option value="vehicle">Vehicle</option>
                </select>
            </div>
            <div v-if="hasResults && domains"
                 class="w-3/4 mt-4 bg-white shadow-lg rounded-lg p-4 max-w-xl opacity-100 transition-all duration-800">

                <div v-for="(domain, index) in domains" :key="index" class="py-2 border-b last:border-none">
                    <DomainCard :domain="domain"/>
                </div>

            </div>
        </div>
    </form>
</template>
