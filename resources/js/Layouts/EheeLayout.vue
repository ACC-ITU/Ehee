<script setup>
import {ref} from "vue";

const mobileNavOpen = ref(false);
</script>

<template>
    <nav class="py-6">
        <div class="container mx-auto px-4">
            <div class="relative flex items-center justify-between"><a class="inline-block" href="#"><img class="h-8" src="images/logo-white.svg" alt=""></a>
                <ul class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 hidden md:flex">
                    <li class="mr-8"><a class="inline-block text-white hover:text-lime-500 font-medium" href="about.html">About us</a></li>
                    <li class="mr-8"><a class="inline-block text-white hover:text-lime-500 font-medium" href="pricing.html">Pricing</a></li>
                    <li class="mr-8"><a class="inline-block text-white hover:text-lime-500 font-medium" href="contact.html">Contact us</a></li>
                    <li><a class="inline-block text-white hover:text-lime-500 font-medium" href="blog.html">Blog</a></li>
                </ul>
                <div class="flex items-center justify-end">
                    <div class="hidden md:block"><a class="inline-flex group py-2.5 px-4 items-center justify-center text-sm font-medium text-white hover:text-teal-900 border border-white hover:bg-white rounded-full transition duration-200" href="contact.html"><span class="mr-2">Get in touch</span>                      <span class="transform group-hover:translate-x-0.5 transition-transform duration-200">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M4.75 10H15.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          <path d="M10 4.75L15.25 10L10 15.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg></span></a></div>
                    <button class="md:hidden text-white hover:text-lime-500" @click="mobileNavOpen = !mobileNavOpen">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.19995 23.2H26.7999" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M5.19995 16H26.7999" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M5.19995 8.79999H26.7999" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="relative pt-18 pb-24 sm:pb-32 lg:pt-36 lg:pb-62">
        <div class="container mx-auto px-4 relative">
            <div class="max-w-lg xl:max-w-xl mx-auto text-center">
                <h1 class="font-heading text-5xl xs:text-7xl xl:text-8xl tracking-tight text-white mb-8">Energizing a Green Future</h1>
                <p class="max-w-md xl:max-w-none text-lg text-white opacity-80 mb-10">Our commitment to green energy is paving the way for a cleaner, healthier planet. Join us on a journey towards a future where clean, renewable energy sources transform the way we power our lives.</p><a class="inline-flex py-4 px-6 items-center justify-center text-lg font-medium text-teal-900 border border-lime-500 hover:border-white bg-lime-500 hover:bg-white rounded-full transition duration-200" href="#">See our solutions</a>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
