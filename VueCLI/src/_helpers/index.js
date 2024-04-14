import router from "@/router/index";

let authConnect = (to) => {
    let token = localStorage.getItem('user-info')

    if (token) {
        return true
    }
    router.push('/login')
}
let authDeconnect= (to) =>{
    let token = localStorage.getItem('user-info')
    
    if (!token) {
        return true
    }
    router.push('/')
}
let authUser= (to) =>{
    let user = JSON.parse(localStorage.getItem("user-info"));

    if (user[0].role == 'admin'){
        return true
    }
    router.push('/')
}
export const auth = {
    authDeconnect,authConnect,authUser
}