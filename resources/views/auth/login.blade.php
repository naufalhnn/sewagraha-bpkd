<x-app-layout>
		<x-slot name="title">
				Login - SEWAGRAHA
		</x-slot>

		<div class="items-center justify-center py-12">
				<div class="mx-auto max-w-md sm:px-6 lg:px-8">
						<div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
								<div class="border-b border-gray-200 bg-white p-6">
										<img class="mx-auto w-20" src="{{ asset('icons/LogoKabPekalongan.png') }}" alt="">
										<h2 class="mt-2 text-center text-lg font-normal leading-9 text-gray-900">
												Login untuk Masuk
										</h2>

										@if (session('status'))
												<div class="mb-4 text-sm font-medium text-green-600">
														{{ session('status') }}
												</div>
										@endif

										<form method="POST" action="{{ route('login') }}">
												@csrf

												<div class="mb-4">
														<label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
														<x-forms.input id="email" class="outline-primary mt-1 block h-10 w-full border border-gray-300 px-3"
																type="email" name="email" :value="old('email')" required autofocus :error="$errors->first('email')" />
												</div>

												<div class="mb-4">
														<label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
														<x-forms.input id="password" class="outline-primary mt-1 block h-10 w-full border border-gray-300 px-3"
																type="password" name="password" required :error="$errors->first('password')" />
												</div>

												<div class="mb-4 block">
														<label for="remember_me" class="inline-flex items-center">
																<input id="remember_me" type="checkbox"
																		class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
																		name="remember">
																<span class="ml-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
														</label>
												</div>

												<div class="mt-4 flex items-center justify-between">
														<a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('register') }}">
																{{ __('Belum punya akun?') }}
														</a>

														<x-forms.button>
																{{ __('Log in') }}
														</x-forms.button>
												</div>
										</form>
								</div>
						</div>
				</div>
		</div>
</x-app-layout>
