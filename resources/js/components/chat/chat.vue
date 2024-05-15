<template>
    <div class="row">
        <!-- threads -->
        <div class="col-lg-3">

        </div>

        <!-- messages -->
        <div class="col-lg-6">

            <div v-for="message in messages" :key="message.id">
                <strong>{{ message.Sender }}:</strong> {{ message.Message }}
            </div>
            <div class="chat-box p-2">
                <input class="chat-input" v-model="newMessage" @keyup.enter="sendMessage">
            </div>
        </div>
        
        <!-- threads -->
        <div class="col-lg-3">

        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            messages: [],
            newMessage: ''
        };
    },
    methods: {
        fetchMessages() {
            axios.get('/messages/get-message-thread')
            .then(response => {
                this.messages = response.data;
            }).catch(error => {
                console.log(error)
            })
        },
        sendMessage() {
            axios.post(`${ axios.defaults.baseURL }/messages/store-messages`, {
                Message: this.newMessage
            }).then(response => {
                this.newMessage = '';
            }).catch(error => {
                console.log(error)
            })
        }
    },
    mounted() {
        this.fetchMessages();
        Echo.channel('chat')
            .listen('MessageSent', (e) => {
                console.log(e)
                this.messages.push(e.message);
            });
    },
};
</script>