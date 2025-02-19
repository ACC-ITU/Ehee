<script setup>
import {ref} from "vue";
import Sidebar from "@/Components/Sidebar.vue";

const mobileNavOpen = ref(false);
</script>

<template>
    <sidebar />
    <slot />
</template>

<style scoped>

</style>
