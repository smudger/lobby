<script setup>
defineProps({
    lobby: Object,
});
</script>

<template>
    <Head :title="`Lobby ${lobby.id}`" />

    <div class="m-6 h-full flex items-center justify-center">
        <div class="max-w-lg mx-auto">
            <div class="text-center flex flex-col items-center">
                <svg
                    class="mx-auto h-16 w-16 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 48 48"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A10.003 10.003 0 0124 26c4.21 0 7.813 2.602 9.288 6.286M30 14a6 6 0 11-12 0 6 6 0 0112 0zm12 6a4 4 0 11-8 0 4 4 0 018 0zm-28 0a4 4 0 11-8 0 4 4 0 018 0z"
                    />
                </svg>
                <h2 class="mt-2 text-xl font-medium text-gray-900">
                    Welcome to the lobby
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Send the lobby code below to your friends so they can join
                    you.
                </p>

                <div class="relative mt-6">
                    <p
                        class="bg-white text-4xl tracking-widest text-gray-900 p-4 border border-dashed font-mono"
                    >
                        {{ lobby.id }}
                    </p>
                    <button
                        type="button"
                        class="absolute -right-20 inset-y-4 flex-shrink-0 px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Copy
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
