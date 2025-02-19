<template>
    <div>
        <!-- Hamburger button -->
        <button
            @click="isOpen = !isOpen"
            class="fixed top-4 left-4 p-2 rounded-lg hover:bg-gray-100 transition-colors z-50"
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
                class="fixed inset-0 bg-black/20 z-40"
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
                class="fixed top-0 left-0 h-full w-20 bg-white shadow-lg z-50 flex flex-col items-center"
            >
                <!-- Main buttons container -->
                <div class="flex-1 w-full pt-16 flex flex-col items-center gap-4">
                    <button
                        v-for="(button, index) in mainButtons"
                        :key="index"
                        @click="button.action"
                        class="w-12 h-12 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors flex items-center justify-center text-gray-700 hover:text-gray-900"
                    >
                        <component :is="button.icon" class="w-5 h-5" />
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

<script setup>
import {defineComponent, h, ref} from 'vue'
import {router, useForm} from "@inertiajs/vue3";

const isOpen = ref(false)

// Icon components
const HomeIcon = defineComponent({
    render: () => h('svg', {
        class: 'w-5 h-5',
        fill: 'none',
        stroke: 'currentColor',
        viewBox: '0 0 24 24'
    }, [
        h('path', {
            'stroke-linecap': 'round',
            'stroke-linejoin': 'round',
            'stroke-width': '2',
            d: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
        })
    ])
})

const MailIcon = defineComponent({
    render: () => h('svg', {
        class: 'w-5 h-5',
        fill: 'none',
        stroke: 'currentColor',
        viewBox: '0 0 24 24'
    }, [
        h('path', {
            'stroke-linecap': 'round',
            'stroke-linejoin': 'round',
            'stroke-width': '2',
            d: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'
        })
    ])
})

const BellIcon = defineComponent({
    render: () => h('svg', {
        class: 'w-5 h-5',
        fill: 'none',
        stroke: 'currentColor',
        viewBox: '0 0 24 24'
    }, [
        h('path', {
            'stroke-linecap': 'round',
            'stroke-linejoin': 'round',
            'stroke-width': '2',
            d: 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'
        })
    ])
})

const SettingsIcon = defineComponent({
    render: () => h('svg', {
        class: 'w-5 h-5',
        fill: 'none',
        stroke: 'currentColor',
        viewBox: '0 0 24 24'
    }, [
        h('path', {
            'stroke-linecap': 'round',
            'stroke-linejoin': 'round',
            'stroke-width': '2',
            d: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'
        }),
        h('path', {
            'stroke-linecap': 'round',
            'stroke-linejoin': 'round',
            'stroke-width': '2',
            d: 'M15 12a3 3 0 11-6 0 3 3 0 016 0z'
        })
    ])
})

// Main buttons configuration
const mainButtons = [
    { icon: HomeIcon, action: () => router.visit(route('domains.index')) },
]

// Logout handler
const handleLogout = () => {
    console.log('Logout clicked')
    useForm().post(route('logout'));
}
</script>
