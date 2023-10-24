import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';

export function useUserData() {
    const userData = reactive([{
        name: '',
        username: '',
        email: '',
        website: '',
        role: '',
        address: '',
        company: '',
    }]);

    const showAdditionalData = ref([]);

    const loadAdditionalData = async (index) => {
        try {
            const response = await axios.get('/proxy-users');
            const additionalData = response.data[index];

            userData[index].address = additionalData.address;
            userData[index].company = additionalData.company;

            showAdditionalData.value[index] = true;
        } catch (error) {
            console.error(error);
        }
    };

    const fetchData = async () => {
        try {
            const response = await axios.get('/proxy-users');
            Object.assign(userData, response.data);
        } catch (error) {
            console.error(error);
        }
    };

    onMounted(fetchData);

    return { userData, showAdditionalData, loadAdditionalData };
}
