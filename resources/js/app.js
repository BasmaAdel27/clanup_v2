
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

const { default: axios } = require('axios');
const { default: Echo } = require('laravel-echo');
require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


import Vue from "vue";
var notificationsWrapper   = $('.dropdown-messages');
var notificationsToggle    = notificationsWrapper.find('button[data-bs-toggle]');
var notificationsCountElem = notificationsToggle.find('i[data-count]');
var notificationsCount     = parseInt(notificationsCountElem.data('count'));
var notifications          = notificationsWrapper.find('#messages-dropdown');
console.log(notifications,notificationsCount);
if (notificationsCount <= 0) {
    // notificationsWrapper.hide();
}



// Bind a function to a Event (the full Laravel class)


Vue.prototype.$userId = document.querySelector("meta[name='user-id']").getAttribute('content');
Vue.config.productionTip = false

Vue.component('chat-messages', require('./components/ChatMessages.vue').default);
// Vue.component('chat-form', require('./components/ChatForm.vue').default);
// Pusher.logToConsole = true;

var pusher = new Pusher('e86d88c0cf50e3ba3a78', {
    cluster: 'eu',
    encrypted: true,

});
var groupid=window.location.href.split("/").slice(-1)[0].slice(-2);
var channel = pusher.subscribe('groups.'+groupid);

//send message
channel.bind('MessageSent', function(data) {
    app.messages.push(JSON.stringify(data));
});

//send notification
channel.bind('notification', function(data) {
    // console.log(data);
    var existingNotifications = notifications.html();
    // console.log(Vue.prototype.$userId);
    if (Vue.prototype.$userId == data.message.user_id) {
        notifications.html(existingNotifications);
    }else {
        var newNotificationHtml = `
         <li class="border-bottom">
        <a class="dropdown-item" href="/g/`+ data.message.group.slug+`?x=`+ data.message.group.id +`">
            <div class="row gx-0 d-flex align-items-center">
                <div class="col-3">
                    <span class="avatar avatar-sm border" style="background-image: url(`+data.image+`)"></span>
                </div>
                <div class="col-9">
                    <p class="mb-0 text-truncate text-truncate-two-line">` + data.msg + `</p>
                    <small class="mb-0 text-muted">` + data.created_at + `</small>
                </div>
            </div>
        </a>
    </li>
        `;
        notifications.html(newNotificationHtml + existingNotifications);
        notificationsCount += 1;
        notificationsCountElem.attr('data-count', notificationsCount);
        // notificationsWrapper.find('.notif-count').text(notificationsCount);
        document.getElementById('notificationsDropdown').innerHTML += `
      <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                                <span class="visually-hidden">{{ __('Unread notifications') }}</span>
                            </span>`
    }
});

const app = new Vue({
    el: '#app',
    //Store chat messages for display in this array.
    mounted(){
        //we get the group id in the url
        const url = window.location.href;
        const group_Id = url.split("/").slice(-1)[0].slice(-2);
        this.groupId = group_Id;

            axios.get('/messages/'+this.groupId).then(res => {
                res.data.map(message => {
                    // console.log(message)
                    this.messages.push(message)


                });
            })
       window.Echo.private('groups.'+this.groupId)
            .listen('MessageSent', (e) => {
                // console.log(e)
                this.messages.push({
                    message: e.message.message,
                    user: e.user,
                    created_at:e.message.created_at,
                });
                $('#datascroll').animate({
                    scrollTop: $('#datascroll').get(0).scrollHeight
                }, 1000);
            });
         // console.log(this.messages)
    },
    data: {
        message:'',
        groupId: null,
        messages: [],
    },


methods: {

    addMessage(event) {
        event.preventDefault();
        let token = document.head.querySelector('meta[name="csrf-token"]');

        axios.post('/messages/' + this.groupId, {message: this.message}, {'X-CSRF-TOKEN': token})
            .then(res => {
                // console.log(res.data)
                  this.message='';

            });
    },

}
});


