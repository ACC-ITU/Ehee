<script setup>
import { ref } from 'vue'

const props = defineProps({
    tabs: {
        type: Array,
        required: true
    },
    initialTab: {
        type: String,
        default: null
    }
})

const activeTab = ref(props.initialTab || props.tabs[0].id)
</script>

<template>
    <div>
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8">
                <button
                    v-for="tab in tabs"
                    :key="tab.id"
                    @click="activeTab = tab.id"
                    class="py-4 px-1 relative"
                    :class="[
            activeTab === tab.id
              ? 'text-green-600 font-medium'
              : 'text-gray-500 hover:text-gray-700'
          ]"
                >
                    {{ tab.name }}
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 transition-colors"
                        :class="[
              activeTab === tab.id
                ? 'bg-green-600'
                : 'bg-transparent'
            ]"
                    ></span>
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="mt-6">
            <slot :name="activeTab" />
        </div>
    </div>
</template>
