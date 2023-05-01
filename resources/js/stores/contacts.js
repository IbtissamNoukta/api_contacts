import { reactive, computed } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'

export const useContactStore = defineStore('contacts', () => {
    //state
    const contacts = reactive ({
        data : [],
    });
    const contact = reactive({
        id : '',
        name : '',
        tel :  ''
    })
    //action
    function addContact(contact){
        axios.post('/api/contacts', contact)
            .then(res => {this.getContacts();})
            .catch((error) => {console.log("post contacts",err);});
    }
    function updateContact(id, contact){
        axios.put('/api/contacts/'+id, contact)
            .then(res => {this.getContacts();})
            .catch((error) => {console.log("put contacts id",err);});
    }
    //getter
    function getContacts(){
        axios.get('/api/contacts')
            .then(res => {this.contacts.data = res.data.data; console.log(contacts.data);})
            .catch(res => {console.log("get contacts",err)});
        return this.contacts.data;
    }

    function getContact(id){
        axios.get('/api/contacts/'+id)
            .then(res => {contact = res.data.data})
            .catch((error) => {console.log("get contacts id",err);});
        //return this.contact;
    }

      return { contacts, addContact, getContacts, getContact, updateContact}
    })
