<template>
    <ModalWindow :showing="open">
        <div class="text-lg font-semibold mb-8">
            Модерация обращения
        </div>
        <div class="space-y-4">
            <div>
                <div class="text-sm text-gray-500 mb-0.5">Имя пользователя:</div>
                <div class="font-medium text-gray-600">
                    {{ticket.name}}
                </div>
            </div>
            <div>
                <div class="text-sm text-gray-500 mb-0.5">Email пользователя:</div>
                <div class="font-medium text-gray-600">
                    {{ticket.email}}
                </div>
            </div>
            <div>
                <div class="text-sm text-gray-500 mb-0.5">Вопрос:</div>
                <div class="font-medium text-gray-600">
                    {{ticket.message}}
                </div>
            </div>

            <div>
                <div class="text-sm text-gray-500 mb-0.5">Статус:</div>
                <div v-if="ticket.status === 'active'" class="text-yellow-700">
                    Требует модерации
                </div>
                <div v-if="ticket.status === 'resolved'" class="text-green-700">
                    Проверен
                </div>
            </div>

            <div v-if="ticket.status === 'active'">
                <FormGroup label="Комментарий модератора">
                    <TextareaInput v-model="editTicket.comment"/>
                </FormGroup>
            </div>
            <div v-if="ticket.status === 'resolved'">
                <div class="text-sm text-gray-500 mb-0.5">Вопрос:</div>
                <div class="font-medium text-gray-600">
                    {{ticket.comment}}
                </div>
            </div>
        </div>
        <div class="mt-6">
            <button @click="approveTicket"
                    class="border-2 border-blue-500 px-4 py-2 rounded-xl shadow-sm font-semibold text-blue-600 hover:bg-blue-500 hover:text-white duration-150
                    disabled:bg-gray-200 disabled:border-gray-200 disabled:cursor-not-allowed disabled:text-gray-500"
                    :disabled="ticket.status === 'resolved'"
            >
                {{ this.btnText }}
            </button>
        </div>

        <div class="mt-4 h-4">
            <div v-if="$page.props.errors.resolve" class="text-red-500 text-sm font-medium">
                {{$page.props.errors.resolve}}
            </div>
        </div>
    </ModalWindow>
</template>

<script>
import ModalWindow from "@/Components/ModalWindow.vue";
import FormGroup from "@/Components/Form/FormGroup.vue";
import TextareaInput from "@/Components/Form/TextareaInput.vue";
export default {
    name: "EditModal",
    components: {TextareaInput, FormGroup, ModalWindow},
    props: {
        open: {
            type: Boolean,
            default: false,
        },
        ticket: {
            type: Object,
            required: true,
        }
    },
    data() {
        return {
            editTicket: this.$inertia.form({
                comment: '',
            })
        }
    },
    methods: {
        approveTicket() {
            this.editTicket.put(route('requests.resolve', this.ticket.id));
        }
    },
    computed: {
        btnText() {
            if (this.ticket.status === 'active') {
                return 'Ответить на обращение';
            }
            if (this.ticket.status === 'resolved') {
                return 'Обращение уже закрыто';
            }
        }
    }
}
</script>
