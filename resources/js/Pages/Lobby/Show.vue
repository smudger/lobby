<script setup>
defineProps({
    lobby: Object,
});
</script>

<template>
    <Head :title="`Lobby ${lobby.id}`" />

    <h1>Home Page</h1>
</template>
