<script setup>
import { reactive } from "vue";
import axios from 'axios'
import register from "./register.vue";

const data = reactive({
    user : {
       email : '',
    password : ''
    },
    message : '',
    loggedToParent : true,
    callRegister : false,
});

//props
const props = defineProps({
    logged:{
        type : Boolean,
        required : true,
    }
})

//emit event
const emit = defineEmits(['loggedToParent']);

//action
const loginUser = () =>{
    axios.post('/api/login', data.user)
        .then(res => {
            data.message = res.data.message;
            //console.log(res.data);
            if(res.data.token){
                localStorage.setItem('token',JSON.stringify(res.data.token));
                emit("loggedToParent",data.loggedToParent);
            }
            data.user = {
                email : '',
                password : ''
            };
            //2 seconds
            setTimeout(() => {
                data.message= '';
            }, 2000);
        })
        .catch(err => {console.log("login user",err);});
}

//get data from child
const getIsRegister = (isRegister) =>{
    data.callRegister = !isRegister;
}

</script>
<template>
    <!-- form for login -->
    <form v-if="!logged && !data.callRegister">
                <div class="bg-white  p-5 mx-64 my-20 border-solid border-2 rounded-md divide-gray-400 ">
                    <div class="flex justify-between px-2 py-4 min-w-0 flex-1 bg-indigo-50">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Login or register now</h2>
                    </div>
                    <label class="block my-4">
                        <span class="block text-sm font-medium text-slate-700">Email</span>
                        <!-- Using form state modifiers, the classes can be identical for every input -->
                        <input v-model="data.user.email" type="email" placeholder="Email" class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
                        focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500"/>
                    </label>
                    <label class="block my-4">
                        <span class="block text-sm font-medium text-slate-700">Password</span>
                        <!-- Using form state modifiers, the classes can be identical for every input -->
                        <input v-model="data.user.password" type="password" placeholder="Password" class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
                        focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500"/>
                    </label>
                    <label v-if="data.message !== ''" class="block my-4">
                        <h2 class="underline text-2xl font-bold leading-7 text-red-500 sm:truncate sm:tracking-tight">{{ data.message }}</h2>
                    </label>
                    <label class="flex justify-around ">
                        <!-- Login button -->
                        <button
                        type ="submit" class="rounded-full bg-indigo-400 px-6 py-3 text-white"
                        @click.prevent="loginUser();">Login</button>

                        <!-- Register button -->
                        <button type ="submit" class="rounded-full bg-indigo-400 px-6 py-3 text-white"
                        @click.prevent="data.callRegister=true">Register</button>
                    </label>
                </div>
    </form>
    <register :callRegister="data.callRegister" @isRegister="getIsRegister"/>
</template>
