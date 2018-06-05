let user = window.App.user;

module.exports = {
    owns (model, prop = 'user_id') {
        return model[prop] === user.id;
    },

    isAdmin(){
        return['Serge','Dana'].includes(user.name);
    }

};