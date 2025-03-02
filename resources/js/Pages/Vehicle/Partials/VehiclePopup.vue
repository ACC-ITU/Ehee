<script setup>
import PopupCard from "@/Components/PopupCard.vue";
import VehicleCard from "@/Pages/Vehicle/Partials/VehicleCard.vue";
import {computed} from "vue";

const props = defineProps({
    modelValue: {
        required: true,
        type: Boolean,
    },
    vehicle: Object,
})

const emit = defineEmits(["update:modelValue"]);
const visible = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
})

function close() {
    emit('update:modelValue', false);
}
</script>

<template>
    <PopupCard v-model="visible">
        <VehicleCard :vehicle="vehicle" />
    </PopupCard>

</template>
