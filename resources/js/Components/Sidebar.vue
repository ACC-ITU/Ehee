<script setup>
import {router, useForm} from "@inertiajs/vue3";
import {ref} from "vue";

const isOpen = ref(false)

// Main buttons configuration
const mainButtons = [
    {icon: "bx bxs-car", action: () => router.visit(route('domains.index'))},
    {icon: "bx bx-credit-card-alt", action: () => router.visit(route('domains.index'))},
    {icon: "bx bx-water", action: () => router.visit(route('domains.index'))},
]

// Logout handler
const handleLogout = () => {
    console.log('Logout clicked')
    useForm().post(route('logout'));
}
</script>

<template>
    <div >
        <!-- Hamburger button -->
        <button
            @click="isOpen = !isOpen"
            class="fixed top-4 left-4 p-2 rounded-lg hover:bg-gray-100 transition-colors z-50 "
        >
            <svg
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />
            </svg>
        </button>

        <!-- Backdrop -->
        <Transition
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
            enter-active-class="transition-opacity duration-300"
            leave-active-class="transition-opacity duration-300"
        >
            <div
                v-if="isOpen"
                @click="isOpen = false"
                class="fixed inset-0 bg-black/20 z-40 "
            />
        </Transition>

        <!-- Sidebar -->
        <Transition
            enter-from-class="-translate-x-full"
            enter-to-class="translate-x-0"
            leave-from-class="translate-x-0"
            leave-to-class="-translate-x-full"
            enter-active-class="transition-transform duration-300 ease-in-out"
            leave-active-class="transition-transform duration-300 ease-in-out"
        >
            <div
                v-if="isOpen"
                class="fixed top-0 left-0 h-full w-20 bg-white shadow-lg z-50 flex flex-col items-center "
            >
                <!-- Main buttons container -->
                <div class="flex-1 w-full pt-16 flex flex-col items-center gap-4">
                    <button
                        v-for="(button, index) in mainButtons"
                        :key="index"
                        @click="button.action"
                        class="w-12 h-12 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors flex items-center justify-center text-gray-700 hover:text-gray-900"
                    >
                        <i :class="button.icon" class="text-xl" />
                    </button>
                </div>

                <!-- Logout button -->
                <div class="mb-8">
                    <button
                        @click="handleLogout"
                        class="w-12 h-12 rounded-lg bg-red-100 hover:bg-red-200 transition-colors flex items-center justify-center text-red-600 hover:text-red-700"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>
