<!-- resources/js/Pages/Admin/ActivityLogs/Index.vue -->
<template>
    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Activity Logs</h1>
                <div class="flex space-x-3">
                    <button
                        @click="exportLogs"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    >
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export CSV
                    </button>
                </div>
            </div>

            <!-- Filters Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form @submit.prevent="applyFilters">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- User Filter -->
                        <div>
                            <label for="user-filter" class="block text-sm font-medium text-gray-700 mb-1">User</label>
                            <select
                                id="user-filter"
                                v-model="filters.userId"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            >
                                <option value="all">All Users</option>
                                <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                            </select>
                        </div>

                        <!-- Activity Type Filter -->
                        <div>
                            <label for="type-filter" class="block text-sm font-medium text-gray-700 mb-1">Activity Type</label>
                            <select
                                id="type-filter"
                                v-model="filters.actionType"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            >
                                <option value="all">All Types</option>
                                <option value="search">Search</option>
                                <option value="view">View</option>
                            </select>
                        </div>

                        <!-- Date Range Filter -->
                        <div>
                            <label for="date-range" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                            <select
                                id="date-range"
                                v-model="filters.dateRange"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            >
                                <option value="all">All Time</option>
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="week">Last 7 Days</option>
                                <option value="month">Last 30 Days</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>

                        <!-- Search Text -->
                        <div>
                            <label for="search-text" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input
                                id="search-text"
                                type="text"
                                v-model="filters.searchText"
                                placeholder="Registration number, parameters..."
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            />
                        </div>
                    </div>

                    <!-- Custom Date Range (only visible when Custom Range is selected) -->
                    <div v-if="filters.dateRange === 'custom'" class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label for="start-date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input
                                id="start-date"
                                type="date"
                                v-model="filters.startDate"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            />
                        </div>
                        <div>
                            <label for="end-date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input
                                id="end-date"
                                type="date"
                                v-model="filters.endDate"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            />
                        </div>
                    </div>

                    <!-- Advanced Filters Toggle -->
                    <div class="mt-4">
                        <button
                            type="button"
                            @click="showAdvancedFilters = !showAdvancedFilters"
                            class="text-sm text-green-600 hover:text-green-800 flex items-center font-medium"
                        >
                            <span>{{ showAdvancedFilters ? 'Hide' : 'Show' }} Advanced Filters</span>
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    :d="showAdvancedFilters ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'"
                                />
                            </svg>
                        </button>
                    </div>

                    <!-- Advanced Filters -->
                    <div v-if="showAdvancedFilters" class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                        <!-- Domain Filter -->
                        <div>
                            <label for="domain-filter" class="block text-sm font-medium text-gray-700 mb-1">Domain</label>
                            <select
                                id="domain-filter"
                                v-model="filters.domainId"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            >
                                <option value="all">All Domains</option>
                                <option v-for="domain in domains" :key="domain.id" :value="domain.id">{{ domain.name }}</option>
                            </select>
                        </div>

                        <!-- Registration Number Filter -->
                        <div>
                            <label for="registration-no" class="block text-sm font-medium text-gray-700 mb-1">Registration No.</label>
                            <input
                                id="registration-no"
                                type="text"
                                v-model="filters.registrationNo"
                                placeholder="e.g. A0R5478"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            />
                        </div>

                        <!-- IP Address Filter -->
                        <div>
                            <label for="ip-address" class="block text-sm font-medium text-gray-700 mb-1">IP Address</label>
                            <input
                                id="ip-address"
                                type="text"
                                v-model="filters.ipAddress"
                                placeholder="e.g. 192.168.1.100"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            />
                        </div>
                    </div>

                    <!-- Filter Actions -->
                    <div class="flex justify-end space-x-3 mt-6">
                        <button
                            type="button"
                            @click="resetFilters"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            Reset
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Activity Logs Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg leading-6 font-medium text-gray-900">Activity Log Records</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Time
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Registration No
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                IP Address
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="log in logs.data" :key="log.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ formatDateTime(log.created_at) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ log.user ? log.user.name : 'Unknown' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                  <span
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                      :class="{
                      'bg-green-100 text-green-800': log.action_type === 'search',
                      'bg-amber-100 text-amber-800': log.action_type === 'view'
                    }"
                  >
                    {{ log.action_type }}
                  </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ log.registration_no || '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ log.ip_address || '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button
                                    @click="viewLogDetails(log)"
                                    class="text-green-600 hover:text-green-900 mr-3"
                                >
                                    View
                                </button>
                            </td>
                        </tr>

                        <!-- Empty state -->
                        <tr v-if="logs.data.length === 0">
                            <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2 font-medium">No activity logs found</p>
                                    <p class="mt-1">Try adjusting your filters or search criteria</p>
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
                                @click="goToPreviousPage"
                                :disabled="!logs.prev_page_url"
                                :class="{ 'opacity-50 cursor-not-allowed': !logs.prev_page_url }"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Previous
                            </button>
                            <button
                                @click="goToNextPage"
                                :disabled="!logs.next_page_url"
                                :class="{ 'opacity-50 cursor-not-allowed': !logs.next_page_url }"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Next
                            </button>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ logs.from || 0 }}</span>
                                    to
                                    <span class="font-medium">{{ logs.to || 0 }}</span>
                                    of
                                    <span class="font-medium">{{ logs.total }}</span>
                                    results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <!-- Previous Button -->
                                    <button
                                        @click="goToPreviousPage"
                                        :disabled="!logs.prev_page_url"
                                        :class="{ 'opacity-50 cursor-not-allowed': !logs.prev_page_url }"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                    >
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    <!-- Page Number Buttons -->
                                    <button
                                        v-for="page in paginationPages"
                                        :key="page"
                                        @click="goToPage(page)"
                                        :class="[
                      page === logs.current_page
                        ? 'z-10 bg-green-50 border-green-500 text-green-600'
                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                      'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                    ]"
                                    >
                                        {{ page }}
                                    </button>

                                    <!-- Next Button -->
                                    <button
                                        @click="goToNextPage"
                                        :disabled="!logs.next_page_url"
                                        :class="{ 'opacity-50 cursor-not-allowed': !logs.next_page_url }"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                    >
                                        <span class="sr-only">Next</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Log Detail Modal -->
        <div v-if="showDetailModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showDetailModal = false"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Activity Log Details
                                </h3>

                                <div class="mt-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Basic Info -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500 mb-3">Basic Information</h4>
                                            <dl class="grid grid-cols-3 gap-x-4 gap-y-2">
                                                <dt class="text-sm font-medium text-gray-500">ID:</dt>
                                                <dd class="text-sm text-gray-900 col-span-2">{{ selectedLog.id }}</dd>

                                                <dt class="text-sm font-medium text-gray-500">Type:</dt>
                                                <dd class="text-sm text-gray-900 col-span-2">
                          <span
                              class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                              :class="{
                              'bg-green-100 text-green-800': selectedLog.action_type === 'search',
                              'bg-amber-100 text-amber-800': selectedLog.action_type === 'view'
                            }"
                          >
                            {{ selectedLog.action_type }}
                          </span>
                                                </dd>

                                                <dt class="text-sm font-medium text-gray-500">User:</dt>
                                                <dd class="text-sm text-gray-900 col-span-2">{{ selectedLog.user ? selectedLog.user.name : 'Unknown' }}</dd>

                                                <dt class="text-sm font-medium text-gray-500">Date:</dt>
                                                <dd class="text-sm text-gray-900 col-span-2">{{ formatDetailDateTime(selectedLog.created_at) }}</dd>

                                                <dt class="text-sm font-medium text-gray-500">IP:</dt>
                                                <dd class="text-sm text-gray-900 col-span-2">{{ selectedLog.ip_address || '-' }}</dd>
                                            </dl>
                                        </div>

                                        <!-- Vehicle Info -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500 mb-3">Vehicle Information</h4>
                                            <dl class="grid grid-cols-3 gap-x-4 gap-y-2">
                                                <dt class="text-sm font-medium text-gray-500">Registration:</dt>
                                                <dd class="text-sm text-gray-900 col-span-2">{{ selectedLog.registration_no || '-' }}</dd>

                                                <dt class="text-sm font-medium text-gray-500">Domain:</dt>
                                                <dd class="text-sm text-gray-900 col-span-2">{{ getDomainName(selectedLog.domain) }}</dd>

                                                <dt class="text-sm font-medium text-gray-500">Endpoint:</dt>
                                                <dd class="text-sm text-gray-900 col-span-2">{{ selectedLog.endpoint || '-' }}</dd>
                                            </dl>
                                        </div>
                                    </div>

                                    <!-- Search Parameters (if any) -->
                                    <div v-if="selectedLog.search_parameters" class="mt-6">
                                        <h4 class="text-sm font-medium text-gray-500 mb-3">Search Parameters</h4>
                                        <div class="bg-gray-50 rounded-md p-3 overflow-x-auto">
                                            <pre class="text-xs text-gray-700 whitespace-pre-wrap">{{ formatJSON(selectedLog.search_parameters) }}</pre>
                                        </div>
                                    </div>

                                    <!-- Metadata (if any) -->
                                    <div v-if="selectedLog.metadata" class="mt-6">
                                        <h4 class="text-sm font-medium text-gray-500 mb-3">Response Metadata</h4>
                                        <div class="bg-gray-50 rounded-md p-3 overflow-x-auto">
                                            <pre class="text-xs text-gray-700 whitespace-pre-wrap">{{ formatJSON(selectedLog.metadata) }}</pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            @click="showDetailModal = false"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

