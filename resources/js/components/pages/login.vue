<template>
    <div>
        <div class="container">
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-4">
                  <div class="login_header">
                      <h1>Login to the dashboard</h1>
                  </div>
                  <div class="space">
                        <Input type="email" v-model="data.email"  placeholder="Email"  />
                    </div>
                    <div class="space">
                        <Input type="password" v-model="data.password"  placeholder="******"  />
                    </div>
                    <div class="login_footer">
                        <Button type="primary" @click="login" :disabled="isLogging || isLocked" :loading="isLogging">{{isLogging ? 'Loging...' : 'Login'}}</Button>
                    </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    data(){
        return {
            data : {
                email : '', 
                password: ''
            }, 
            isLogging: false, 
            isLocked: localStorage.getItem('loginCounter') >= 3? true: false, 
        }
    },
    
    methods : {
        async login(){
            if(this.data.email.trim()=='') return this.e('Email is required')
            if(this.data.password.trim()=='') return this.e('Password is required')
            this.isLogging = true
            const res = await this.callApi('post', '/api/login', this.data)
            if(res.status===200){
                this.s(res.data.msg)
                window.location = '/'
            }else{
                if (!localStorage.getItem('loginCounter')) {
                    localStorage.setItem('loginCounter', 1)
                } else {
                    let loginCounter = localStorage.getItem('loginCounter')
                    localStorage.setItem('loginCounter', parseInt(loginCounter) + 1)
                }
                if(res.status===401){
                    this.i(res.data.msg)
                }else if(res.status==422){
                    for(let i in res.data.errors){
                        this.e(res.data.errors[i][0])
                    }
                }
                else{
                    this.swr()
                }
                if (localStorage.getItem('loginCounter') == 3) {
                    this.isLocked = true
                    this.i('You failed 3 times to login. You can retry in 5 mins.')
                    setTimeout(() => {
                        localStorage.setItem('loginCounter', 0)
                        this.isLocked = false
                    }, 300000);
                }
            }
            this.isLogging = false
        }
    }
}
</script>

<style scoped>
    ._1adminOverveiw_table_recent {
        margin: 0 auto;
        margin-top: 220px;
    }
    .login_footer{
        text-align: center;
    }
    .login_header{
        text-align: center;
        margin-bottom: 25px;
    }
</style>