<script>
export default {
    layout: null,
};
</script>

<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { ExclamationCircleIcon } from "@heroicons/vue/solid";

const form = useForm({
    member_name: null,
});
</script>

<template>
    <div
        class="fixed inset-0 bg-indigo-700 flex flex-col items-center justify-center p-4"
    >
        <div class="max-w-2xl">
            <h1 class="text-3xl font-extrabold text-white sm:text-4xl">
                Create a new lobby
            </h1>
            <p class="mt-4 text-lg leading-6 text-indigo-200">
                Just let us know what you'd like others to call you.
            </p>

            <form class="mt-8" @submit.prevent="form.post('/lobbies')">
                <div class="mt-4">
                    <label
                        for="name"
                        class="block text-sm font-medium text-indigo-200"
                        >Your Name</label
                    >
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input
                            id="name"
                            v-model="form.member_name"
                            autofocus
                            type="text"
                            name="name"
                            class="block w-full pr-10 focus:outline-none sm:text-sm rounded-md"
                            :class="{
                                'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500':
                                    form.errors.member_name,
                            }"
                            placeholder="Master Chief"
                            :aria-invalid="form.errors.member_name"
                            aria-describedby="name-error"
                            required
                            @keyup="() => form.clearErrors('member_name')"
                        />
                        <div
                            v-if="form.errors.member_name"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                        >
                            <ExclamationCircleIcon
                                class="h-5 w-5 text-red-500"
                                aria-hidden="true"
                            />
                        </div>
                    </div>
                    <p
                        v-if="form.errors.member_name"
                        id="name-error"
                        class="mt-2 text-sm font-semibold text-red-600"
                    >
                        {{ form.errors.member_name }}
                    </p>
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white disabled:bg-indigo-100 hover:bg-indigo-100 sm:w-auto"
                        :disabled="form.processing"
                    >
                        Create Lobby
                    </button>
                </div>
            </form>
        </div>
        <div class="absolute top-0 left-0 pt-4 pl-4">
            <Link
                href="/"
                class="rounded-full text-2xl text-indigo-200 hover:text-indigo-400 focus:outline-none border-2 border-transparent focus:border-indigo-200 px-1"
            >
                <span class="sr-only">Home</span>
                <span aria-hidden="true">&larr;</span>
            </Link>
        </div>
    </div>
</template>
