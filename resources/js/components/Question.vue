<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form class="card" v-if="editing" @submit.prevent="update">
                <div class="card-body">
                    <div class="card-title">
                        <input class="form-control form-control-lg" v-model="title"/>
                    </div>
                    <hr>
                    <div class="media">
                        <div class="media-body">
                            <div class="form-group">
                                <textarea v-model="body" rows="10" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-outline-primary" :disabled="isInvalid">Update</button>
                            <button type="button" @click="cancel" class="btn btn-outline-secondary">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card" v-else>
                <div class="card-body">
                    <div class="card-title">
                        <div class="d-flex align-items-center">
                            <h1>{{ title }}</h1>
                            <div class="ml-auto">
                                <a href="/questions" class="btn btn-outline-secondary">Back to
                                    all
                                    Questions</a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="media">
                        <vote :model="question" name="question"></vote>
                        <div class="media-body">
                            <div v-html="bodyHtml">
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <div class="ml-auto">
                                        <a v-if="authorize('modify', question)" @click.prevent="edit"
                                           class="btn btn-sm btn-outline-info">Edit</a>
                                        <button v-if="authorize('deleteQuestion', question)" @click="destroy"
                                                class="btn btn-sm btn-outline-danger">Delete
                                        </button>
                                    </div>
                                </div>
                                <div class="col-4"></div>
                                <div class="col-4">
                                    <user-info :model="question" label="Asked"></user-info>
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
    import Vote from './Vote.vue';
    import UserInfo from './UserInfo.vue';
    import modification from '../mixins/modification';

    export default {
        props: ["question"],

        components: {Vote, UserInfo},

        mixins: [modification],

        data() {
            return {
                title: this.question.title,
                body: this.question.body,
                bodyHtml: this.question.body_html,
                id: this.question.id,
                beforeEditCache: {}
            }
        },

        computed: {
            isInvalid() {
                return this.body.length < 10 || this.title.length < 10;
            },

            endpoint() {
                return `/questions/${this.id}`;
            }
        },

        methods: {
            setEditCache() {
                this.beforeEditCache = {
                    title: this.title,
                    body: this.body
                };
            },

            restoreFromCache() {
                this.title = this.beforeEditCache.title;
                this.body = this.beforeEditCache.body;
            },

            payload() {
                return {
                    title: this.title,
                    body: this.body
                }
            },

            delete() {
                axios.delete(this.endpoint)
                    .then(({data}) => {
                        this.$toast.success(data.message, "Success", {timeout: 2000})
                    });

                setTimeout(() => {
                    window.location.href = '/questions';
                }, 3000);
            }
        }
    }
</script>
