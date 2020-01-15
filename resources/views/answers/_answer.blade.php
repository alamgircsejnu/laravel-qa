<answer :answer="{{ $answer }}" inline-template>
    <div class="media post">
        @include('shared._vote', [
        'model' => $answer,
        ])
        <div class="media-body">
            <form v-if="editing" @submit.prevent="update">
                <div class="form-group">
                <textarea v-model="body" rows="10" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-outline-primary" :disabled="isInvalid">Update</button>
                <button type="button" @click="cancel" class="btn btn-outline-secondary">Cancel</button>
            </form>
            <div v-else>
                <div v-html="bodyHtml"></div>

                <div class="row mt-4">
                    <div class="col-4 mt-4">
                        <div class="ml-auto">
                            @can('update', $answer)
                                <a @click.prevent="edit" class="btn btn-sm btn-outline-info">Edit</a>
                            @endcan
                            @can('delete', $answer)
                                    <button @click="destroy" class="btn btn-sm btn-outline-danger">Delete
                                    </button>
                            @endcan
                        </div>
                    </div>
                    <div class="col-4"></div>
                    <div class="col-4">
                        <user-info :model="{{ $answer}}" label="Answered"></user-info>
                    </div>
                </div>
            </div>
        </div>
    </div>
</answer>
