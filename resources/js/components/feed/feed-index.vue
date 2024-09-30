<template>
    <div class="row mb-2">
        <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1" @submit.prevent="view()">
            <div class="card shadow-none">
                <div class="card-body p-4">
                    <div class="post-box" @click="makeAPost()" style="cursor: pointer;">
                        <div style="width: 50px; height: 50px;">
                            <img style="height: 50px !important; width: 50px !important; cursor: pointer; object-fit: cover;" class="img-circle" :src="imagePreview" alt="n/a">
                        </div>
                        <div class="post-input-holder">
                            <p class="text-muted no-pads">Start a post...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- results -->
        <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1">
            <div v-for="res in results" class="card shadow-none mt-2">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="post-box">
                                <div>
                                    <img v-if="!isNull(res.ProfilePicture)" style="height: 40px !important; width: 40px !important; object-fit: cover;" class="img-circle" :src="imgsPath + '/profiles/' + res.ProfilePicture" alt="n/a">
                                    <img v-if="isNull(res.ProfilePicture)" style="height: 40px !important; width: 40px !important; object-fit: cover;" class="img-circle" :src="imgsPath + '/prof-img.png'" alt="n/a">
                                </div>
                                <div>
                                    <p class="no-pads"><strong>{{ res.FirstName + ' ' + res.LastName }}</strong></p>
                                    <p class="no-pads text-muted text-sm">{{ moment(res.created_at).format('ddd, MMM DD, YYYY hh:mm A') }} • <i class="fas fa-globe-americas"></i></p>
                                </div>
                            </div>
        
                            <p class="px-2 pt-4 pb-2" v-html="res.PostContent"></p>
                        </div>

                        <div class="col-lg-12">
                            <button class="btn btn-link-muted float-right" title="Repost"><i class="fas fa-retweet"></i></button>
                            <button class="btn btn-link-muted float-right" title="Comment"><i class="far fa-comments"></i></button>
                            <button @click="react(res.id)" class="btn btn-link-muted float-right" title="Like"><i :class="reactHasMe(res.id)"></i> <span class="text-xs">{{ isNull(res.ReactionCount) ? 0 : res.ReactionCount }}</span></button>
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
    </div>

    <!-- MAKE A POST -->
    <div ref="modalMakePost" class="modal fade" id="modal-make-post" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content px-4 py-3">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="post-box">
                                <div style="width: 58px; height: 58px;">
                                    <img style="height: 58px !important; width: 58px !important; cursor: pointer; object-fit: cover;" class="img-circle" :src="imagePreview" alt="n/a">
                                </div>
                                <div>
                                    <p class="no-pads" style="font-size: 1.3em;">{{ isNull(employeeData) ? '' : employeeData.FirstName + ' ' + employeeData.LastName }}</p>
                                    <select class="form-control form-control-sm" v-model="postPrivacy">
                                        <option value="Show to Everyone">Show to Everyone</option>
                                        <option value="Show Only to Me">Show Only to Me</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 pt-4">
                            <textarea class="form-control p-3" placeholder="What'ya wanna talk about?" rows="10" v-model="postContent" autofocus></textarea>
                        </div>

                        <div class="col-lg-12 pt-4">
                            <button class="btn btn-link-muted" title="Attach image"><i class="fas fa-image"></i></button>

                            <button @click="publishPost" class="btn btn-primary float-right">Post</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import jquery from 'jquery';
import moment from 'moment';
import Swal from 'sweetalert2';

