<template>
    <div>
        <div v-for="(user, index) in userData" :key="index">
            <h1>{{ user.name }}</h1>
            <p>Username: {{ user.username }}</p>
            <p>Email: {{ user.email }}</p>
            <p>Website: <a :href="user.website">{{ user.website }}</a></p>
            <p>Role: {{ user.role }}</p>
            <button @click="toggleAdditionalData(index)">Load Additional Data</button>
            <div v-if="showAdditionalData[index]">
                <p>Address:
                    <ul>
                        <li>Street: {{ user.address.street }} </li>
                        <li>Suite: {{ user.address.suite }} </li>
                        <li>City: {{ user.address.city }} </li>
                        <li>Zipcode: {{ user.address.zipcode }} </li>
                        <li>Geo => Lat: {{ user.address.geo.lat }} , Lng : {{ user.address.geo.lng }}</li>
                    </ul>
                </p>
                <p>Company:
                    <ul>
                        <li>Name: {{ user.company.name }}</li>
                        <li>Catch Phrase: {{ user.company.catchPhrase }}</li>
                        <li>Bs: {{ user.company.bs }}</li>
                    </ul>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { useUserData } from './composables/useUserData.js';
import {ref} from "vue";

export default {
    setup() {
        const { userData, showAdditionalData, loadAdditionalData } = useUserData();

        // Initialize an array to track which user's additional data is shown
        const showAdditionalDataIndex = ref(new Array(userData.length).fill(false));


        // Function to toggle the additional data for a specific user
        const toggleAdditionalData = (index) => {
            showAdditionalDataIndex.value[index] = !showAdditionalDataIndex.value[index];
            if (showAdditionalDataIndex.value[index]) {
                // Load additional data for the selected user
                loadAdditionalData(index);
            }
        };

        return { userData, showAdditionalData, loadAdditionalData, toggleAdditionalData };
    },
};
</script>
