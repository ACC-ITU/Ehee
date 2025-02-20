<script setup>
import {Head, Link} from '@inertiajs/vue3';
import { DotLottieVue } from '@lottiefiles/dotlottie-vue'


</script>

<template>
    <Head title="Welcome"/>
    <div>
        <div class="flex flex-col items-center justify-center text-center">
            <div class="">
                <DotLottieVue style="height: 300px; width: 300px" autoplay loop src="/images/car_animation.lottie" />
            </div>

            <h1 class="text-7xl font-bold text-green-700 mb-4">EHEE</h1>
            <p class="text-lg text-gray-600 mb-4">System for querying vehicle registry information from MTA for the Anti-Corruption Commission</p>
            <p class="text-md text-gray-500 max-w-lg mb-6">Ehee helps investigators analyze vehicle registrations, ownership history, and associated domains efficiently and accurately.</p>
            <Link :href="route('domains.index')" class="px-6 py-3 bg-green-700 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition">Start Search</Link>
        </div>

    </div>
</template>

