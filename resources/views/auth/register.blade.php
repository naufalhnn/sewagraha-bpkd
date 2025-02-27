<x-app-layout>
		<x-slot name="title">
				Daftar - SEWAGRAHA
		</x-slot>

		<div class="py-12">
				<div class="mx-auto max-w-md sm:px-6 lg:px-8">
						<div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
								<div class="border-b border-gray-200 bg-white p-6">
										<img class="mx-auto w-20" src="{{ asset('icons/LogoKabPekalongan.png') }}" alt="">
										<h2 class="mt-2 text-center text-lg font-normal leading-9 text-gray-900">
												Daftar Akun
										</h2>

										<form method="POST" action="{{ route('register') }}">
												@csrf
												<div class="mb-4">
														<label for="name" class="block text-sm font-medium text-gray-700">{{ __('Nama') }}</label>
														<x-forms.input id="name" class="outline-primary mt-1 block h-10 w-full border border-gray-300 px-3"
																type="text" name="name" :value="old('name')" required autofocus :error="$errors->first('name')" />
												</div>

												<div class="mb-4">
														<label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
														<x-forms.input id="email" class="outline-primary mt-1 block h-10 w-full border border-gray-300 px-3"
																type="email" name="email" :value="old('email')" required :error="$errors->first('email')" />
												</div>

												<div class="mb-4">
														<label for="phone" class="block text-sm font-medium text-gray-700">{{ __('Nomor Telepon') }}</label>
														<x-forms.input id="phone" class="outline-primary mt-1 block h-10 w-full border border-gray-300 px-3"
																type="number" name="phone" :value="old('phone')" required :error="$errors->first('phone')" />
												</div>

												<div class="mb-4">
														<label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
														<x-forms.input id="password" class="outline-primary mt-1 block h-10 w-full border border-gray-300 px-3"
																type="password" name="password" required :error="$errors->first('password')" />
												</div>

												<div class="mb-4">
														<label for="password_confirmation"
																class="block text-sm font-medium text-gray-700">{{ __('Konformasi Password') }}</label>
														<x-forms.input id="password_confirmation"
																class="outline-primary mt-1 block h-10 w-full border border-gray-300 px-3" type="password"
																name="password_confirmation" required />
												</div>

												<div class="mt-4 flex items-center justify-between">
														<a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('login') }}">
																{{ __('Sudah punya akun?') }}
														</a>

														<x-forms.button>
																{{ __('Daftar') }}
														</x-forms.button>
												</div>
										</form>
								</div>
						</div>
				</div>
		</div>
</x-app-layout>
