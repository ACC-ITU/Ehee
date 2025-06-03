<!-- resources/js/Components/Charts/DoughnutChart.vue -->
<template>
    <canvas ref="chartCanvas"></canvas>
</template>

<script setup>
import { ref, onMounted, watch, onBeforeUnmount } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
    chartData: {
        type: Object,
        required: true
    },
    options: {
        type: Object,
        default: () => ({})
    }
});

const chartCanvas = ref(null);
let chart = null;

const createChart = () => {
    if (chartCanvas.value) {
        const ctx = chartCanvas.value.getContext('2d');

        chart = new Chart(ctx, {
            type: 'doughnut',
            data: props.chartData,
            options: props
        });
    }
};

const updateChart = () => {
    if (chart) {
        chart.data = props.chartData;

        if (props.options) {
            chart.options = props.options;
        }

        chart.update();
    }
};

watch(() => props.chartData, () => {
    if (chart) {
        updateChart();
    }
}, { deep: true });

watch(() => props.options, () => {
    if (chart) {
        updateChart();
    }
}, { deep: true });

onMounted(() => {
    createChart();
});

onBeforeUnmount(() => {
    if (chart) {
        chart.destroy();
    }
});
</script>
