<script setup lang="ts">
import {ChevronLeftIcon, ChevronRightIcon} from "lucide-vue-next";


defineProps({
    pages: Array<number>,
    currentPage: Number,
    showEllipsis: Boolean,
})
const emit = defineEmits(['pageChange']);

const goToPage = (page: number) => {
    emit('pageChange', page);
}
</script>

<template>
    <div class="inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
        <button
            @click="goToPage(currentPage-1)"
            class="relative inline-flex items-center rounded-l-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20"
        >
            <span class="sr-only">Previous</span>
            <ChevronLeftIcon class="h-5 w-5"/>
        </button>
        <button
            v-for="page in pages"
            :key="page"
            :class="['relative inline-flex items-center border px-4 py-2 text-sm font-medium', {
                    'bg-green-600 text-white': page === currentPage,
                    'text-gray-500 hover:bg-gray-50': page !== currentPage
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
            @click="goToPage(currentPage+1)"
            class="relative inline-flex items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20"
        >
            <span class="sr-only">Next</span>
            <ChevronRightIcon class="h-5 w-5"/>
        </button>
    </div>
</template>

<style scoped>

</style>
