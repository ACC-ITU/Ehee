<script setup>
import {Head, useForm} from "@inertiajs/vue3";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});


function handleLogin() {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
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
                    <p class="text-gray-500">Sign in to your account</p>
                </div>

                <!-- Login Form -->
                <form @submit.prevent="handleLogin" class="space-y-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700 block">Email</label>
                            <input
                                autocomplete="email"
                                type="email"
                                v-model="form.email"
                                class="mt-1 block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                                placeholder="Enter your email"
                                required
                            />
                            <p v-if="form.errors.email" class="text-xs text-left text-red-600 font-light">{{form.errors.email}}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 block">Password</label>
                            <input
                                type="password"
                                v-model="form.password"
                                class="mt-1 block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                                placeholder="Enter your password"
                                required
                            />
                        </div>
                    </div>

                    <!-- Remember Me and Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                id="remember"
                                v-model="form.remember"
                                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                            />
                            <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                        </div>
                        <a href="#" class="text-sm text-green-600 hover:text-green-500">Forgot password?</a>
                    </div>

                    <!-- Login Button -->
                    <button
                        type="submit"
                        class="w-full bg-green-600 text-white rounded-lg px-4 py-3 font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200"
                    >
                        Sign in
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
