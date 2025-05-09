<x-guest-layout>
    <div>
        <div class="mx-auto sm:px-6 lg:px-8 py-12">
            <h2 class="text-3xl font-semibold text-gray-900 dark:text-white">Ready to test!</h2>

            <p class="mt-4 text-gray-500 dark:text-gray-400 text-lg leading-relaxed">
                To start the test, please open the web browser on your embedded device and navigate to the following URL:
            </p>
            <h3 class="text-xl font-semibold dark:text-white">{{ $short_url }}</h3>
            <p class="my-4 text-gray-500 dark:text-gray-400 text-lg leading-relaxed">
                Please do not try to use the URL on any other devices, as it will only work once for this specific device. You can generate a new unique URL after completion of the tests.
            </p>
            <ol class="space-y-4" x-data="statusPage" x-init="ajaxInterval">
                <li>
                    <div role="alert" class="grid grid-cols-12 p-4"
                         :class="statusCode == 1 ? blueColors : statusCode > 1 ? greenColors : grayColors"
                    >
                        <div class="col-span-10">
                            <h3 class="font-medium">1. Wait for page to be opened on device</h3>
                        </div>
                        <div class="col-span-2">
                            <svg x-show="statusCode == 1" aria-hidden="true" role="status" class="fill-current h-8 w-8 animate-spin ml-auto mr-0" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                            </svg>
                            <svg x-show="statusCode > 1" class="w-6 h-6 ml-auto mr-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                            </svg>
                        </div>
                        <div x-show="statusCode == 1" class="col-span-10">
                            <p class="text-sm my-2">After opening the page, the tests will start running automatically and can take a few minutes to complete.</p>
                            <p class="text-sm">Please don't refresh this page, it will update automatically every 5 seconds.</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div role="alert" class="grid grid-cols-12 p-4" :class="statusCode == 2 ? blueColors : statusCode > 2 ? greenColors : grayColors">
                        <div class="col-span-10">
                            <h3 class="font-medium">2. Collect browser engine information</h3>
                        </div>
                        <div class="col-span-2">
                            <svg x-show="statusCode == 2" aria-hidden="true" role="status" class="fill-current h-8 w-8 animate-spin ml-auto mr-0" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                            </svg>
                            <svg x-show="statusCode > 2" class="w-6 h-6 mr-0 ml-auto" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                            </svg>
                        </div>
                        <div x-show="statusCode == 2" class="col-span-10">
                            <p class="text-sm my-2">Waiting to receive browser engine information...</p>
                            <p class="text-sm">Please don't refresh this page, it will update automatically every 5 seconds.</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div role="alert" class="grid grid-cols-12 p-4" :class="statusCode == 3 ? blueColors : statusCode > 3 ? greenColors : grayColors">
                        <div class="col-span-10">
                            <h3 class="font-medium">3. Collect browser test results</h3>
                        </div>
                        <div class="col-span-2">
                            <svg x-show="statusCode == 3" aria-hidden="true" role="status" class="fill-current h-8 w-8 animate-spin ml-auto mr-0" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                            </svg>
                            <svg x-show="statusCode > 3" class="w-6 h-6 ml-auto mr-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                            </svg>
                        </div>
                        <div class="col-span-10" x-show="statusCode == 3">
                            <p class="text-sm my-2">Waiting to receive test results, this might take a couple of minutes...</p>
                            <p class="text-sm">Please don't refresh this page, it will update automatically every 5 seconds.</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div role="alert" class="grid grid-cols-12 p-4" :class="statusCode == 4 ? blueColors : statusCode > 4 ? (greenColors + ' cursor-pointer') : grayColors" @click="if (statusCode > 4) statusCode = 4;">
                        <div class="col-span-10">
                            <h3 class="font-medium">4. Attempt software update for device</h3>
                        </div>
                        <div class="col-span-2">
                            <svg x-show="statusCode > 4" class="w-6 h-6 ml-auto mr-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                            </svg>
                        </div>
                        <div x-show="statusCode == 4" class="col-span-12">
                            <p class="text-sm my-2">We would like to try and see if a software update is available for your device.<br/> You can now close the web browser on your device and try to go into your device's settings and see if it is possible to update the software.</p>
                            <button @click="setUpdate('bail')" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded w-full text-center text-md">I have no interest in/time for updating the software</button>
                            <button @click="setUpdate('fail')" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded w-full text-center text-md">I did not find any option to update the software</button>
                            <button @click="setUpdate('updated')" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded w-full text-center text-md">A software update was available and I installed it</button>
                            <button @click="setUpdate('noupdate')" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded w-full text-center text-md">The software was already up to date</button>
                        </div>
                    </div>
                </li>
                <li>
                    <div role="alert" class="grid grid-cols-12 p-4" :class="statusCode == 5 ? greenColors : grayColors">
                        <div class="col-span-10">
                            <h3 class="font-medium">5. Complete</h3>
                        </div>
                        <div class="col-span-2">
                            <svg x-show="statusCode == 5" class="w-6 h-6 ml-auto mr-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                            </svg>
                        </div>
                        <div class="col-span-12" x-show="statusCode == 5">
                            <p class="text-sm my-2">Your results were saved! Thank you for your time in participating in this study.</p>
                            <p class="text-sm my-2">Feel free to run the test on another device by clicking the button below:</p>
                            <a role="button" href="/enroll">
                                <div class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded w-full text-center text-xl">
                                    Test another device
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 inline align-middle">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>
                </li>
            </ol>
        </div>
    </div>
</x-guest-layout>
