<template>
    <div class="min-h-screen bg-gray-200 flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
            <!-- Error Icon -->
            <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-2xl font-semibold text-gray-900 mb-4">
                Account Inactive
            </h1>

            <!-- Message -->
            <p class="text-gray-600 mb-8 leading-relaxed">
                Your user account is inactive or not authorized to use this application.
                Please contact IT support to assist.
            </p>

            <!-- Contact Button -->
            <button class="w-full bg-gray-900 text-white py-3 px-4 rounded-md hover:bg-gray-800 transition-colors duration-200 font-medium">
                Contact IT Support
            </button>
        </div>
    </div>
</template>

<script setup>
// No logic needed for this static page
</script>
