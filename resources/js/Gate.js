/* acl on the front end
when use the gate -> pass the authenticaded user info -> access to isAdmin, isUser  */

export default class Gate {
    constructor(user) {
        this.user = user;
    }

    isAdmin() {
        return this.user.type === "admin";
    }

    isUser() {
        return this.user.type === "user";
    }

    isAdminOrAuthor() {
        if (this.user.type === "admin" || this.user.type === "author") {
            return true;
        }
    }
}
