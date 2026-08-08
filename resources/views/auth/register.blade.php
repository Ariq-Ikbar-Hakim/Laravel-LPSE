<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- NIP -->
        <div>
            <x-input-label for="nip" :value="__('NIP')" />
            <x-text-input id="nip" class="block mt-1 w-full" type="text" name="nip" :value="old('nip')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('nip')" class="mt-2" />
        </div>

        <!-- Nama -->
        <div class="mt-4">
            <x-input-label for="nama" :value="__('Nama Lengkap')" />
            <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama" :value="old('nama')" required autocomplete="name" />
            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
        </div>

        <!-- OPD -->
        <div class="mt-4">
            <x-input-label for="opd" :value="__('Organisasi Perangkat Daerah (OPD)')" />
            <x-text-input id="opd" class="block mt-1 w-full" type="text" name="opd" :value="old('opd')" required />
            <x-input-error :messages="$errors->get('opd')" class="mt-2" />
        </div>

        <!-- Sub Unit OPD -->
        <div class="mt-4">
            <x-input-label for="sub_unit_opd" :value="__('Sub Unit OPD')" />
            <x-text-input id="sub_unit_opd" class="block mt-1 w-full" type="text" name="sub_unit_opd" :value="old('sub_unit_opd')" required />
            <x-input-error :messages="$errors->get('sub_unit_opd')" class="mt-2" />
        </div>

        <!-- Jabatan Aktif -->
        <div class="mt-4">
            <x-input-label for="jabatan_aktif" :value="__('Jabatan Aktif')" />
            <select id="jabatan_aktif" name="jabatan_aktif" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                <option value="" disabled selected>Pilih Jabatan</option>
                <option value="PPK" {{ old('jabatan_aktif') == 'PPK' ? 'selected' : '' }}>PPK (Pejabat Pembuat Komitmen)</option>
                <option value="PP" {{ old('jabatan_aktif') == 'PP' ? 'selected' : '' }}>PP (Pejabat Pengadaan)</option>
            </select>
            <x-input-error :messages="$errors->get('jabatan_aktif')" class="mt-2" />
        </div>

        <!-- Nomor SK -->
        <div class="mt-4">
            <x-input-label for="sk_nomor" :value="__('Nomor SK')" />
            <x-text-input id="sk_nomor" class="block mt-1 w-full" type="text" name="sk_nomor" :value="old('sk_nomor')" required />
            <x-input-error :messages="$errors->get('sk_nomor')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
