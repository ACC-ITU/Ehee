<script setup>
import EmptyCard from "@/Components/EmptyCard.vue";
import RegistryCard from "@/Pages/Vehicle/Partials/RegistryCard.vue";

const props = defineProps({
    registries: Array,
    currentOwner: Object,
})
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" v-if="registries.length">
        <RegistryCard
            v-for="registry in registries"
            :key="registry.id"
            :registry="registry"
            :searchedOwner="currentOwner"
        />
    </div>
    <div v-else class="">
        <EmptyCard />
    </div>
</template>
