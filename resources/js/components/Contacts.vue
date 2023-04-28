<script setup>
import axios from 'axios';
import {reactive} from "vue";
const contacts = reactive ({
    data : []
});
axios.get('/api/contacts')
    .then(res => {contacts.data = res.data.data})
    .catch(res => console.log(res));
</script>

<template>
  <div class="relative mx-12 mt-6">
    <div class="border-solid border-2 rounded-md divide-gray-400 ">
      <div class="flex justify-between px-6 py-4 min-w-0 flex-1 bg-gray-100">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Contacts</h2>
          <button class="rounded-full bg-indigo-400 px-3 text-white">Add new contact</button>
      </div>
      <ul role="list" class="px-6 divide-y divide-gray-100 ">
        <li v-for="person in contacts.data" :key="person.id" class="justify-between gap-x-6 py-5">
          <div class="flex gap-x-4">
            <img class="h-12 w-12 flex-none rounded-full bg-gray-50" :src="person.imageUrl" alt="" />
            <div class="min-w-0 flex-auto">
              <p class="text-sm font-semibold leading-6 text-gray-900">{{ person.name }}</p>
              <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ person.tel }}</p>
            </div>
            <div class="flex ">
                <button class="rounded-none bg-yellow-500 px-2 text-white">Edit</button>
                <button class="rounded-none bg-red-400 px-2 text-white">Delete</button>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
