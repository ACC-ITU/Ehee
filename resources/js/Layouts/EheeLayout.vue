<script setup>
import {ref} from "vue";
import Sidebar from "@/Components/Sidebar.vue";

const mobileNavOpen = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <sidebar/>
        <slot/>
    </div>
</template>

<style scoped>

</style>
