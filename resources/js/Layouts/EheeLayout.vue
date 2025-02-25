<script setup>
import {Link} from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar.vue";
import {usePage} from "@inertiajs/vue3";

const user = usePage().props.auth.user;
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="pl-20 h-16 bg-white flex justify-between items-center">
            <div class="text-green-600 font-bold text-2xl">
                <Link :href="route('welcome')">EHEE</Link>
            </div>
            <div class="pr-10">
                {{user.name}}
            </div>
        </div>
        <sidebar/>
        <slot/>
    </div>
</template>

<style scoped>

</style>