// Define props for data passed from the controller
const props = defineProps({
    logs: {
        type: Object,
        required: true
    },
    users: {
        type: Array,
        default: () => []
    },
    domains: {
        type: Array,
        default: () => []
    },
    filters: {
        type: Object,
        default: () => ({})
    }
});

// State
const showAdvancedFilters = ref(false);
const showDetailModal = ref(false);
const selectedLog = ref({});

// Initialize filters with defaults or props values
const filters = ref({
    userId: props.filters.userId || 'all',
    actionType: props.filters.actionType || 'all',
    dateRange: props.filters.dateRange || 'week',
    startDate: props.filters.startDate || getTodayDate(-7), // Default to 7 days ago
    endDate: props.filters.endDate || getTodayDate(),
    searchText: props.filters.searchText || '',
    registrationNo: props.filters.registrationNo || '',
    domainId: props.filters.domainId || 'all',
    ipAddress: props.filters.ipAddress || ''
});

// Computed properties
const paginationPages = computed(() => {
    if (!props.logs.last_page) return [];

    const currentPage = props.logs.current_page;
    const lastPage = props.logs.last_page;

    // Create an array of page numbers to show
    let pages = [];

    // Always include first and last page
    const pageRange = 2; // Show 2 pages on each side of current page

    // Start with page 1
    pages.push(1);

    // Add ellipsis if needed
    if (currentPage - pageRange > 2) {
        pages.push('...');
    }

    // Add pages around current page
    for (let i = Math.max(2, currentPage - pageRange); i <= Math.min(lastPage - 1, currentPage + pageRange); i++) {
        pages.push(i);
    }

    // Add ellipsis if needed
    if (currentPage + pageRange < lastPage - 1) {
        pages.push('...');
    }

    // Add last page if not already included
    if (lastPage > 1) {
        pages.push(lastPage);
    }

    return pages;
});

