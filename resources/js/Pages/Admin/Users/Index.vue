<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Users Management</h1>
                <p class="mt-2 text-gray-600">Manage user accounts and permissions</p>
            </div>
            <div class="flex flex-col md:flex-row gap-2 md:gap-6">

                <div class="relative w-full min-w-1.5">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input
                        @input="onChangeSearch"
                        v-model="form.search"
                        type="text"
                        placeholder="Search users..."
                        class="block w-full pl-10 pr-3 py-4 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                    />

                    <div v-if="form.processing" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg class="animate-spin h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
                <select v-model="form.status" id="status" name="status"
                        class="rounded border-gray-300"
                        @change="performSearch"
                >
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>

                <select v-model="form.admin" id="admin" name="admin"
                        class="rounded border-gray-300"
                        @change="performSearch"
                >
                    <option value="">All Roles</option>
                    <option value="1">Only Admins</option>
                    <option value="0">Only Users</option>
                </select>
            </div>

            <!-- Users Table -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mt-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">All Users</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-white font-medium text-sm">
                                            {{ getInitials(user.name) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                                        <div class="text-sm text-gray-500">{{ user.email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span v-if="user.is_admin"
                                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    Admin
                                </span>
                                <span v-else
                                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    User
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span v-if="user.status===1"
                                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                                <span v-else
                                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">

                                    <!-- Admin Toggle -->
                                    <button
                                        v-if="user.is_admin"
                                        @click="removeAdmin(user)"
                                        class="text-orange-600 hover:text-orange-900 p-1 rounded hover:bg-orange-50"
                                        title="Remove admin"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                        </svg>
                                    </button>
                                    <button
                                        v-else
                                        @click="makeAdmin(user)"
                                        class="text-purple-600 hover:text-purple-900 p-1 rounded hover:bg-purple-50"
                                        title="Make admin"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </button>

                                    <!-- Activation Toggle -->
                                    <button
                                        v-if="user.status===1"
                                        @click="deactivateUser(user)"
                                        class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50"
                                        title="Deactivate user"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                    </button>
                                    <button
                                        v-else
                                        @click="activateUser(user)"
                                        class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50"
                                        title="Activate user"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>


                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <button
                                v-if="users.prev_page_url"
                                @click="router.visit(users.prev_page_url)"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Previous
                            </button>
                            <button
                                v-if="users.next_page_url"
                                @click="router.visit(users.next_page_url)"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Next
                            </button>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ users.from }}</span>
                                    to
                                    <span class="font-medium">{{ users.to }}</span>
                                    of
                                    <span class="font-medium">{{ users.total }}</span>
                                    results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                     aria-label="Pagination">
                                    <!-- Previous Button -->
                                    <button
                                        v-if="users.prev_page_url"
                                        @click="router.visit(users.prev_page_url)"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                    >
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                    <span
                                        v-else
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400"
                                    >
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </span>

                                    <!-- Page Numbers -->
                                    <template v-for="(link, index) in users.links" :key="index">
                                        <button
                                            v-if="link.url && !link.label.includes('Previous') && !link.label.includes('Next')"
                                            @click="router.visit(link.url)"
                                            :class="['relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                                link.active ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                                  : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                                                  ]"
                                            v-html="link.label"
                                        >
                                        </button>
                                        <span
                                            v-else-if="!link.url && !link.label.includes('Previous') && !link.label.includes('Next')"
                                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                                            v-html="link.label"
                                        >
                                        </span>
                                    </template>

                                    <!-- Next Button -->
                                    <button
                                        v-if="users.next_page_url"
                                        @click="router.visit(users.next_page_url)"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                    >
                                        <span class="sr-only">Next</span>
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                    <span
                                        v-else
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400"
                                    >
                                        <span class="sr-only">Next</span>
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {router, useForm} from '@inertiajs/vue3'
import EheeLayout from "@/Layouts/EheeLayout.vue";

defineProps({
    users: Object // Pagination object with data, links, meta, etc.
})

defineOptions({
    layout: EheeLayout,
})

const getInitials = (name) => {
    return name
        .split(' ')
        .map(word => word.charAt(0))
        .join('')
        .toUpperCase()
        .substring(0, 2)
}

const form = useForm({
    search: '',
    status: '',
    admin: '',
})

let searchTimeout = null

// Watch for changes in a search query
const onChangeSearch = () => {
    // Clear existing timeout
    if (searchTimeout) {
        clearTimeout(searchTimeout)
    }

    // Set new timeout
    searchTimeout = setTimeout(() => {
        performSearch()
    }, 500) // 500ms delay
}

const performSearch = () => {
    form.get(route('users.index'), {
            preserveState: true,
            preserveScroll: true,
        }
    )
}

const makeAdmin = (user) => {
    router.put(route('users.make-admin', user), {}, {
        preserveScroll: true,
        onSuccess: () => {
            // Success handled by Laravel flash messages
        }
    })
}

const removeAdmin = (user) => {
    router.put(route('users.remove-admin', user), {}, {
        preserveScroll: true,
        onSuccess: () => {
            // Success handled by Laravel flash messages
        }
    })
}

const activateUser = (user) => {
    router.put(route('users.activate', user), {}, {
        preserveScroll: true,
        onSuccess: () => {
            // Success handled by Laravel flash messages
        }
    })
}

const deactivateUser = (user) => {
    if (confirm(`Are you sure you want to deactivate ${user.name}?`)) {
        router.put(route('users.deactivate', user), {}, {
            preserveScroll: true,
            onSuccess: () => {
                // Success handled by Laravel flash messages
            }
        })
    }
}
</script>
