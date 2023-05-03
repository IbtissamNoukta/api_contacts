<script setup>
import {reactive, onMounted } from "vue";
import axios from 'axios'
import Login from "./Login.vue";
//import { useContactStore } from '@/stores/contacts'
//const {  addContact } = useContactStore();

//set data
const data = reactive({
    contact : {
        id : '',
        name : '',
        tel : ''
    },
    contacts : [],
    toggleModelContact : false,
    update : false,
    profile:'',
    //logget == connecter
    logged : JSON.parse(localStorage.getItem('token'))? true : false,
    token : JSON.parse(localStorage.getItem('token')) || '',

});

//config of api
const config = {
        headers:{
            'Authorization':"Bearer "+data.token,
        }
    };

//calling api
const getContacts = () =>{
    axios.get('/api/contacts', config)
        .then(res => {data.contacts = res.data.data;})
        .catch(err => {console.log("get contacts",err);});
}
const addContact = (contact) =>{
    axios.post('/api/contacts', contact, config )
        .then(res => {getContacts();})
        .catch(err => {console.log("post contacts",err);});
}
const getContact = (id) =>{
    axios.get('/api/contacts/'+ id, config)
        .then(res => {data.contact = res.data.data;})
        .catch(err => {console.log("get contacts/id",err);});
}
const updateContact = (contact) =>{
    axios.put('/api/contacts/'+contact.id, contact, config)
        .then(res => {getContacts();})
        .catch(err => {console.log("put contacts/id",err);});
    data.contact = {
        id : '',
        name : '',
        tel : ''
    };
}
const deleteContact = (id) =>{
    //sweetalert2 biblio
    Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
    if (result.isConfirmed) {
        //delete api
        axios.delete('/api/contacts/'+id, config)
                .then(res => {getContacts();})
                .catch(err => {console.log("delete contacts/id",err);});
        Swal.fire(
        'Deleted!',
        'Your contact has been deleted.',
        'success'
        )
    }
    })
}

//actions
const getProfile = () =>{
    axios.get('/api/profile', config)
        .then(res => {data.profile = res.data.data})
        .catch(err => {console.log("get profil",err);});
}
const logout = (token) =>{
    axios.get('/api/logout', config)
        .then(res => {data.logged = false;
                        localStorage.removeItem('token');})
        .catch(err => {console.log("get logout",err);});
}

//onMounted for list: to execute get(send request) one time also get profil one time
onMounted(() => {
    getContacts();
    getProfile(data.token);
    })

//actions
const storeContact = () =>{
    const contact = data.contact;
    addContact(contact);
    data.contact = {
        id : '',
        name : '',
        tel : ''
    };
}
//getting id to update a contact
const editContact = (id) =>{
    getContact(id);
}

//for close
const freeDataContact= () =>{
    data.contact = {
        id : '',
        name : '',
        tel : ''
    };
}
//get data from child
 const getLogged = (loggedToParent) => {
    data.logged = loggedToParent;
}

</script>

<template>
    <div v-if="data.logged" >
        <div  :class="[(data.toggleModelContact)? 'blur-sm' : '' ]">
        <div class="flex justify-between p-2 min-w-0 flex-1 bg-indigo-50">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Welcome {{ data.profile.name }}</h2>
                <button class="mr-2 rounded-lg pb-1 bg-gray-600 px-3 hover:bg-gray-800 text-white"
                     @click="logout(data.token);">Logout</button>
        </div>
        <div class="relative mx-12 mt-8 mb-4">
            <div class="border-solid border-2 rounded-md divide-gray-400 ">
                <div class="flex justify-between px-6 py-4 min-w-0 flex-1 bg-gray-100">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Contacts</h2>
                    <button class="rounded-full bg-indigo-400 px-3 hover:bg-indigo-600 text-white"
                     @click="data.toggleModelContact = !data.toggleModelContact">Add new contact</button>
                </div>
                <ul role="list" class="px-6 divide-y divide-gray-100 ">
                    <li v-for="person in data.contacts" :key="person.id" class="justify-between gap-x-6 py-5">
                    <div class="flex gap-x-4">
                        <!-- :src="person.imageUrl" -->
                        <img class="h-12 w-12 flex-none rounded-full bg-gray-50"  alt="" />
                        <div class="min-w-0 flex-auto">
                        <p class="text-sm font-semibold leading-6 text-gray-900">{{ person.name }}</p>
                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ person.tel }}</p>
                        </div>
                        <div class="flex ">
                            <button class="rounded-none bg-yellow-500 px-4 text-white rounded-l-lg"
                            @click="editContact(person.id); data.toggleModelContact = !data.toggleModelContact;
                            data.update=true;">Edit</button>

                            <button @click="deleteContact(person.id);"
                            class="rounded-none bg-red-400 px-2 text-white rounded-r-lg">Delete</button>

                        </div>
                    </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
        <!-- modal -->
        <div v-if="data.toggleModelContact" class="fixed overflow-x-hidden overflow-y-auto inset-0 flex justify-center items-center z-50">
            <div class="relative mx-auto w-auto max-w-2xl">
                <div class="bg-white w-full p-5 border-solid border-2 rounded-md divide-gray-400 shadow-xl">
                    <label class="block my-4">
                        <span class="block text-sm font-medium text-slate-700">Contact name</span>
                        <!-- Using form state modifiers, the classes can be identical for every input -->
                        <input v-model="data.contact.name" type="text" placeholder="Name" class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
                        focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500"/>
                    </label>
                    <label class="block my-4">
                        <span class="block text-sm font-medium text-slate-700">Contact phone</span>
                        <!-- Using form state modifiers, the classes can be identical for every input -->
                        <input v-model="data.contact.tel" type="text" placeholder="Phone number" class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
                        focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500"/>
                    </label>
                    <label class="flex justify-between">
                        <!-- add contact -->
                        <button v-if="!data.update" :disabled="!data.contact.name || !data.contact.tel"
                        type ="submit" class="rounded-md bg-indigo-400 px-6 py-1 text-white"
                        @click.prevent="storeContact(); data.toggleModelContact=false;">Add</button>
                        <!-- edit contact -->
                        <button v-if="data.update" :disabled="!data.contact.name || !data.contact.tel"
                        type ="submit" class="rounded-md bg-yellow-500 px-4 py-1 text-white"
                        @click.prevent="updateContact(data.contact); data.toggleModelContact=false; data.update=false;">Update</button>

                        <button type ="submit" class="rounded-md bg-red-400 px-6 py-1 text-white"
                        @click.prevent="freeDataContact(); data.toggleModelContact=false; data.update=false;">Close</button>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <!-- <div v-if="data.toggleModel" class="absolute inset-0 z-40 opacity-25 bg-black"></div> -->
    <Login :logged = "data.logged" @loggedToParent="getLogged"/>
</template>
