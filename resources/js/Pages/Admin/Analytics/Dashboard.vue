<!-- resources/js/Pages/Admin/Analytics/Index.vue -->
<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900">Vehicle Registration Analytics</h1>
                <div class="flex space-x-3">
                    <button
                        @click="exportData"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    >
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export Data
                    </button>
                    <div class="text-sm text-gray-500 flex items-center">
                        Last updated: {{ formattedDateTime }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Main content -->
        <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Filter Controls -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex flex-col md:flex-row justify-between space-y-4 md:space-y-0 md:space-x-4">
                    <div class="w-full md:w-1/4">
                        <label for="dateRange" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                        <select
                            id="dateRange"
                            v-model="filters.dateRange"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                            @change="applyFilters"
                        >
                            <option value="day">Today</option>
                            <option value="week">Last 7 days</option>
                            <option value="month">Last 30 days</option>
                            <option value="quarter">Last 90 days</option>
                            <option value="year">Last year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>

                    <div v-if="filters.dateRange === 'custom'" class="w-full md:w-1/2 flex space-x-4">
                        <div class="w-1/2">
                            <label for="startDate" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input
                                type="date"
                                id="startDate"
                                v-model="filters.startDate"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                            />
                        </div>
                        <div class="w-1/2">
                            <label for="endDate" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input
                                type="date"
                                id="endDate"
                                v-model="filters.endDate"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                            />
                        </div>
                    </div>

                    <div class="w-full md:w-1/4">
                        <label for="filterType" class="block text-sm font-medium text-gray-700 mb-1">View</label>
                        <select
                            id="filterType"
                            v-model="filters.type"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                            @change="applyFilters"
                        >
                            <option value="all">All Activities</option>
                            <option value="search">Searches Only</option>
                            <option value="view">Views Only</option>
                        </select>
                    </div>

                    <div class="w-full md:w-1/4 flex items-end">
                        <button
                            type="button"
                            @click="applyFilters"
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
                <!-- Total Searches Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Searches</dt>
                                    <dd>
                                        <div class="text-xl font-semibold text-gray-900">{{ totalSearches }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
              <span class="font-medium text-green-700 hover:text-green-900">
                {{ searchTrend > 0 ? '+' : '' }}{{ searchTrend }}% from previous period
              </span>
                        </div>
                    </div>
                </div>

                <!-- Total Views Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Views</dt>
                                    <dd>
                                        <div class="text-xl font-semibold text-gray-900">{{ totalViews }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
              <span class="font-medium text-green-700 hover:text-green-900">
                {{ viewTrend > 0 ? '+' : '' }}{{ viewTrend }}% from previous period
              </span>
                        </div>
                    </div>
                </div>

                <!-- Average Search by Day Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Avg. Searches/Day</dt>
                                    <dd>
                                        <div class="text-xl font-semibold text-gray-900">{{ avgSearchesPerDay }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
              <span class="font-medium text-purple-700 hover:text-purple-900">
                {{ searchesPerDayTrend > 0 ? '+' : '' }}{{ searchesPerDayTrend }}% from previous period
              </span>
                        </div>
                    </div>
                </div>

                <!-- Search to View Ratio Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Search to View Ratio</dt>
                                    <dd>
                                        <div class="text-xl font-semibold text-gray-900">{{ searchToViewRatio }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
              <span class="font-medium text-yellow-700 hover:text-yellow-900">
                {{ searchToViewRatio > 5 ? 'High search rate' : 'Balanced activity' }}
              </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Activity Over Time Chart -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-5 py-4 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Activity Over Time</h3>
                    </div>
                    <div class="p-5">
                        <div class="h-72">
                            <line-chart
                                :chart-data="timeSeriesData"
                                :options="timeSeriesOptions"
                            />
                        </div>
                    </div>
                </div>

                <!-- Popular Searches Chart -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-5 py-4 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Most Common Search Parameters</h3>
                    </div>
                    <div class="p-5">
                        <div class="h-72">
                            <bar-chart
                                :chart-data="commonSearchParamsData"
                                :options="commonSearchParamsOptions"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tables Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Most Searched Vehicles Table -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-5 py-4 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Most Searched Vehicles</h3>
                    </div>
                    <div class="p-0">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SearchedKey</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SearchedValue</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Search Count</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="(item, index) in commonSearchParams" :key="index">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ Object.keys(item.search_parameters)[0] || '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ Object.values(item.search_parameters)[0] || '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.date || '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.owner || '-' }}</td>
                                </tr>
                                <tr v-if="commonSearchParams.length === 0">
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No data available</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Popular Vehicles Table -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-5 py-4 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Most Viewed Vehicles</h3>
                    </div>
                    <div class="p-0">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration No.</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">View Count</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Domain</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Most Recent View</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="(item, index) in popularVehicles" :key="index">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.registration_no }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.view_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.domain || '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(item.last_viewed_at) }}</td>
                                </tr>
                                <tr v-if="popularVehicles.length === 0">
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No data available</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import LineChart from '@/Components/Charts/LineChart.vue';
import BarChart from '@/Components/Charts/BarChart.vue';
import EheeLayout from "@/Layouts/EheeLayout.vue";

defineOptions({
    layout: EheeLayout,
})

// Define props
const props = defineProps({
    searchData: {
        type: Array,
        default: () => []
    },
    viewData: {
        type: Array,
        default: () => []
    },
    commonSearchParams: {
        type: Array,
        default: () => []
    },
    popularVehicles: {
        type: Array,
        default: () => []
    }
});

// Reactive state
const filters = ref({
    dateRange: 'week',
    startDate: getDateXDaysAgo(7),
    endDate: getTodayDate(),
    type: 'all'
});

// Computed properties
const formattedDateTime = computed(() => {
    return new Date().toLocaleString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
});

// Stats calculations
const totalSearches = computed(() => {
    return props.searchData.reduce((sum, item) => sum + item.count, 0);
});

const totalViews = computed(() => {
    return props.viewData.reduce((sum, item) => sum + item.count, 0);
});

const avgSearchesPerDay = computed(() => {
    if (props.searchData.length === 0) return 0;
    return Math.round(totalSearches.value / props.searchData.length);
});

const searchToViewRatio = computed(() => {
    if (totalViews.value === 0) return 0;
    return (totalSearches.value / totalViews.value).toFixed(2);
});

// Trends (placeholders - would be calculated with real data)
const searchTrend = ref(12.4);
const viewTrend = ref(-5.8);
const searchesPerDayTrend = ref(7.2);

// Chart data
const timeSeriesData = computed(() => {
    const labels = [...new Set([
        ...props.searchData.map(item => item.date),
        ...props.viewData.map(item => item.date)
    ])].sort();

    const searchCounts = labels.map(date => {
        const match = props.searchData.find(item => item.date === date);
        return match ? match.count : 0;
    });

    const viewCounts = labels.map(date => {
        const match = props.viewData.find(item => item.date === date);
        return match ? match.count : 0;
    });

    return {
        labels,
        datasets: [
            {
                label: 'Searches',
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgb(59, 130, 246)',
                data: searchCounts,
                tension: 0.4
            },
            {
                label: 'Views',
                backgroundColor: 'rgba(16, 185, 129, 0.5)',
                borderColor: 'rgb(16, 185, 129)',
                data: viewCounts,
                tension: 0.4
            }
        ]
    };
});

const timeSeriesOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        x: {
            grid: {
                display: false
            }
        },
        y: {
            beginAtZero: true,
            grid: {
                color: 'rgba(0, 0, 0, 0.05)'
            }
        }
    },
    plugins: {
        legend: {
            position: 'top'
        },
        tooltip: {
            mode: 'index',
            intersect: false
        }
    },
    interaction: {
        mode: 'nearest',
        axis: 'x',
        intersect: false
    }
};

const commonSearchParamsData = computed(() => {
    const sortedParams = [...props.commonSearchParams].sort((a, b) => b.count - a.count).slice(0, 8);

    return {
        labels: sortedParams.map(item => item.registration_no || 'Other'),
        datasets: [
            {
                label: 'Search Count',
                backgroundColor: 'rgba(99, 102, 241, 0.8)',
                data: sortedParams.map(item => item.count)
            }
        ]
    };
});

const commonSearchParamsOptions = {
    responsive: true,
    maintainAspectRatio: false,
    indexAxis: 'y',
    scales: {
        x: {
            beginAtZero: true,
            grid: {
                color: 'rgba(0, 0, 0, 0.05)'
            }
        },
        y: {
            grid: {
                display: false
            }
        }
    },
    plugins: {
        legend: {
            display: false
        }
    }
};

// Methods
function applyFilters() {
    router.get(route('analytics.index'), {
        date_range: filters.value.dateRange,
        start_date: filters.value.dateRange === 'custom' ? filters.value.startDate : undefined,
        end_date: filters.value.dateRange === 'custom' ? filters.value.endDate : undefined,
        type: filters.value.type
    }, {
        preserveState: true,
        preserveScroll: true,
        only: ['searchData', 'viewData', 'commonSearchParams', 'popularVehicles']
    });
}

function exportData() {
    window.location.href = route('analytics.export', {
        date_range: filters.value.dateRange,
        start_date: filters.value.dateRange === 'custom' ? filters.value.startDate : undefined,
        end_date: filters.value.dateRange === 'custom' ? filters.value.endDate : undefined,
        type: filters.value.type
    });
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function getTodayDate() {
    return new Date().toISOString().split('T')[0];
}

function getDateXDaysAgo(days) {
    const date = new Date();
    date.setDate(date.getDate() - days);
    return date.toISOString().split('T')[0];
}

// Lifecycle
onMounted(() => {
    console.log('Analytics dashboard mounted');
});
</script>