// Methods
function applyFilters() {
    router.get(route('activity-logs.index'), {
        userId: filters.value.userId,
        actionType: filters.value.actionType,
        dateRange: filters.value.dateRange,
        startDate: filters.value.dateRange === 'custom' ? filters.value.startDate : undefined,
        endDate: filters.value.dateRange === 'custom' ? filters.value.endDate : undefined,
        searchText: filters.value.searchText,
        registrationNo: filters.value.registrationNo,
        domainId: filters.value.domainId,
        ipAddress: filters.value.ipAddress
    }, {
        preserveState: true,
        preserveScroll: true
    });
}

function resetFilters() {
    filters.value = {
        userId: 'all',
        actionType: 'all',
        dateRange: 'week',
        startDate: getTodayDate(-7),
        endDate: getTodayDate(),
        searchText: '',
        registrationNo: '',
        domainId: 'all',
        ipAddress: ''
    };
}

function goToPreviousPage() {
    if (props.logs.prev_page_url) {
        router.get(props.logs.prev_page_url, {}, {
            preserveState: true,
            preserveScroll: true
        });
    }
}

function goToNextPage() {
    if (props.logs.next_page_url) {
        router.get(props.logs.next_page_url, {}, {
            preserveState: true,
            preserveScroll: true
        });
    }
}

function goToPage(page) {
    if (typeof page === 'number') {
        router.get(route('activity-logs.index'), {
            // Include all current filters
            ...Object.fromEntries(
                Object.entries(filters.value).filter(([_, v]) => v !== '' && v !== 'all')
            ),
            page
        }, {
            preserveState: true,
            preserveScroll: true
        });
    }
}

function viewLogDetails(log) {
    selectedLog.value = log;
    showDetailModal.value = true;
}

function exportLogs() {
    // Navigate to export route with current filters
    const queryParams = new URLSearchParams();

    Object.entries(filters.value).forEach(([key, value]) => {
        if (value !== '' && value !== 'all') {
            queryParams.append(key, value);
        }
    });

    window.location.href = `${route('activity-logs.export')}?${queryParams.toString()}`;
}

function formatDateTime(dateTime) {
    if (!dateTime) return '-';
    const date = new Date(dateTime);
    return date.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatDetailDateTime(dateTime) {
    if (!dateTime) return '-';
    const date = new Date(dateTime);
    return date.toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
}

function formatJSON(data) {
    if (typeof data === 'string') {
        try {
            data = JSON.parse(data);
        } catch (e) {
            // If it's not valid JSON, return as is
            return data;
        }
    }
    return JSON.stringify(data, null, 2);
}

function getDomainName(domainId) {
    if (!domainId) return '-';
    const domain = props.domains.find(d => d.id === parseInt(domainId));
    return domain ? domain.name : domainId;
}

function getTodayDate(offset = 0) {
    const date = new Date();
    if (offset) {
        date.setDate(date.getDate() + offset);
    }
    return date.toISOString().split('T')[0];
}

// Initialize component
onMounted(() => {
    console.log('Activity Log Viewer mounted');
});
</script>
