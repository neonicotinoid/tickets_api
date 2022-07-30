<template>
    <Head title="Список заявок"/>
    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Список заявок
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div>
                        <div class="space-y-4">
                            <div class="p-3 bg-white rounded-lg shadow flex" v-for="ticket in tickets" :key="ticket.id">
                                <div class="mr-4">
                                    <div>
                                        {{ ticket.message}}
                                    </div>
                                    <div class="flex space-x-2 mt-2">
                                        <div class="text-sm font-medium text-gray-600">
                                            <span class="font-semibold">Имя:</span>
                                            <span class="ml-1">{{ ticket.name }}</span>
                                        </div>
                                        <div class="text-sm font-medium text-gray-600">
                                            <span class="font-semibold">Дата:</span>
                                            <span class="ml-1">{{ ticket.created_at }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-auto">
                                    <button class="bg-blue-500 rounded-lg text-white text-sm px-3 py-1 shadow-sm hover:bg-blue-600">
                                        Ответить
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import {Head} from "@inertiajs/inertia-vue3";
export default {
    name: "Tickets",
    components: {BreezeAuthenticatedLayout, Head},
    props: {
        tickets: {
            type: Array,
        }
    }
}
</script>
