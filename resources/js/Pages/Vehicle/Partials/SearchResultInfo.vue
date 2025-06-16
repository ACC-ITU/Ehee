<script setup lang="ts">
defineProps({
    from: Number,
    to: Number,
    total: Number,
})
</script>

<template>
    <div>
        <p class="text-sm text-gray-700">
            Showing <span class="font-medium">{{ from }}</span> to <span
            class="font-medium">{{ to }}</span> of
            <span class="font-medium">{{ total }}</span> results
        </p>
    </div>
</template>

<style scoped>

</style>
