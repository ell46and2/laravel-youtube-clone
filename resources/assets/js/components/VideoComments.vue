<template>
<div>
	<p>{{ comments.length }} {{ pluralize('comment', comments.length) }}</p>

	<div class="video__comment clearfix" if="$root.user.authenticated">
		<textarea 
			placeholder="Add a comment" 
			class="form-control video__comment__input"
			v-model="body"
		></textarea>

		<div class="float-right">
			<button class="btn btn-primary" type="submit" @click.prevent="createComment">Post</button>
		</div>		
	</div>

	<ul class="media-list">
		<li class="media mb-3" v-for="comment in comments">
			<div class="media-left mr-3">
				<a :href="'/channel/' + comment.channel.data.slug">
					<img v-bind:src="comment.channel.data.image" :alt="comment.channel.data.name + 'image'" class="media-object">
				</a>
			</div>
			<div class="media-body">
				<a :href="'/channel/' + comment.channel.data.slug" class="mr-2">{{ comment.channel.data.name }}</a>
				<small>{{ comment.created_at_human }}</small>
				<p>{{ comment.body }}</p>

				<ul class="list-inline" v-if="$root.user.authenticated">
					<li>
						<a href="#" @click.prevent="toggleReplyForm(comment.id)">
							{{ replyFormVisible === comment.id ? 'Cancel' : 'Reply' }}
						</a>
					</li>
					<li v-if="$root.user.id === comment.user_id">
						<a href="#" @click.prevent="deleteComment(comment.id)">Delete</a>
					</li>
				</ul>

				<div v-if="replyFormVisible === comment.id" class="video__comment clear">
					<textarea class="form-control video__comment__input" v-model="replyBody"></textarea>
					<div class="float-right">
						<button type="submit" class="btn btn-primary" @click.prevent="createReply(comment.id)">Post</button>
					</div>
				</div>

				<div class="media mt-2" v-for="reply in comment.replies.data">
					<div class="media-left mr-3">
							<a :href="'/channel/' + reply.channel.data.slug">
								<img v-bind:src="reply.channel.data.image" :alt="reply.channel.data.name + 'image'" class="media-object">
							</a>
					</div>
					<div class="media-body">
						<a :href="'/channel/' + reply.channel.data.slug" class="mr-2">{{ reply.channel.data.name }}</a>
						<small>{{ reply.created_at_human }}</small>
						<p>{{ reply.body }}</p>

						<ul class="list-inline" v-if="$root.user.id === reply.user_id">
							<li>
								<a href="#" @click.prevent="deleteComment(reply.id)">Delete</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</li>
	</ul>
</div>
</template>

<script>
	import pluralize from 'pluralize';

	export default {
		data() {
			return {
				comments: [],
				body: null,
				replyBody: null,
				replyFormVisible: null
			}
		},
		props: {
			videoUid: null
		},	
		methods: {
			pluralize, // adds the pluralize filter method from the import.
			getComments() {
				axios.get(`/videos/${this.videoUid}/comments`)
					.then(response => {
						this.comments = response.data.data;
					});
			},
			createComment() {
				axios.post(`/videos/${this.videoUid}/comments`, {
					'body': this.body
				}).then(response => {
					this.comments.unshift(response.data.data);
					this.body = null;
				});
			},
			createReply(commentId) {
				axios.post(`/videos/${this.videoUid}/comments`, {
					'body': this.replyBody,
					'reply_id': commentId
				}).then(response => {
					this.comments.map((comment, index) => {
						if(comment.id === commentId) {
							this.comments[index].replies.data.push(response.data.data);
							return;
						}
					});
					
					this.replyBody = null;
					this.replyFormVisible = null;
				});
			},
			toggleReplyForm(commentId) {
				this.replyBody = null;
				this.replyFormVisible = this.replyFormVisible === commentId ? null : commentId;
			},
			deleteComment(commentId) {
				if(!confirm('Are you sure you want to delete this comment?')) {
					return;
				}

				this.deleteById(commentId);
				axios.delete(`/videos/${this.videoUid}/comments/${commentId}`);
			},
			deleteById(commentId) {
				this.comments.map((comment, index) => {
					if(comment.id === commentId) {
						this.comments.splice(index, 1);
						return;
					} 

					comment.replies.data.map((reply, replyIndex) => {
						if(reply.id === commentId) {
							this.comments[index].replies.data.splice(replyIndex, 1);
							return;
						}
					});			
				});
			}
		},
		mounted() {
			this.getComments();
		}
	}

</script>