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
                <div class="flex space-x-2 mb-8">
                    <select v-model="filter.status" class="bg-white border-gray-300 text-sm shadow-sm rounded-lg">
                        <option value="all">Все заявки</option>
                        <option value="active">Только активные</option>
                        <option value="resolved">Только завершенные</option>
                    </select>
                    <select v-model="filter.date" class="bg-white border-gray-300 text-sm shadow-sm rounded-lg">
                        <option value="asc">Сначала новые</option>
                        <option value="desc">Сначала старые</option>
                    </select>
                </div>
                    <div>
                        <div class="space-y-4">
                            <TransitionGroup
                                enter-active-class="transition ease-out duration-300"
                                enter-from-class="opacity-0 translate-y-95"
                                enter-to-class="opacity-100 translate-y-100"
                                leave-active-class="transition ease-in duration-300"
                                leave-from-class="opacity-100 translate-y-100"
                                leave-to-class="opacity-0 translate-y-95"
                                appear>
                                    <TicketCard v-for="ticket in tickets.data" :key="ticket.id" :ticket="ticket"/>
                                </TransitionGroup>
                        </div>
                        <pagination :links="tickets.meta.links" />
                    </div>

            </div>
        </div>
    </BreezeAuthenticatedLayout>

</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import {Inertia} from "@inertiajs/inertia";
import {Head} from "@inertiajs/inertia-vue3";
import ModalWindow from "@/Components/ModalWindow.vue";
import EditModal from "@/Components/Ticket/EditModal.vue";
import TicketCard from "@/Components/Ticket/TicketCard.vue";
import Pagination from "@/Components/Pagination.vue";
export default {
    name: "Tickets",
    components: {TicketCard, EditModal, BreezeAuthenticatedLayout, Head, ModalWindow, Pagination},
    props: {
        tickets: {
            data: {
                type: Array,
            },
        }
    },
    data() {
        return {
            isEditModalOpen: false,
            editableTicket: null,
            filter: {
                status: 'all',
                date: 'asc'
            },
        }
    },
    methods: {
        openTicket: function (ticket) {
            this.isEditModalOpen = true;
            this.editableTicket = ticket;
        }
    },
    computed: {
        filterQueryData() {
            let filter = {};
            if (this.filter.status !== 'all') {
                filter.status = this.filter.status;
            }
            if (this.filter.date !== 'asc') {
                filter.date = this.filter.date;
            }
            return filter;
        }
    },
    watch: {
        filter: {
            handler() {
                Inertia.visit(route('requests.index'), {
                    only: ['tickets'],
                    data: this.filterQueryData,
                    preserveState: true,
                })
            },
            deep: true,
        }
    }
}
</script>
