<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Csv/Excel Data') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status')}}
                    </div>
                    @endif
                    @if ($errors && $errors->any())
                    <div class="mb-4 font-medium text-sm text-red-600">
                        @foreach($errors->all() as $error)
                        {{$error}}
                        @endforeach
                    </div>
                    @endif
                    @if (session()->has('failures'))

                    <table class="table-auto full-width border-1">
                        <tr>
                            <th>Sr No</th>
                            <th>Attribute</th>
                            <th>Errors</th>
                            <th>Value</th>
                        </tr>

                        @foreach (session()->get('failures') as $validation)
                        <tr class="text-red-600">
                            <td>{{ $validation->row() }}</td>
                            <td>{{ $validation->attribute() }}</td>
                            <td>
                                <ul>
                                    @foreach ($validation->errors() as $e)
                                    <li class="text-red">{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                {{ $validation->values()[$validation->attribute()] }}
                            </td>
                        </tr>
                        @endforeach
                    </table>

                    @endif

                    <form method="POST" action="{{ route('contacts.store') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- chose File -->
                        <div>
                            <x-label for="file" :value="__('File')" />

                            <x-input id="file" class="block mt-1 w-full" type="file" name="file" :value="old('file')"
                                required autofocus />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-3">
                                {{ __('Save') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>