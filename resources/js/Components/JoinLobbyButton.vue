<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    DialogDescription,
} from "@headlessui/vue";

const isOpen = ref(false);

function setIsOpen(value) {
    isOpen.value = value;
}

const form = useForm({
    code: null,
    name: null,
});
</script>

<template>
    <button
        type="button"
        class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 sm:w-auto"
        @click="() => setIsOpen(true)"
    >
        Join Lobby
    </button>

    <Dialog :open="isOpen" class="relative z-50" @close="setIsOpen">
        <DialogPanel
            class="fixed inset-0 bg-indigo-700 flex flex-col items-center justify-center p-4"
        >
            <div class="max-w-2xl">
                <DialogTitle
                    class="text-3xl font-extrabold text-white sm:text-4xl"
                    >Join an existing lobby</DialogTitle
                >
                <DialogDescription
                    class="mt-4 text-lg leading-6 text-indigo-200"
                >
                    Enter the unique 4-digit lobby code and make a name for
                    yourself.
                </DialogDescription>

                <form
                    class="mt-10"
                    @submit.prevent="
                        form.transform(({ name }) => ({ name })).post(
                            `/lobbies/${form.code}/members`
                        )
                    "
                >
                    <div>
                        <label
                            for="code"
                            class="block text-sm font-medium text-indigo-200"
                            >Lobby Code</label
                        >
                        <div class="mt-1">
                            <input
                                id="code"
                                v-model="form.code"
                                type="text"
                                name="code"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                placeholder="XXXX"
                            />
                        </div>
                    </div>

                    <div class="mt-4">
                        <label
                            for="name"
                            class="block text-sm font-medium text-indigo-200"
                            >Name</label
                        >
                        <div class="mt-1">
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                name="name"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                placeholder="Master Chief"
                            />
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-100 sm:w-auto"
                            :disabled="form.processing"
                        >
                            Join Lobby
                        </button>
                    </div>
                </form>
            </div>
            <div class="absolute top-0 left-0 pt-4 pl-4">
                <button
                    type="button"
                    class="rounded-full text-2xl text-indigo-200 hover:text-indigo-400 focus:outline-none border-2 border-transparent focus:border-indigo-200 px-1"
                    @click="() => setIsOpen(false)"
                >
                    <span class="sr-only">Close</span>
                    <span aria-hidden="true">&larr;</span>
                </button>
            </div>
        </DialogPanel>
    </Dialog>
</template>
