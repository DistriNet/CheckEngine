import './bootstrap';

import Alpine from 'alpinejs';
import {data} from "autoprefixer";

window.Alpine = Alpine;

Alpine.data('statusPage', () => ({
    async ajaxInterval() {
        const res = await window.axios.get(window.location.href);
        this.statusCode = res.data.status;
        setInterval(async () => {
            if (this.statusCode < 4) {
                const res = await window.axios.get(window.location.href);
                this.statusCode = res.data.status;
            }
        }, 5000);
    },
    statusCode: 1,
    greenColors: 'text-green-700 border-green-300 bg-green-50 dark:bg-gray-600 dark:border-green-800 dark:text-green-400 w-full rounded-lg border',
    grayColors: 'text-gray-900 bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-700 dark:text-gray-400 w-full rounded-lg border',
    blueColors: 'text-blue-700 bg-blue-100 border-blue-300 dark:bg-gray-600 dark:border-blue-800 dark:text-blue-400 w-full border-t-4 rounded-b shadow-md',
    async setUpdate(state){
        const token = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
        const res = await window.axios.post('/update_status/' + token, {
            update_status: state,
        });
        if (res.data.href){
            window.location.href = res.data.href ;
        }else{
            this.statusCode = res.data.status;
        }
    }
}));

Alpine.start();
