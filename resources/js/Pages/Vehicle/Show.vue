<script setup>
import {computed, onMounted, ref} from 'vue';
import {
    UserIcon,
    PrinterIcon,
    PencilIcon,
    EyeIcon,
    FileTextIcon,
    DatabaseIcon,
    CarIcon,
    TruckIcon,
    BikeIcon,
    TractorIcon,
} from 'lucide-vue-next';
import {lowerCase, startCase} from "lodash";

const props = defineProps({
    registries: Object,
})

// State for owner view toggle
const ownerView = ref('current');
const registry = ref(props.registries.items[0]);

const currentOwner = computed(() => {
    return registry.value?.owners.find(owner => owner.status === true)?.ownerDetails
})

const previousOwners = computed(() => {
    return registry.value?.owners.filter(owner => owner.ownerDetails.id !== currentOwner.value?.id)
})

const formatDate = (dateString) => {
    const options = {year: 'numeric', month: 'short', day: 'numeric'};
    return new Date(dateString).toLocaleDateString(undefined, options);
}

const formatName = (name) => {
    return startCase(lowerCase(name));
}

function printPage() {
    window.print()
}
</script>

<template>
    <div class="min-h-screen bg-gray-50 p-4 md:p-8">
        <div class="mx-auto max-w-7xl">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 md:text-3xl">Vehicle Registry
                        Details</h1>
                    <p class="text-sm text-gray-500">View complete information about this vehicle registry</p>
                </div>
                <div class="flex gap-2">
                    <button
                        @click="printPage"
                        class="print-button inline-flex items-center justify-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        <PrinterIcon class="mr-2 h-4 w-4"/>
                        Print
                    </button>
                    <!--                    <button-->
                    <!--                        class="inline-flex items-center justify-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-white shadow hover:bg-primary/90">-->
                    <!--                        <PencilIcon class="mr-2 h-4 w-4"/>-->
                    <!--                        Edit-->
                    <!--                    </button>-->
                </div>
            </div>

            <div>
                <h1 class="text-md font-semibold tracking-tight text-gray-600 md:text-xl">
                    Registry Versions
                    <span class="text-sm text-gray-500 font-light">(There maybe multiple versions under same registry number)</span>
                </h1>
                <div class="flex mt-2 w-full gap-5">
                    <button
                        v-for="reg in registries.items" :key="reg.id"
                        @click="registry = reg"
                        class="px-3 py-1 text-sm font-medium rounded-md"
                        :class="registry.id === reg.id ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    >
                        {{ reg.registration_no }}
                        <span class="block">ID: {{ reg.id }}</span>
                    </button>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="flex space-x-5 items-center my-6">
                <!-- Vehicle Type Indicator -->
                <div class="flex  items-center">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <CarIcon v-if="registry.vehicle.description === 'CAR'"
                                 class="h-12 w-12 stroke-1 text-gray-700"/>
                        <BikeIcon v-else-if="registry.vehicle.description === 'MOTOR CYCLE'"
                                  class="h-12 w-12 stroke-1 text-gray-700"/>
                        <TruckIcon
                            v-else-if="registry.vehicle.description === 'PICKUP' || registry.vehicle.description === 'TRUCK'"
                            class="h-12 w-12 stroke-1 text-gray-700"/>
                        <TruckIcon v-else-if="registry.vehicle.description === 'VAN'"
                                   class="h-12 w-12 stroke-1 text-gray-700" style="transform: scale(0.85);"/>
                        <TractorIcon v-else-if="registry.vehicle.description === 'DUMPER'"
                                     class="h-12 w-12 stroke-1 text-gray-700"/>
                        <CarIcon v-else class="h-12 w-12 stroke-1 text-gray-700"/>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900 capitalize">{{ registry.vehicle.description }}</h3>
                    </div>
                    <div class="ml-4">
                        <span
                            :class="[
                            'inline-flex items-center rounded-full px-3 py-1 text-sm font-medium',
                            registry.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']"
                        >
                          <span class="mr-1 h-2 w-2 rounded-full"
                                :class="[
                              registry.active ? 'bg-green-600' : 'bg-red-600']"
                          ></span>
                          {{ registry.active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- General Information -->
                <div class="col-span-1 rounded-lg border bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">General Information</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Registration No.</span>
                            <span class="text-sm font-semibold text-gray-900">{{ registry.registration_no }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Registration Date</span>
                            <span class="text-sm text-gray-900">{{ formatDate(registry.registration_at) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Old Registration No.</span>
                            <span class="text-sm text-gray-900">{{ registry.old_registration_no || 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Island</span>
                            <span class="text-sm text-gray-900">{{ registry.island.name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Atoll</span>
                            <span class="text-sm text-gray-900">{{ registry.island.atoll.name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Information -->
                <div class="col-span-1 lg:col-span-2 rounded-lg border bg-white p-6 shadow-sm page-break-after">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Vehicle Information</h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Family</span>
                            <span class="text-sm text-gray-900">{{ registry.vehicle.family }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Brand</span>
                            <span class="text-sm text-gray-900">{{ registry.vehicle.manufactured_company }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Category</span>
                            <span class="text-sm text-gray-900">{{ registry.vehicle.category }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Model No</span>
                            <span class="text-sm text-gray-900">{{ registry.vehicle.model_no }}</span>
                        </div>

                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Engine No.</span>
                            <span class="text-sm text-gray-900">{{ registry.vehicle.engine_no }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Chassis No.</span>
                            <span class="text-sm text-gray-900">{{ registry.vehicle.chassis_no }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Manufactured Date</span>
                            <span class="text-sm text-gray-900">{{
                                    formatDate(registry.vehicle.manufactured_date)
                                }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Imported Date</span>
                            <span class="text-sm text-gray-900">{{ formatDate(registry.vehicle.imported_date) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Tonnage</span>
                            <span class="text-sm text-gray-900">{{ registry.vehicle.tonnage }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">No of Seats</span>
                            <span class="text-sm text-gray-900">{{ registry.vehicle.no_of_seats }}</span>
                        </div>

                    </div>
                </div>

                <!-- Owner Information -->
                <div class="col-span-1 lg:col-span-3 rounded-lg border bg-white p-6 shadow-sm mt-5 page-break-after">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Owner Information</h2>
                    </div>

                    <!-- Current Owner -->
                    <div>
                        <div v-if="currentOwner" class="space-y-4">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-500">
                                    <UserIcon class="h-6 w-6"/>
                                </div>
                                <div>
                                    <h3 class="text-base font-medium text-gray-900">{{
                                            formatName(currentOwner.full_name)
                                        }}</h3>
                                    <p class="text-sm text-gray-500">ID: {{ formatName(currentOwner.identifier) }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4 pt-4 sm:grid-cols-2 md:grid-cols-3">

                                <div class="rounded-md bg-gray-50 p-3">
                                    <span class="text-xs font-medium text-gray-500">Contact</span>
                                    <p class="text-sm font-medium text-gray-900">{{ currentOwner.phone_no }} /
                                        {{ currentOwner.mobile_no }}</p>
                                </div>
                                <div class="rounded-md bg-gray-50 p-3">
                                    <span class="text-xs font-medium text-gray-500">Island</span>
                                    <p class="text-sm font-medium text-gray-900">{{ currentOwner.island?.name }}</p>
                                </div>
                                <div class="rounded-md bg-gray-50 p-3">
                                    <span class="text-xs font-medium text-gray-500">Atoll</span>
                                    <p class="text-sm font-medium text-gray-900">{{
                                            currentOwner.island?.atoll?.name
                                        }}</p>
                                </div>
                                <div class="rounded-md bg-gray-50 p-3 sm:col-span-2 md:col-span-3">
                                    <span class="text-xs font-medium text-gray-500">Address</span>
                                    <p class="text-sm font-medium text-gray-900">{{ currentOwner.address }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-else
                             class="flex flex-col items-center justify-center rounded-md border border-dashed border-gray-300 p-8 text-center">
                            <UserIcon class="mb-2 h-10 w-10 text-gray-400"/>
                            <h3 class="text-sm font-medium text-gray-900">No owners found</h3>
                            <p class="mt-1 text-sm text-gray-500">This vehicle registry doesn't have any owners</p>
                        </div>

                        <!-- Owners History -->
                        <div class="mt-5">
                            <div class="mb-4 flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900">Owner History</h2>

                            </div>
                            <div class="overflow-hidden rounded-md border" v-if="previousOwners.length > 0">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Name
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            ID
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Contact
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Island/Atoll
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Period
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="(owner) in previousOwners" :key="owner.id">
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                            {{ formatName(owner.ownerDetails.full_name) }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ formatName(owner.ownerDetails.identifier) }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ owner.ownerDetails.phone_no }} / {{ owner.ownerDetails.mobile_no }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ owner.ownerDetails.island?.atoll?.name }}. {{
                                                owner.ownerDetails.island?.name
                                            }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ formatDate(owner.issued_at) }}
                                            - {{ formatDate(owner.expired_at) }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else
                                 class="flex flex-col items-center justify-center rounded-md border border-dashed border-gray-300 p-8 text-center">
                                <UserIcon class="mb-2 h-10 w-10 text-gray-400"/>
                                <h3 class="text-sm font-medium text-gray-900">No previous owners found</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Ownership History -->

                </div>

                <!-- Domain Information -->
                <div class="col-span-1 lg:col-span-3 rounded-lg border bg-white p-6 shadow-sm mt-5">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Domain Information</h2>
                    <div v-if="registry.domains.length > 0" class="overflow-hidden rounded-md border">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Domain No.
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Registration.
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Issued At
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Base
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="(domain) in registry.domains" :key="domain.id">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ domain.domain_no }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ domain.vehicle_registration_no }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{
                                        formatDate(domain.issued_at)
                                    }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    <span
                                        :class="[
                                        'inline-flex rounded-full px-2 py-1 text-xs font-medium',
                                        domain.active ? 'bg-green-100 text-green-800' :
                                        'bg-red-100 text-red-800'
                                      ]"
                                    >
                                      {{ domain.active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ domain.base }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-else
                         class="flex flex-col items-center justify-center rounded-md border border-dashed border-gray-300 p-8 text-center">
                        <DatabaseIcon class="mb-2 h-10 w-10 text-gray-400"/>
                        <h3 class="text-sm font-medium text-gray-900">No domains found</h3>
                        <p class="mt-1 text-sm text-gray-500">This vehicle registry doesn't have any domains
                            assigned.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>

<style>
@media print {
    .print-button {
        display: none; /* Hide the print button when printing */
    }

    /* Insert a page break before a section */
    .page-break-before {
        page-break-before: always;
    }

    /* Insert a page break after a section */
    .page-break-after {
        page-break-after: always;
    }
}

:root {
    --primary: #07d129;
    --primary-foreground: #ffffff;
}

.bg-primary {
    background-color: var(--primary);
}

.text-primary {
    color: var(--primary);
}

.bg-primary\/90 {
    background-color: rgba(9, 172, 0, 0.9);
}

.hover\:bg-primary\/90:hover {
    background-color: rgba(8, 221, 11, 0.9);
}
</style>
