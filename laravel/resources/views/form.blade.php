<x-guest-layout>
    <div>
        <div class="mx-auto sm:px-6 lg:px-8 py-12">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Device information</h2>

            <p class="mt-4 text-gray-500 dark:text-gray-400 text-lg leading-relaxed">
                Please fill out as many details <strong>as you can</strong> about the device you would like to test.
            </p>
            @if(!is_null($record))
            <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md dark:bg-gray-600 dark:text-teal-400 dark:border-teal-800" role="alert">
                <div class="flex">
                    <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                    <div>
                        <p class="font-bold">Generate new URL after software update</p>
                        <p class="text-sm">Please provide the newly installed software version in the field below and generate a new URL to test with the new software.<br/><br/>If your device did not tell you the new software version, you can just leave the field blank and generate a new URL.</p>
                    </div>
                </div>
            </div>
            @endif
            <form class="mt-4" method="post" action="/enroll" x-data="{loading:false}" @submit="loading=true;">
                @csrf
                @if(!is_null($record)) <input type="hidden" name="update_token" value="{{$record->token}}"/> @endif
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-white" for="type">
                        Device type
                    </label>
                    <input :readonly="loading" name="type" @if(is_null($record)) autofocus @endif class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="type" type="text" placeholder="ex. Smart TV / Set-top box / Game Console / ..."
                           value="@if(!is_null($record)){{$record->type}}@else{{old('type')}}@endif"/>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-white" for="vendor">
                        Vendor
                    </label>
                    <input :readonly="loading" name="vendor" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="vendor" type="text" placeholder="ex. Sony / Panasonic / ..."
                           value="@if(!is_null($record)){{$record->vendor}}@else{{old('vendor')}}@endif" />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-white @error('warning') text-red-500 @enderror" for="model">
                        Device model
                    </label>
                    @error('warning') <input type="hidden" name="ignore" value="1"/>@enderror
                    <input :readonly="loading" name="model" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('warning') border-red-500 @enderror dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="model" type="text" placeholder="ex. Bravia OLED / XR-55A90J / ..."
                           value="@if(!is_null($record)){{$record->model}}@else{{old('model')}}@endif" />
                    @error('warning') <p class="text-red-500 text-xs italic">Please provide at least a model if possible.<br/>Press the generate button again to ignore this warning.</p>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-white" for="year">
                        Year of purchase
                    </label>
                    <input :readonly="loading" name="year" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outlined dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="year" type="text" placeholder="ex. 2019 / ..."
                           value="@if(!is_null($record)){{$record->year}}@else{{old('year')}}@endif" />
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-white" for="version">
                        Software version
                    </label>
                    <input :readonly="loading" name="version" class="shadow appearance-none border rounded w-full py-2 mb-3 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="version" type="text" placeholder="ex. v1.4 / ..."
                    @if(!is_null($record)) autofocus @endif value="{{old('version')}}" />
                    @if(is_null($record)) <p class="text-gray-600 italic text-xs mb-3 dark:text-white">In case your device uses some older software version, you are invited <strong>NOT</strong> to update it. We would like to collect results for the version you are currently using, and optionally once more after you update the software.</p> @endif
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-white" for="notes">
                        Remarks
                    </label>
                    <textarea :readonly="loading" name="notes" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="notes" type="text" rows="3" placeholder="Any other relevant information...">{{old('notes')}}</textarea>
                </div>
                <button class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded w-full text-center text-xl disabled:opacity-25 disabled:cursor-not-allowed"
                        x-bind:disabled="loading">
                    <span x-show="!loading">Generate unique testing URL</span>
                    <svg x-show="!loading" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 inline align-middle">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                    <svg x-show="loading" aria-hidden="true" role="status" class="align-middle inline w-6 h-6 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
