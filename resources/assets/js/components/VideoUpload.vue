<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Upload</div>

                    <div class="card-body">
                        <input 
                            type="file" 
                            name="video" 
                            id="video" 
                            @change="fileInputChange"
                            v-if="!uploading"
                        >
                        
                        <div class="alert alert-danger" v-if="failed">Something went wrong. Please try again</div>

                        <div id="video-form" v-if="uploading && !failed">

                            <div class="alert alert-info" v-if="!uploadingComplete">
                                Your video will be available at <a :href="videoURL" target="_blank">{{ videoURL }}</a>.
                            </div>
                            <div class="alert alert-success" v-else>
                                Upload complete. Video is now processing.
                                <a href="/videos">Go to your videos</a>
                            </div>

                            <div class="progress" v-if="!uploadingComplete">
                                <div class="progress-bar" v-bind:style="{width: fileProgress + '%' }"></div>
                            </div>

                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" v-model="title">
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" v-model="description"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="visibility">Visibility</label>
                                <select class="form-control" v-model="visibility">
                                    <option value="private">Private</option>
                                    <option value="unlisted">Unlisted</option>
                                    <option value="public">Public</option>
                                </select>
                            </div>

                            <span class="help-block float-right">{{ saveStatus }}</span>
                            <button 
                                class="btn btn-primary" 
                                type="submit"
                                @click.prevent="update"
                            >Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                uid: null,
                file: null,
                uploading: false,
                uploadingComplete: false,
                failed: false,
                title: 'Untitled',
                description: null,
                visibility: 'private',
                saveStatus: null,
                fileProgress: 0
            }
        },
        computed: {
            videoURL() {
                return this.$root.url + '/videos/' + this.uid;
            }
        },
        methods: {
            fileInputChange() {
                console.log('changed file');
                this.uploading = true;
                this.failed = false;

                this.file = document.getElementById('video').files[0];

                // store the metadata
                this.store().then(() => {
                    let form = new FormData();

                    form.append('video', this.file);
                    form.append('uid', this.uid);

                    const uploadProgress = this.uploadProgress;

                    let config = {
                        onUploadProgress(progressEvent) {
                            let percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);

                            uploadProgress(percentCompleted);

                            return percentCompleted;
                        }
                    };

                    axios.post('/upload', form, config)
                    .then(() => {
                        this.uploadingComplete = true;
                    }, () => {
                        this.failed = true;
                    });
                }, () => {
                    this.failed = true;
                });
            },
            uploadProgress(percent) {
                console.log('percent', percent);
                this.fileProgress = percent;
            },
            store() {
                return axios.post('/videos', {
                    title: this.title,
                    description: this.description,
                    visibility: this.visibility,
                    extension: this.file.name.split('.').pop()
                }).then(response => {
                    this.uid = response.data.data.uid;
                });
            },
            update() {
                this.saveStatus = 'Saving changes.';

                return axios.put(`/videos/${this.uid}`, {
                    title: this.title,
                    description: this.description,
                    visibility: this.visibility,
                }).then(response => {
                    this.saveStatus = 'Changes saved.';

                    setTimeout(() => {
                        this.saveStatus = null;
                    }, 3000);
                }, () => {
                    this.saveStatus = 'Failed to save changes.';
                });
            }
        },
        mounted() {
            window.onbeforeunload = () => {
                if(this.uploading && !this.uploadingComplete && !this.failed) {
                    return 'Are you sure you want to navigate away. Your video is currently uploading.'
                }
            }
        }
    }
</script>
