<script setup lang="ts">
import {SearchIcon} from "lucide-vue-next";

defineProps({
    message: {
        type: String,
        required: true,
    }
})
</script>

<template>
    <div class="flex flex-col items-center justify-center p-12 text-center">
        <div class="rounded-full bg-gray-100 p-3">
            <SearchIcon class="h-6 w-6 text-gray-400"/>
        </div>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No registries found</h3>

        <p class="mt-1 text-sm text-gray-500" >
            {{message}}

        </p>
    </div>
</template>

<style scoped>

</style>
