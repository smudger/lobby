<script setup>
import { onMounted, ref } from "vue";

defineProps({
    lobby: Object,
    game: Object,
});

const frame = ref(null);

onMounted(() => {
    frame.value.focus();
});
</script>

<template>
    <Head :title="game.name" />

    <div class="m-6 h-full flex items-center justify-center">
        <div class="mx-auto">
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <div class="px-4 py-5 sm:p-6">
                    <iframe
                        ref="frame"
                        :title="game.name"
                        :src="`/games/${game.slug}/index.html`"
                        scrolling="no"
                        class="overflow-hidden"
                        height="600"
                        width="600"
                        tabindex="0"
                    >
                        Your browser does not support iframes.
                    </iframe>
                </div>
                <div class="px-4 py-4 sm:px-6">
                    <h2 class="mt-2 text-xl font-medium text-gray-900">
                        {{ game.name }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ game.description }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
