<script setup>
import {Head, Link} from '@inertiajs/vue3';

</script>

<template>
    <Head title="Welcome"/>
    <div>
        <div class="flex flex-col items-center justify-center h-screen text-center">
            <div class="mb-6">
                <i class='bx bxs-car-crash text-9xl text-green-600'></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Ehee</h1>
            <p class="text-lg text-gray-600 mb-4">System for querying vehicle registry information from MTA for the Anti-Corruption Commission</p>
            <p class="text-md text-gray-500 max-w-lg mb-6">Ehee helps investigators analyze vehicle registrations, ownership history, and associated domains efficiently and accurately.</p>
            <Link :href="route('domains.index')" class="px-6 py-3 bg-green-700 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition">Start Search</Link>
        </div>
    </div>
</template>

