<script setup>
import {ref, computed, onMounted, onUnmounted, nextTick} from 'vue'

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: ''
    },
    options: {
        type: Array,
        required: true,
    },
    optionLabel: {
        type: String,
        default: null
    },
    optionValue: {
        type: String,
        default: null
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

const isOpen = ref(false)
const toggle = async () => {
    if (!props.disabled) {
        isOpen.value = !isOpen.value

        if (isOpen.value) {
            // Need to wait for the DOM to update before checking position
            await nextTick()
            checkDropdownPosition()
        }
    }
}

// Computed
const displayValue = computed(() => {
    const selectedOption = props.options.find(option => {
        if (!props.optionValue) {
            return option === props.modelValue
        }

        return option[props.optionValue] === props.modelValue
    })
    if(!selectedOption) {
        return props.placeholder
    }
    if (!props.optionLabel) {
        return selectedOption ?? props.placeholder
    }
    return selectedOption[props.optionLabel];
})


const selectOption = (option) => {
    let value = option
    if (props.optionValue) {
        value = option[props.optionValue]
    }
    emit('update:modelValue', value)
    emit('change', value)
    isOpen.value = false
}

const selectRef = ref(null)
const dropdownRef = ref(null)
const openUpward = ref(false)
const checkDropdownPosition = () => {
    if (!selectRef.value || !dropdownRef.value) return

    const selectRect = selectRef.value.getBoundingClientRect()
    const dropdownHeight = dropdownRef.value.offsetHeight
    const viewportHeight = window.innerHeight

    // Check if there's enough space below
    const spaceBelow = viewportHeight - selectRect.bottom
    openUpward.value = spaceBelow < dropdownHeight && selectRect.top > dropdownHeight
}
// Listen for window resize to recheck position
const handleResize = () => {
    if (isOpen.value) {
        checkDropdownPosition()
    }
}

const handleOutsideClick = (event) => {
    if (selectRef.value && !selectRef.value.contains(event.target)) {
        isOpen.value = false
    }
}

onMounted(() => {
    document.addEventListener('click', handleOutsideClick)
    window.addEventListener('resize', handleResize)
    window.addEventListener('scroll', handleResize, true)
})

onUnmounted(() => {
    document.removeEventListener('click', handleOutsideClick)
    window.removeEventListener('resize', handleResize)
    window.removeEventListener('scroll', handleResize, true)
})
</script>

<template>
    <!-- Select Container -->
    <div class="relative w-full" @keydown.esc="isOpen = false" ref="selectRef">
        <!-- Select Button -->
        <button
            type="button"
            class="relative w-full min-h-12 px-4 py-2 bg-white text-left border rounded-lg shadow-sm outline-none transition-all duration-200
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
                ref="dropdownRef"
                :class="[openUpward ? 'mb-1 bottom-full' : 'mt-1 top-full']"
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
                    {{ optionLabel ? option[optionLabel] : option }}
                </li>
            </ul>
        </transition>
    </div>
</template>
