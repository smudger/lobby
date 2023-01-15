<script setup>
import { onMounted, onBeforeUnmount } from "vue";
defineProps({
    lobby: Object,
    game_url: String,
});

const receiveMessage = (event) => {
    console.log(event.data);
};

onMounted(() => {
    window.addEventListener("message", receiveMessage);
});
onBeforeUnmount(() => {
    window.removeEventListener("message", receiveMessage);
});
</script>

<template>
    <Head title="Iframe" />

    <div class="m-6 h-full flex items-center justify-center">
        <div class="max-w-lg mx-auto">
            <div class="text-center flex flex-col items-center">
                <h1>Iframe</h1>
                <iframe
                    src="/games/walk-the-dog/index.html"
                    title="Walk The Dog"
                    class="border-none overflow-hidden"
                    scrolling="no"
                    width="600"
                    height="600"
                ></iframe>
            </div>
        </div>
    </div>
</template>
