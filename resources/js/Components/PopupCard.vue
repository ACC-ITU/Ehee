<script setup>
import {computed} from 'vue';

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false
    },
    showCloseButton: {
        type: Boolean,
        default: true
    },
    closeOnBackdropClick: {
        type: Boolean,
        default: true
    },
    closeOnEsc: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['update:modelValue']);

const isOpen = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
});

const close = () => {
    emit('update:modelValue', false);
};


// Export methods for external use
defineExpose({
    open: () => {
        isOpen.value = true;
    },
    close
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <div
                v-if="isOpen"
                class="fixed inset-0 z-50 overflow-y-auto"
                aria-modal="true"
                role="dialog"
                @click="close()"
            >
                <div class="flex min-h-screen items-center justify-center p-4">
                    <!-- Backdrop -->
                    <div class="fixed inset-0 bg-black bg-opacity-25"
                         @click="closeOnBackdropClick ? close() : null"></div>

                    <!-- Popup Content -->
                    <div
                        class="relative z-10 rounded-lg bg-white p-6 shadow-xl max-w-md w-full"
                        @click.stop
                    >
                        <!-- Close button -->
                        <button
                            v-if="showCloseButton"
                            @click="close"
                            class="absolute top-3 right-3 text-gray-400 hover:text-gray-500"
                            aria-label="Close"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>

                        <div>
                            <slot></slot>
                        </div>

                        <!-- Footer -->
                        <div v-if="$slots.footer" class="mt-6 flex justify-end">
                            <slot name="footer"></slot>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
