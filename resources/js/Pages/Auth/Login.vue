<script setup>
import {Head, router} from "@inertiajs/vue3";
import microsoft_logo from '@/../images/micosoft-logo.svg'

const  loginWithMicrosoft = () => {
    router.get(route('auth.microsoft.redirect'))
}
</script>

<template>
    <Head title="Log in"/>

    <div class="min-h-screen bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center p-6">
        <div class="w-full max-w-md">
            <!-- Card Container -->
            <div class="bg-white rounded-2xl shadow-xl p-8 space-y-8">
                <!-- Logo and Title -->
                <div class="text-center space-y-2">
                    <div class="h-16 w-16 bg-green-600 rounded-full mx-auto flex items-center justify-center">
                        <i class='bx bxs-car-crash text-white text-4xl'></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800">Welcome Back</h2>
                    <p class="text-gray-500">Sign in with your Microsoft account</p>
                </div>

                <!-- Microsoft Login Button -->
                <div class="space-y-4">
                    <Button
                        @click="loginWithMicrosoft"
                        class="w-full bg-white dark:bg-gray-800 border-2 border-gray-300 hover:border-gray-400 text-gray-700 dark:text-white font-medium py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center gap-3 hover:shadow-md"
                    >
                        <img :src="microsoft_logo" alt="Microsoft" class="w-5 h-5" />
                        Sign in with Microsoft
                    </Button>

                    <p class="text-xs text-center text-gray-400">
                        Secure authentication powered by Microsoft
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
