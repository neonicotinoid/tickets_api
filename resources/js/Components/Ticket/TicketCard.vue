<template>
    <div class="p-3 bg-white rounded-lg shadow flex duration-150" :key="ticket.id">
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
                <div class="text-sm font-medium">
                    <span v-if="ticket.status === 'active'" class="text-yellow-500">На модерации</span>
                    <span v-if="ticket.status === 'resolved'" class="text-green-500">Завершенные</span>
                </div>
            </div>
        </div>
        <div class="ml-auto">
            <button @click="isEditModalOpen = true" class="bg-blue-500 rounded-lg text-white text-sm px-3 py-1 shadow-sm hover:bg-blue-600">
                Ответить
            </button>
        </div>
    <EditModal v-if="isEditModalOpen" :open="isEditModalOpen" :ticket="ticket" @close="isEditModalOpen = false"/>
    </div>

</template>

<script>
import EditModal from "@/Components/Ticket/EditModal.vue";
export default {
    name: "TicketCard",
    components: {EditModal},
    props: {
        ticket: {
            type: Object,
            required: true,
        }
    },
    data() {
        return {
            isEditModalOpen: false,
        }
    }
}
</script>
