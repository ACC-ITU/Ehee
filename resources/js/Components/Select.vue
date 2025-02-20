<script setup>
import {ref, computed} from 'vue'
import {onClickOutside} from '@vueuse/core'

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: ''
    },
    options: {
        type: Array,
        required: true,
        validator: (options) => {
            return options.every(option => 'value' in option && 'label' in option)
        }
    },
    placeholder: {
        type: String,
        default: 'Select an option'
    },
    disabled: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

// State
const isOpen = ref(false)
const selectRef = ref(null)

// Computed
const displayValue = computed(() => {
    const selectedOption = props.options.find(option => option.value === props.modelValue)
    return selectedOption ? selectedOption.label : props.placeholder
})

// Methods
const toggle = () => {
    if (!props.disabled) {
        isOpen.value = !isOpen.value
    }
}

const selectOption = (option) => {
    emit('update:modelValue', option.value)
    emit('change', option.value)
    isOpen.value = false
}

// Click outside handling
onClickOutside(selectRef, () => {
    isOpen.value = false
})
</script>

<template>
    <!-- Select Container -->
    <div class="relative w-full" @keydown.esc="isOpen = false">
        <!-- Select Button -->
        <button
            type="button"
            class="relative w-full min-h-16 px-4 py-2 bg-white text-left border rounded-lg shadow-sm outline-none transition-all duration-200
            hover:border-gray-300
            focus:ring-1 focus:ring-green-200
            disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed"
            :class="{'border-gray-200': !isOpen,}"
            @click="toggle"
            :disabled="disabled"
        >
        <span class="block truncate" :class="{ 'text-gray-500': !modelValue }">
        {{ displayValue }}
        </span>

            <!-- Chevron Icon -->
            <span class="absolute inset-y-0 right-0 flex items-center  pr-2 pointer-events-none">
                <svg
                    class="w-4 h-4 text-gray-400 transition-transform duration-200"
                    :class="{ 'rotate-180': isOpen }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
          </span>
        </button>

        <!-- Options Dropdown -->
        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <ul
                v-if="isOpen"
                class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-auto focus:outline-none py-1"
            >
                <li
                    v-for="option in options"
                    :key="option.value"
                    @click="selectOption(option)"
                    class="relative px-4 py-2 cursor-pointer select-none transition-colors"
                    :class="{
                    'bg-green-50 text-green-600': modelValue === option.value,
                    'text-gray-900 hover:bg-gray-50': modelValue !== option.value
                    }"
                >
                    {{ option.label }}
                </li>
            </ul>
        </transition>
    </div>
</template>
