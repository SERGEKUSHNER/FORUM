<template>

    <li class="dropdown" v-if="notifications.length" >
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            <i class="far fa-bell"></i><span class="caret"></span>
        </a>

        <ul class="dropdown-menu" role="menu">

            <li class="dropdown-item" v-for="notification in notifications">
                <a :href="notification.data.link"
                   v-text="notification.data.message"
                   @click="markAsRead(notification)"
                ></a>

            </li>

        </ul>
    </li>

</template>

<script>
    export default {
       data(){
           return {
               notifications: false
           }
       },
        created(){
           axios.get("/profiles/"+ window.App.user.name + "/notifications")
                .then(response=>this.notifications = response.data);
        },
        methods:{
           //"/profiles/{$user->name}/notifications/"
           markAsRead(notification){
                axios.delete('/profiles/'+ window.App.user.name + '/notifications/'+ notification.id);
           }
        }
    }
</script>

<style scoped>

</style>