export default {
    name : 'FeedIndex.feed-index',
    components : {
        'pagination' : Bootstrap4Pagination,
        Swal,
    },
    data() {
        return {
            token : document.querySelector("meta[name='csrf-token']").getAttribute('content'),
            employeeId : document.querySelector("meta[name='employee-id']").getAttribute('content'),
            userId : document.querySelector("meta[name='user-id']").getAttribute('content'),
            moment : moment,
            search : '',
            isEditMode : false,
            employees : {},
            baseURL : axios.defaults.baseURL,
            imgsPath : axios.defaults.imgsPath,
            results : {},
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            activeMemo : {},
            respondents : [],
            employeeData : {},
            imagePreview : null,
            postPrivacy : 'Show to Everyone',
            postContent : '',
        }
    },
    methods : {
        isNull (value) {
            // Check for null or undefined
            if (value === null || value === undefined) {
                return true;
            }

            // Check for empty string
            if (typeof value === 'string' && value.trim() === '') {
                return true;
            }

            // Check for empty array
            if (Array.isArray(value) && value.length === 0) {
                return true;
            }

            // Check for empty object
            if (typeof value === 'object' && !Array.isArray(value) && Object.keys(value).length === 0) {
                return true;
            }

            // Check for NaN
            if (typeof value === 'number' && isNaN(value)) {
                return true;
            }

            // If none of the above, it's not null, empty, or undefined
            return false;
        },
        generateRandomString(length) {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';

            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                result += characters.charAt(randomIndex);
            }

            return result;
        },
        generateUniqueId() {
            return moment().valueOf() + "-" + this.generateRandomString(32);
        },
        getEmployeeInfo() {
            this.employeeData = null
            axios.get(`${ this.baseURL }/employees/get-employee-full-ajax`, {
                params : {
                    EmployeeId : this.employeeId,
                }
            })
            .then(response => {
                this.employeeData = response.data

                if (!this.isNull(this.employeeData)) {
                    if (!this.isNull(this.employeeData.ProfilePicture)) {
                        this.imagePreview = this.imgsPath + "/profiles/" + this.employeeId + '.jpg'
                    } else {
                        this.imagePreview = this.imgsPath + 'prof-img.png'
                    }
                }
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting employee data!\n' + error.response.data
                })
            })
        },
        printMemo(id) {
            Swal.fire({
                title: 'Print Option',
                showDenyButton: true,
                confirmButtonText: 'Print Without Employee Names',
                denyButtonText: `Print With Employee Names`,
                }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `${ this.baseURL }/memorandums/print-memo/${ id }/with-no-employee`
                } else if (result.isDenied) {
                    window.location.href = `${ this.baseURL }/memorandums/print-memo/${ id }/with-employee`
                }
            })
        },
        trashMemo(id) {
            Swal.fire({
                title: 'Delete this Memo?',
                text : 'You can always re-add this.',
                confirmButtonText: 'Delete',
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`${ this.baseURL }/memorandums/trash-memo`, {
                        _token : this.token,
                        id : id,
                    })
                    .then(response => {
                        this.toast.fire({
                            icon : 'success',
                            text : 'Memo deleted!'
                        })
                        location.reload()
                    })
                    .catch(error => {
                        console.log(error.response)
                        this.toast.fire({
                            icon : 'error',
                            text : 'Error deleting memo!'
                        })
                    })
                }
            })
        },
        makeAPost() {
            let modalElement = this.$refs.modalMakePost
            $(modalElement).modal('show')
        },
        publishPost() {
            axios.post(`${ this.baseURL }/posts`, {
                _token : this.token,
                id : this.generateUniqueId(),
                PostContent : this.postContent,
                PostRawText : this.postContent,
                Priority : 3,
                PostType : 'REGULAR POST',
                Privacy : this.postPrivacy,
            })
            .then(response => {
                let modalElement = this.$refs.modalMakePost
                $(modalElement).modal('hide')

                this.toast.fire({
                    icon : 'success',
                    text : 'Post published!'
                })
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error publishing post!'
                })
            })
        },
        getPosts(take = 20) {
            axios.get(`${ this.baseURL }/posts/get-posts`, {
                params : {
                    Take : take,
                }
            })
            .then(response => {
                this.results = response.data
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting posts!'
                })
            })
        },
        react(id) {
            axios.post(`${ this.baseURL }/posts/react`, {
                _token : this.token,
                Type : 'LIKE',
                PostId : id,
            })
            .then(response => {
                this.updateReaction(id, response.data)
                this.reactHasMe(id)
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error sending react!'
                })
            })
        },
        updateReaction(id, count) {
            // add or deduct reaction
            let post = this.results.find(obj => obj.id === id)
            let reactions = []

            if (!this.isNull(post)) {
                reactions = post.Reactions

                let me = reactions.find(obj => obj.UserId === this.userId)

                if (this.isNull(me)) {
                    reactions.push({
                        UserId : this.userId
                    })
                } else {
                    reactions = reactions.filter(obj => obj.UserId !== this.userId)
                }
            }

            // update results
            this.results = this.results.map(obj => obj.id === id ? { ...obj, ReactionCount : count, Reactions : reactions } : obj )
        },
        reactHasMe(id) {
            let post = this.results.find(obj => obj.id === id)

            if (!this.isNull(post)) {
                let reactions = post.Reactions

                let me = reactions.find(obj => obj.UserId === this.userId)

                if (this.isNull(me)) {
                    // i didnt react
                    return 'far fa-heart'
                } else {
                    // i reacted
                    return 'fas fa-heart text-danger'
                }
            } else {
                return 'far fa-heart'
            }
        }
    },
    created() {
        
    },
    mounted() {
        this.getEmployeeInfo()
        this.getPosts()
    }
}

</script>