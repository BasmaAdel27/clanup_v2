<template>
  <div class="input-group">
    <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Type your message here..." v-model="newMessage" @keyup.enter="sendMessage">
    <input id="btn-input" type="hidden" name="group" class="form-control input-sm" v-model="group" >

    <span class="input-group-btn" style="margin: -10px">
            <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage"
                    style="margin: 10px;background-color: #E06F19;">
                <i class="far fa-paper-plane" style="padding:9px;font-size: large"></i>
            </button>
        </span>
  </div>
</template>

<script>
export default {
  props: ['user','group'],
  data() {
    return {
      newMessage: ''
    }
  },
  methods: {
    sendMessage() {
      this.$emit('messagesent', {
        user: this.user,
        message: this.newMessage,
        group:this.group

      });

      this.newMessage = ''
    }
  }
}
</script>
