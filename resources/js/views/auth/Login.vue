<template>
    <div class="container container-login animated fadeIn">
        <h3 class="text-center">Masuk ke sistem</h3>
        <div class="login-form">
            <div 
                :class="['form-group', 'form-floating-label', {
                    'has-error': errors.first('email')
                },{
                    'has-success': fields.email.valid
                }]">
                <input name="email" type="text" class="form-control input-border-bottom" 
                    v-validate="'required|email'"
                    v-model.trim="loginData.email">
                <label for="email" class="placeholder">Email*</label>
                <small class="form-text text-danger">{{ errors.first('email') }}</small>
            </div>
            <div 
                :class="['form-group', 'form-floating-label', {
                    'has-error': errors.first('password')
                }]">
                <input name="password" type="password" class="form-control input-border-bottom" 
                    v-validate="'required|min:6'"
                    v-model.trim="loginData.password">
                <label for="password" class="placeholder">Password*</label>
                <div class="show-password">
                    <i class="icon-eye"></i>
                </div>
                <small class="form-text text-danger">{{ errors.first('password') }}</small>
            </div>
            <div class="row form-sub m-0">
                <!-- <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="rememberme">
                    <label class="custom-control-label" for="rememberme">Remember Me</label>
                </div> -->
                
                <a href="#" class="link float-right">Lupa Password ?</a>
            </div>
            <div class="form-action mb-3">
                <button class="btn btn-primary btn-rounded btn-login"
                    @click="submitForm()"
                >Login</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            loginData: {
                email: '',
                password: ''
            },
            submitting: false
        }
    },
    methods: {
        submitForm(){
            this.$validator.validate()
                .then(valid => {
                    if (valid) {
                        
                    }else{
                        this.$utils.sendNotification('invalid');
                    }
                })
        }
    },
    created(){
        document.body.classList.add("login");
    }
}
</script>
