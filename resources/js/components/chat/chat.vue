<template>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-lg-12">
                <div class="row">
                    <!-- create -->
                    <div class="col-lg-3">
                        <div class="chat-select mb-3" style="min-height: 8vh; height: 8vh; max-height: 8vh;">
                            <select v-model="targetUser" @change="selectTargeFromDropdown(targetUser)" 
                                class="custom-select select2"
                                style="border-radius: 14px !important; border: none; padding: 12px 20px !important; min-height: 42px !important;">
                                <option value=""></option>
                                <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="chat-select mb-3" style="min-height: 8vh; height: 8vh; max-height: 8vh;" v-if="isNull(targetUser) ? false : true">
                            <img id="prof-img" style="width: 36px !important;" class="img-fluid img-circle" :src="imgsPath + 'prof-img.png'" alt="User profile picture">
                            <h4 class="no-pads px-2">{{ targetName }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-12">
                <div class="row">
                    <!-- threads -->
                    <div class="col-lg-3">
                        <!-- header threads -->
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless">
                                <tbody>
                                    <tr v-for="headers in headerThreads" :key="headers.id" style="cursor: pointer;" @click="selectTargeFromThreadList(headers.Receiver, headers.name)">
                                        <td>
                                            <span :class="headers.Status === 'Unread' ? 'text-bold' : ''">{{ headers.name }}</span>
                                            <br>
                                            <p :class="headers.Status === 'Unread' ? 'text-bold' : ''" class="text-muted no-pads ellipsize-1">{{ headers.LatestMessage }}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
            
                    <!-- messages -->
                    <div class="col-lg-9">
                        <div class="chat-container">
                            <div ref="chatMessages" class="chat-messages" style="height: 72vh;">
                                <button v-if="showLoadMore" @click="loadMoreMessages" class="load-more-button">
                                    Load More Messages
                                </button>
                                <div class="bubble-container" v-for="(message, index) in displayedMessages" :key="index">
                                    <p class="text-muted no-pads text-xs text-center" v-if="showTime(message.created_at, displayedMessages[index-1])">{{ moment(message.created_at).format("ddd, MMM DD, YYYY HH:mm A") }}</p>
                                    <div :class="userId===message.Sender ? 'sender-bubble' : 'receiver-bubble' ">
                                        <div>
                                            <span>{{ message.Message }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="chat-input" v-if="isNull(targetUser) ? false : true">
                                <div class="chat-input-form">
                                    <input placeholder="Compose..." class="" v-model="newMessage" @keyup.enter="sendMessage">
                                    <button @click="sendMessage"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import FlatPickr from 'vue-flatpickr-component';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
    components : {
        FlatPickr,
        Swal,
        'pagination' : Bootstrap4Pagination,
    },
    data() {
        return {
            moment : moment,
            baseURL : axios.defaults.baseURL,
            filePath : axios.defaults.filePath,
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            userId : document.querySelector("meta[name='user-id']").getAttribute('content'),
            imgsPath : axios.defaults.imgsPath,
            messages: [],
            displayedMessages: [],
            newMessage: '',
            messagesPerPage: 15, // Number of messages to load per page
            currentPage: 0,
            headerThreads : [],
            users : [],
            targetUser : null,
            targetName : null,
        };
    },
    computed: {
        showLoadMore() {
            return this.messages.length > this.displayedMessages.length;
        },
    },
    methods: {
        isNull (item) {
            if (jquery.isEmptyObject(item)) {
                return true;
            } else {
                return false;
            }
        },
        loadMoreMessages() {
            this.currentPage++;
            const endIndex = this.messagesPerPage * this.currentPage;
            this.displayedMessages = this.messages.slice(0, endIndex).reverse();
            // this.$nextTick(() => {
            //         this.scrollToBottom(false);
            // })
        },
        fetchMessages(id) {
            axios.get('/messages/get-message-thread', {
                params : {
                    Receiver : id,
                    Sender : this.userId,
                }
            })
            .then(response => {
                this.displayedMessages = []
                this.messages = []

                this.messages = response.data;
                // this.messages.reverse()
                this.loadMoreMessages()
            }).catch(error => {
                console.log(error)
            })
        },
        sendMessage() {
            axios.post(`${ axios.defaults.baseURL }/messages/store-messages`, {
                Message: this.newMessage,
                Receiver : this.targetUser
            }).then(response => {
                this.newMessage = '';
                this.messages.push(response.data)
                this.displayedMessages.push(response.data)
                this.$nextTick(() => {
                    this.scrollToBottom() // Ensure the scroll happens after DOM updates
                })

                // add to headerThreads if new
                const exists = this.headerThreads.some(obj => obj.Receiver === response.data.Receiver)
                if (!exists) {
                    const newHeader = {
                        id : 'TMP_ID',
                        Sender : this.userId,
                        Receiver : response.data.Receiver,
                        LatestMessage : response.data.Message,
                        name : this.targetName,
                        Status : response.data.Receiver === this.userId ? 'Unread' : 'Read',
                    }

                    this.headerThreads.unshift(newHeader)
                }
            }).catch(error => {
                console.log(error)
            })
        },
        scrollToBottom() {
            const chatMessages = this.$refs.chatMessages;
            chatMessages.scrollTo({
                top: chatMessages.scrollHeight,
                behavior: 'smooth',
            })
        },
        showTime(currentTime, previousTime) {
            if (!this.isNull(previousTime)) {
                const current = moment(currentTime)
                const prev = moment(previousTime.created_at)

                const dif = current.diff(prev, 'minutes')
                if (dif > 1) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        fetchHeaderThreads() {
            axios.get('/messages/get-header-threads')
            .then(response => {
                this.headerThreads = response.data;

                // // remove threads that has my id on the sender
                // this.headerThreads = this.headerThreads.filter(obj => obj.sender_id !== this.userId)

                // // merge same senders
                // this.headerThreads = Object.values(this.headerThreads.reduce((acc, obj) => {
                //     const key = obj.sender_id;
                //     if (!acc[key]) {
                //         acc[key] = { ...obj };
                //     } else {
                //         acc[key] = { ...acc[key], ...obj };
                //     }
                //     return acc;
                // }, {}));
            }).catch(error => {
                console.log(error)
            })
        },
        fetchUsers() {
            axios.get('/messages/get-users')
            .then(response => {
                this.users = response.data;
            }).catch(error => {
                console.log(error)
            })
        },
        selectTargeFromThreadList(receiverId, name) {
            this.targetUser = receiverId
            this.targetName = name
            this.fetchMessages(receiverId)
        },
        selectTargeFromDropdown(receiverId) {
            const target = this.users.find(obj => obj.id===receiverId)
            this.targetName = target.name
            this.fetchMessages(receiverId)
        }
    },
    mounted() {
        this.fetchUsers()
        this.fetchHeaderThreads()
        Echo.channel('chat')
            .listen('MessageSent', (e) => {
                console.log(e)
                if (!this.isNull(e.message.Receiver)) {
                    // only show if message is for the current user
                    if (e.message.Sender === this.targetUser) {
                        this.messages.push(e.message)
                        this.displayedMessages.push(e.message)
                    }

                    // update heads if there are new messages for the current user
                    if (e.message.Receiver === this.userId) {
                        this.fetchHeaderThreads()
                    }
                }
            })
    },
    updated() {
        this.scrollToBottom()
    }
};
</script>