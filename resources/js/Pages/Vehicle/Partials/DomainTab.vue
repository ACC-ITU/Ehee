<script setup>
import DomainCard from "@/Pages/Vehicle/Partials/DomainCard.vue";
import EmptyCard from "@/Components/EmptyCard.vue";

const props = defineProps({
    domains: Array,
})
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" v-if="domains.length">
        <DomainCard
            v-for="domain in domains"
            :key="domain.number"
            :domain="domain"
        />
    </div>
    <div v-else class="">
        <EmptyCard />
    </div>
</template>
