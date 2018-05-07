<template>
	<div v-if="subscribers !== null">
		{{ subscribers }} {{ pluralize('subscriber', subscribers) }} &nbsp;

		<button 
			class="btn btn-sm btn-primary" 
			v-if="canSubscribe"
			@click.prevent="handle"
		>
			{{ userSubscribed ? 'Unsubscribe' : 'Subscribe' }}
		</button>
	</div>
</template>

<script>
	import pluralize from 'pluralize';

	export default {
		data() {
			return {
				subscribers: null,
				userSubscribed: false,
				canSubscribe: false
			}
		},
		props: {
			channelSlug: null
		},
		methods: {
			pluralize,
			getSubscriptionStatus() {
				axios.get(`/subscription/${this.channelSlug}`)
					.then(response => {
						const resData = response.data.data;
						this.subscribers = resData.count;
						this.userSubscribed = resData.user_subscribed;
						this.canSubscribe = resData.can_subscribe;
					});
			},
			handle() {
				if(this.userSubscribed) {
					this.unsubscribe();
				} else {
					this.subscribe();
				}
			},
			subscribe() {
				this.userSubscribed = true;
				this.subscribers++;

				axios.post(`/subscription/${this.channelSlug}`);
			},
			unsubscribe() {
				this.userSubscribed = false;
				this.subscribers--;

				axios.delete(`/subscription/${this.channelSlug}`);
			}
		},
		mounted() {
			this.getSubscriptionStatus();
		}
	}
</script>