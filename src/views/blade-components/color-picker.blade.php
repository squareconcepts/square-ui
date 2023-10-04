<div class="sc-color-input">
    <div class="flex justify-between items-end mb-1">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
            {{$label}}
        </label>
    </div>
    <div class="relative rounded-md  shadow-sm ">
        <input class="placeholder-secondary-400 h-[40px] dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm"
               type="color" {{ $attributes }}>
    </div>
    <style>
        .sc-color-input input[type="color"] {
            -webkit-appearance: none;
            border: none;
            border-radius: 5px;
            overflow: hidden;
            height:30px;
        }
        .sc-color-input input[type="color"]::-webkit-color-swatch-wrapper {
            padding: 0;
            border-radius: 5px;
        }
        .sc-color-input input[type="color"]::-webkit-color-swatch {
            border: none;
        }
    </style>
</div>
