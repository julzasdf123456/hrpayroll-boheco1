<template>
    <div class="card shadow-none" style="margin-top: 10px;">
        <div class="card-body">
            <span class="text-muted"><strong>Ask Reeve about Something</strong></span>
            <input id="prompt" v-model="prompt" v-on:keyup.enter="ask()" class="form-control" autofocus placeholder="Type anything..."/>

            <div id="loader" class="spinner-border text-success gone" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <button id="go-btn" class="btn btn-primary btn-sm float-right" @click="ask()" style="margin-top: 5px;"><i class="fas fa-check-circle ico-tab-mini"></i>Go Ask Reeve</button>
        </div>
    </div>

    <div>
        <table class="table table-hover" id="response">
            <tbody>

            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';
import { Toast } from 'bootstrap';

export default {
    name : 'Reeve.reeve',
    components : {
        
    },
    data() {
        return {
            generatedText : '',
            prompt : '',
        }
    },
    methods : {
        async ask() {
            if (jQuery.isEmptyObject(this.prompt)) {
                alert('Type anything before asking')
            } else {
                $('#go-btn').addClass('disabled')
                $('#loader').removeClass('gone')
                try {
                    const response = await axios.post(`/home/chat-reeve`, { prompt : this.prompt });
                    // this.generatedText = response.data.generatedText;
                    $('#response tbody').prepend("<tr><td>" +
                            "<strong class='text-muted' style='padding-bottom: 12px;'><i class='fas fa-user-circle ico-tab'></i>" + this.prompt + "</strong><br><br>" +
                            response.data.generatedText +
                        "</td></tr>")

                    $('#go-btn').removeClass('disabled')
                    $('#loader').addClass('gone')
                    $('#prompt').val('').focus()
                } catch (error) {
                    console.error('Error calling OpenAI API:', error);
                    $('#go-btn').removeClass('disabled')
                    $('#loader').addClass('gone')
                    $('#prompt').val('').focus()
                    alert('Reeve is still busy! Please try again later.')
                }
            }
        }
    },
    created() {
        
    },
    mounted() {
        console.log('page mounted')
    }
}

</script>