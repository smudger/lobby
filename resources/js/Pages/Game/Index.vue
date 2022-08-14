<script setup>
import { MailIcon, PhoneIcon } from "@heroicons/vue/solid";

const people = [
    {
        name: "AFAGTNY",
        description:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
        tags: ["Deception", "Drawing", "Party"],
        imageUrl:
            "https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=60",
    },
    {
        name: "AFAGTNY",
        description:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
        tags: ["Deception", "Drawing", "Party"],
        imageUrl:
            "https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=60",
    },
    {
        name: "AFAGTNY",
        description:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
        tags: ["Deception", "Drawing", "Party"],
        imageUrl:
            "https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=60",
    },
    {
        name: "AFAGTNY",
        description:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
        tags: ["Deception", "Drawing", "Party"],
        imageUrl:
            "https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=60",
    },
    {
        name: "AFAGTNY",
        description:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
        tags: ["Deception", "Drawing", "Party"],
        imageUrl:
            "https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=60",
    },
];
</script>

<template>
    <Head title="Games" />

    <div class="m-6">
        <h1 class="text-2xl font-bold text-gray-900">Games Library</h1>

        <p class="text-sm font-light text-gray-500 mb-6">
            Browse through the selection of games on offer, and find something
            fun to play.
        </p>

        <div class="bg-gray-200 overflow-hidden rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <ul
                    role="list"
                    class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                >
                    <li
                        v-for="person in people"
                        :key="person.email"
                        class="col-span-1 flex flex-col text-center bg-white rounded-lg shadow divide-y divide-gray-200"
                    >
                        <div class="flex-1 flex flex-col p-8">
                            <img
                                class="w-32 h-32 flex-shrink-0 mx-auto rounded-full"
                                :src="person.imageUrl"
                                alt=""
                            />
                            <h3 class="mt-6 text-gray-900 text-sm font-medium">
                                {{ person.name }}
                            </h3>
                            <dl
                                class="mt-1 flex-grow flex flex-col justify-between"
                            >
                                <dt class="sr-only">Title</dt>
                                <dd class="text-gray-500 text-sm">
                                    {{ person.description }}
                                </dd>
                                <dt class="sr-only">Tags</dt>
                                <dd class="mt-3 space-x-2">
                                    <span
                                        v-for="(tag, index) in person.tags"
                                        :key="index"
                                        class="px-2 py-1 text-green-800 text-xs font-medium bg-green-100 rounded-full"
                                        >{{ tag }}</span
                                    >
                                </dd>
                            </dl>
                        </div>
                        <div>
                            <div class="-mt-px flex divide-x divide-gray-200">
                                <div class="w-0 flex-1 flex">
                                    <a
                                        :href="`mailto:${person.email}`"
                                        class="relative -mr-px w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-gray-700 font-medium border border-transparent rounded-bl-lg hover:text-gray-500"
                                    >
                                        <MailIcon
                                            class="w-5 h-5 text-gray-400"
                                            aria-hidden="true"
                                        />
                                        <span class="ml-3">Email</span>
                                    </a>
                                </div>
                                <div class="-ml-px w-0 flex-1 flex">
                                    <a
                                        :href="`tel:${person.telephone}`"
                                        class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-gray-700 font-medium border border-transparent rounded-br-lg hover:text-gray-500"
                                    >
                                        <PhoneIcon
                                            class="w-5 h-5 text-gray-400"
                                            aria-hidden="true"
                                        />
                                        <span class="ml-3">Call</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
