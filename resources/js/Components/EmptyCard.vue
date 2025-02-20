<script setup>
import {DotLottieVue} from "@lottiefiles/dotlottie-vue";

</script>

<template>
    <div class="flex flex-col gap-6 justify-center">
        <h1 class="font-bold text-2xl leading-tight text-green-700 text-center">Oh, Looks like there is nothing to see</h1>
        <div>
            <DotLottieVue style="height: 300px; width: 300px" autoplay loop src="/images/notfound_animation.lottie" class="mx-auto" />
        </div>
    </div>
</template>

<style scoped>

</style>
