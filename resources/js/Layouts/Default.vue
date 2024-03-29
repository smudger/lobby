<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import {
    HomeIcon,
    MenuAlt2Icon,
    RssIcon,
    UserGroupIcon,
    ViewGridIcon,
    XIcon,
} from "@heroicons/vue/outline";
import { LogoutIcon } from "@heroicons/vue/solid";

const lobbyId = computed(() => usePage().props.lobby?.id);

const sidebarNavigation = computed(() => [
    {
        name: "Home",
        href: `/lobbies/${lobbyId.value}`,
        icon: HomeIcon,
        isCurrent: usePage().component === "Lobby/Show",
    },
    {
        name: "Games",
        href: `/lobbies/${lobbyId.value}/games`,
        icon: ViewGridIcon,
        isCurrent: usePage().component.startsWith("Game"),
    },
    {
        name: "Members",
        href: `/lobbies/${lobbyId.value}/members`,
        icon: UserGroupIcon,
        isCurrent: usePage().component === "Member/Index",
    },
    {
        name: "Feed",
        href: `/lobbies/${lobbyId.value}/feed`,
        icon: RssIcon,
        isCurrent: usePage().component === "Feed/Index",
    },
]);

const mobileMenuOpen = ref(false);
</script>

<template>
    <div class="h-full flex">
        <!-- Narrow sidebar -->
        <div class="hidden w-28 bg-indigo-700 overflow-y-auto md:block">
            <div class="w-full py-6 flex flex-col items-center">
                <div class="flex-shrink-0 flex items-center">
                    <img
                        class="h-8 w-auto"
                        src="https://tailwindui.com/img/logos/workflow-mark.svg?color=white"
                        alt="Workflow"
                    />
                </div>
                <div class="flex-1 mt-6 w-full px-2 space-y-1">
                    <Link
                        v-for="item in sidebarNavigation"
                        :key="item.name"
                        :href="item.href"
                        :class="[
                            item.isCurrent
                                ? 'bg-indigo-800 text-white'
                                : 'text-indigo-100 hover:bg-indigo-800 hover:text-white',
                            'group w-full p-3 rounded-md flex flex-col items-center text-xs font-medium',
                        ]"
                        :aria-current="item.isCurrent ? 'page' : undefined"
                    >
                        <component
                            :is="item.icon"
                            :class="[
                                item.isCurrent
                                    ? 'text-white'
                                    : 'text-indigo-300 group-hover:text-white',
                                'h-6 w-6',
                            ]"
                            aria-hidden="true"
                        />
                        <span class="mt-2">{{ item.name }}</span>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <TransitionRoot as="template" :show="mobileMenuOpen">
            <Dialog
                as="div"
                class="relative z-20 md:hidden"
                @close="mobileMenuOpen = false"
            >
                <TransitionChild
                    as="template"
                    enter="transition-opacity ease-linear duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-300"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-75" />
                </TransitionChild>

                <div class="fixed inset-0 z-40 flex">
                    <TransitionChild
                        as="template"
                        enter="transition ease-in-out duration-300 transform"
                        enter-from="-translate-x-full"
                        enter-to="translate-x-0"
                        leave="transition ease-in-out duration-300 transform"
                        leave-from="translate-x-0"
                        leave-to="-translate-x-full"
                    >
                        <DialogPanel
                            class="relative max-w-xs w-full bg-indigo-700 pt-5 pb-4 flex-1 flex flex-col"
                        >
                            <TransitionChild
                                as="template"
                                enter="ease-in-out duration-300"
                                enter-from="opacity-0"
                                enter-to="opacity-100"
                                leave="ease-in-out duration-300"
                                leave-from="opacity-100"
                                leave-to="opacity-0"
                            >
                                <div class="absolute top-1 right-0 -mr-14 p-1">
                                    <button
                                        type="button"
                                        class="h-12 w-12 rounded-full flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-white"
                                        @click="mobileMenuOpen = false"
                                    >
                                        <XIcon
                                            class="h-6 w-6 text-white"
                                            aria-hidden="true"
                                        />
                                        <span class="sr-only"
                                            >Close sidebar</span
                                        >
                                    </button>
                                </div>
                            </TransitionChild>
                            <div class="flex-shrink-0 px-4 flex items-center">
                                <img
                                    class="h-8 w-auto"
                                    src="https://tailwindui.com/img/logos/workflow-mark.svg?color=white"
                                    alt="Workflow"
                                />
                            </div>
                            <div class="mt-5 flex-1 h-0 px-2 overflow-y-auto">
                                <nav class="h-full flex flex-col">
                                    <div class="space-y-1">
                                        <a
                                            v-for="item in sidebarNavigation"
                                            :key="item.name"
                                            :href="item.href"
                                            :class="[
                                                item.isCurrent
                                                    ? 'bg-indigo-800 text-white'
                                                    : 'text-indigo-100 hover:bg-indigo-800 hover:text-white',
                                                'group py-2 px-3 rounded-md flex items-center text-sm font-medium',
                                            ]"
                                            :aria-current="
                                                item.isCurrent
                                                    ? 'page'
                                                    : undefined
                                            "
                                        >
                                            <component
                                                :is="item.icon"
                                                :class="[
                                                    item.isCurrent
                                                        ? 'text-white'
                                                        : 'text-indigo-300 group-hover:text-white',
                                                    'mr-3 h-6 w-6',
                                                ]"
                                                aria-hidden="true"
                                            />
                                            <span>{{ item.name }}</span>
                                        </a>
                                    </div>
                                </nav>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                    <div class="flex-shrink-0 w-14" aria-hidden="true">
                        <!-- Dummy element to force sidebar to shrink to fit close icon -->
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Content area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="w-full">
                <div
                    class="relative z-10 flex-shrink-0 h-16 bg-white border-b border-gray-200 shadow-sm flex"
                >
                    <button
                        type="button"
                        class="border-r border-gray-200 px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden"
                        @click="mobileMenuOpen = true"
                    >
                        <span class="sr-only">Open sidebar</span>
                        <MenuAlt2Icon class="h-6 w-6" aria-hidden="true" />
                    </button>
                    <div class="flex-1 flex justify-between px-4 sm:px-6">
                        <h1
                            class="text-gray-800 text-2xl flex flex-1 items-center"
                        >
                            <span class="text-3xl text-indigo-300 mr-2">#</span>
                            <span class="font-mono">{{ lobbyId }}</span>
                        </h1>
                        <div
                            class="ml-2 flex items-center space-x-4 sm:ml-6 sm:space-x-6"
                        >
                            <Link
                                href="/members/me"
                                method="delete"
                                as="button"
                                type="button"
                                class="flex text-indigo-600 p-1 rounded-full items-center justify-center text-white border-2 border-transparent focus:outline-none focus:border-current hover:text-indigo-500"
                            >
                                <LogoutIcon
                                    class="h-7 w-7"
                                    aria-hidden="true"
                                />
                                <span class="sr-only">Leave Lobby</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content -->
            <div class="flex-1 flex items-stretch overflow-hidden">
                <main class="flex-1 overflow-y-auto">
                    <!-- Primary column -->
                    <section
                        aria-labelledby="primary-heading"
                        class="min-w-0 flex-1 h-full flex flex-col lg:order-last"
                    >
                        <h1 id="primary-heading" class="sr-only">
                            {{
                                sidebarNavigation.filter(
                                    ({ isCurrent }) => isCurrent
                                )[0].name
                            }}
                        </h1>
                        <slot></slot>
                    </section>
                </main>
            </div>
        </div>
    </div>
</template>
