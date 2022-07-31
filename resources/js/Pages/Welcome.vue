<template>
    <Head title="Welcome" />

    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-sm text-gray-700 underline">
                Dashboard
            </Link>

            <template v-else>
                <Link :href="route('login')" class="text-sm text-gray-700 underline">
                    Log in
                </Link>

                <Link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-700 underline">
                    Register
                </Link>
            </template>
        </div>

        <div class="max-w-screen-lg mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-4">
                <form>
                    <FormGroup label="Ваше имя" :error="this.ticket.errors.name">
                        <TextInput v-model="ticket.name" :is-error="Boolean(this.ticket.errors.name)"/>
                    </FormGroup>
                    <FormGroup label="Ваш email" :error="this.ticket.errors.email">
                        <TextInput v-model="ticket.email" :is-error="Boolean(this.ticket.errors.email)" />
                    </FormGroup>
                    <FormGroup label="Текст обращения" :error="this.ticket.errors.message">
                        <TextareaInput v-model="ticket.message" :is-error="Boolean(this.ticket.errors.message)"/>
                    </FormGroup>

                    <button @click="sendTicket" type="button" class="bg-blue-500 text-white rounded-lg shadow-md font-medium px-4 py-1.5 hover:bg-blue-600 duration-150">
                        Отправить обращение
                    </button>
                </form>

                <div v-if="$page.props.flash.success" class="mt-6 text-green-800 bg-green-100 px-1.5 py-0.5 rounded-full">
                    {{ $page.props.flash.success }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import FormGroup from "@/Components/Form/FormGroup.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import TextareaInput from "@/Components/Form/TextareaInput.vue";

export default {
    name: 'Welcome',
    components: {TextareaInput, TextInput, FormGroup, Head, Link},
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
    },
    data() {
        return {
            ticket: this.$inertia.form({
                name: '',
                email: '',
                message: '',
            })
        }
    },
    methods: {
        sendTicket() {
            this.ticket.post(route('requests.store'), {
                onSuccess: () => this.ticket.reset(),
            });
        }
    }
}
</script>
