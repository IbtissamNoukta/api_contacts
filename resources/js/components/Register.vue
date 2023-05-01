<script setup>
import { reactive } from "vue";
import axios from 'axios'

const data = reactive({
    user : {
        name:'',
       email : '',
    password : ''
    },
    isRegister : true,
});

//props
const props = defineProps({
    callRegister:{
        type : Boolean,
        required : true,
    }
})
//emit event
const emit = defineEmits(['isRegister']);

//action
const register = (user) =>{
    axios.post('/api/register', user)
        .then(res => {
            emit("isRegister",data.isRegister);
        })
        .catch(err => {console.log("get register",err);});
}
</script>

<template>
    <!-- form for login -->
    <form v-if="callRegister">
                <div class="bg-white  p-5 mx-64 my-20 border-solid border-2 rounded-md divide-gray-400 ">
                    <div class="flex justify-between px-2 py-4 min-w-0 flex-1 bg-indigo-50">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Register now</h2>
                    </div>
                    <label class="block my-4">
                        <span class="block text-sm font-medium text-slate-700">Name</span>
                        <!-- Using form state modifiers, the classes can be identical for every input -->
                        <input v-model="data.user.name" type="text" placeholder="Name" class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
                        focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500"/>
                    </label>
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
                        <!-- register button -->
                        <button type ="submit" :disabled="!data.user.name || !data.user.email || !data.user.password"
                        class="rounded-full bg-indigo-400 px-6 py-3 text-white"
                        @click.prevent="register(data.user)">Register</button>
                    </label>
                </div>
    </form>
</template>
