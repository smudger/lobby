<template>
    <Head title="Home" />

    <div class="flex flex-col justify-center min-h-screen bg-indigo-700">
        <div
            class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8"
        >
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span class="block">Welcome to the Thunderdome!</span>
            </h2>
            <p class="mt-4 text-lg leading-6 text-indigo-200">
                Gather your friends, jump into a lobby, and start playing
                together right away. Couch co-op meet the internet age.
            </p>
            <div class="inline-flex">
                <Link
                    method="post"
                    as="button"
                    type="button"
                    href="/lobbies"
                    class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-100 sm:w-auto"
                >
                    Create Lobby
                </Link>
            </div>
            <div class="ml-3 inline-flex">
                <Link
                    href="/lobbies/AGXB/members"
                    method="post"
                    as="button"
                    type="button"
                    class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 sm:w-auto"
                >
                    Join Lobby
                </Link>
            </div>
        </div>
    </div>
</template>
