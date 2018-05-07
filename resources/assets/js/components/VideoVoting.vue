<template>
	<div class="video__voting">
		<a 
			href="#" 
			class="video__voting__button" 
			v-bind:class="{'video__voting__button--voted' : userVote == 'up'}"
			@click.prevent="vote('up')"
		>
			<img src="/images/thumb-up.svg" class="video__voting__thumb" alt="">
		</a> {{ up }} &nbsp;
		<a 
			href="#" 
			class="video__voting__button"
			v-bind:class="{'video__voting__button--voted' : userVote == 'down'}"
			@click.prevent="vote('down')"
		>
			<img src="/images/thumb-up.svg" class="video__voting__thumb video__voting__thumb--down" alt="">
		</a> {{ down }} &nbsp;
	</div>
</template>

<script>
	export default {
		data() {
			return {
				up: null,
				down: null,
				userVote: null,
				canVote: false
			}
		},
		props: {
			videoUid: null
		},
		methods: {
			getVotes() {
				axios.get(`/videos/${this.videoUid}/votes`)
					.then((response) => {
						const resData = response.data.data;
						this.up = resData.up;
						this.down = resData.down;
						this.userVote = resData.user_vote;
						this.canVote = resData.can_vote;
					});
			},
			vote(type) {
				if(this.userVote == type) {
					this[type]--;
					this.userVote = null;
					this.deleteVote(type);
					return
				}

				if(this.userVote) {
					// If clicked opposite to current userVote -1 from the
					// the userVote type.
					this[type == 'up' ? 'down' : 'up']--;
				}

				// Add one to the clicked type.
				this[type]++;

				this.userVote = type;
				this.createVote(type);
				
			},
			deleteVote(type) {
				axios.delete(`/videos/${this.videoUid}/votes`, {
					type: type
				});
			},
			createVote(type) {
				axios.post(`/videos/${this.videoUid}/votes`, {
					type: type
				});
			}
		},
		mounted() {
			this.getVotes();
		}

	}
</script>