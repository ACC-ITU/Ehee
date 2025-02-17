<script setup>

const props = defineProps({
    domain: Object,
})

</script>

<template>
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col justify-start gap-5">
            <div class="flex space-x-5">
                <div class="w-48 font-bold text-xl">ID: </div>
                <p>{{domain.id}}</p>
            </div>

            <div class="flex  space-x-5">
                <div class="w-48 font-bold text-xl">Domain NO: </div>
                <p>{{domain.domain_no}}</p>
            </div>

            <div class="flex  space-x-5">
                <div class="w-48 font-bold text-xl">Issued At: </div>
                <p>{{domain.issued_at}}</p>
            </div>

            <div class="flex  space-x-5">
                <div class="w-48 font-bold text-xl">Registration NO: </div>
                <p>{{domain.vehicle_registration_no}}</p>
            </div>

            <div class="flex  space-x-5">
                <div class="w-48 font-bold text-xl">Owner ID: </div>
                <p>{{domain.owner_id}}</p>
            </div>

            <div class="flex  space-x-5">
                <div class="w-48 font-bold text-xl">Base: </div>
                <p>{{domain.base}}</p>
            </div>

        </div>
    </div>

</template>
